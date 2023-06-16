<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Console\Commands;

use App\Classes\Binance;
use App\Models\Market;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use WebSocket\Client;
use WebSocket\ConnectionException;

class WebsocketListenCommand extends Command
{
    protected $signature = 'websockets:listen';

    protected $description = 'Listens to external websockets.';

    /**
     * Defines Array of actions we want to listen on each market's channel.
     */

    protected array $actions = [
        '@depth20@1000ms',
        '@trade',
        '@ticker'
    ];

    /**
     * @return void
     */

    public function handle(): void
    {
        /**
         * Fetch markets which have websocket streams.
         */

        $markets = Market::isActive()->hasWebsocketStream()->get()->map(fn($market) => strtolower(str_replace('-', '', $market->market)));

        /**
         * Generate wss route.
         */

        $endpoint = Binance::websocketStreamBase . 'stream?streams=';

        foreach ($markets as $market) {

            foreach ($this->actions as $action) {

                $endpoint .= $market . $action . '/';

            }

        }

        $endpoint = substr($endpoint, 0, -1);

        /**
         * Connect to stream.
         */

        $client = new Client($endpoint);

        /**
         * Get messages.
         */

        while (true) {

            try {

                /**
                 * Receive and cast message.
                 */

                collect($client->receive())->each(function ($stream) {

                    $stream = json_decode($stream);

                    $exploded = explode('@', $stream->stream);

                    $market = $exploded[0];

                    $channel = $exploded[1];

                    match ($channel) {
                        'depth20' => $this->handleOrderBook($market, $stream->data),
                        'trade' => $this->handleTrades($market, $stream->data),
                        'ticker' => $this->handleTicker($market, $stream->data)
                    };

                });

            } catch (ConnectionException|Exception $exception) {

                $this->error($exception->getMessage());

                break;
            }

        }
    }

    /**
     * @param string $market
     * @param object $stream
     * @return void
     */

    private function handleOrderBook(string $market, object $stream): void
    {
        try {

            $orderBook = collect($stream)->only(['bids', 'asks'])->map(function ($orders, $list) {

                return collect($orders)->transform(function ($order) use ($list) {

                    return [
                        'original_quantity' => $order[1],
                        'original_price' => $order[0],
                        'type' => 'LIMIT',
                        'side' => $list == 'bids' ? 'BUY' : 'SELL',
                        'remained_quantity' => $order[1],
                    ];

                });

            });

            /**
             * Set orderbook in redis.
             */

            Redis::set('external-orderbook:' . $market, $orderBook->toJson(), 'EX', 10);

        } catch (Exception $exception) {

            $this->error($exception->getMessage());

        }
    }

    /**
     * @param string $market
     * @param object $stream
     * @return void
     */

    private function handleTrades(string $market, object $stream): void
    {
        try {

            /**
             * Update exchange price of market.
             */

            $marketORM = Market::market(strtoupper($market))->firstOrFail();

            $marketORM->exchange_price = $stream->p;

            $marketORM->save();

            /**
             * New trade.
             */

            $newTrade = collect([
                'original_quantity' => $stream->q,
                'original_price' => $stream->p,
                'side' => $stream->m ? 'BUY' : 'SELL',
                'created_at' => now(),
            ]);

            /**
             * Update trades of market.
             */

            $trades = Redis::get('external-trades:' . $market);

            /**
             * Push new trade to existing trades.
             */

            $trades = collect(json_decode($trades))->prepend($newTrade);

            /**
             * Handle count of stored trades.
             */

            if ($trades->count() > 50) {

                $trades = $trades->take(50);

            }


            /**
             * Set trades to redis.
             */

            Redis::set('external-trades:' . $market, $trades->values()->toJson(), 'EX', 10);

        } catch (Exception $exception) {

            $this->error($exception->getMessage());

        }

    }

    /**
     * @param string $market
     * @param object $stream
     * @return void
     */

    private function handleTicker(string $market, object $stream): void
    {
        try {

            $ticker = collect([
                'day_highest_price' => $stream->h,
                'day_lowest_price' => $stream->l,
                'day_price_change' => $stream->p,
                'day_price_change_percent' => $stream->P,
                'best_bid' => $stream->b,
                'best_ask' => $stream->a,
                'last_price' => $stream->c,
                'src_volume' => $stream->v,
                'dst_volume' => $stream->q
            ]);

            /**
             * Set ticker in redis.
             */

            Redis::set('external-ticker:' . $market, $ticker->toJson(), 'EX', 10);

        } catch (Exception $exception) {

            $this->error($exception->getMessage());

        }
    }
}
