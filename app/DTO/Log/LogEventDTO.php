<?php

namespace App\DTO\Log;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

class LogEventDTO extends DataTransferObject
{
    /**
     * @var string
     */
    #[MapFrom('channel')]
    #[MapTo('channel')]
    public string $channel;

    /**
     * @var string
     */
    #[MapFrom('level')]
    #[MapTo('level')]
    public string $level;

    /**
     * @var array
     */
    #[MapFrom('data')]
    #[MapTo('data')]
    public array $data;

    /**
     * @var string|null
     */
    #[MapFrom('ip')]
    #[MapTo('ip')]
    public ?string $ip;

    /**
     * @var string|null
     */
    #[MapFrom('device')]
    #[MapTo('device')]
    public ?string $device;

    /**
     * @var string|null
     */
    #[MapFrom('os')]
    #[MapTo('os')]
    public ?string $OS;

    /**
     * @var string|null
     */
    #[MapFrom('browser')]
    #[MapTo('browser')]
    public ?string $browser;

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @param string $level
     */
    public function setLevel(string $level): void
    {
        $this->level = $level;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string|null $ip
     */
    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return string|null
     */
    public function getDevice(): ?string
    {
        return $this->device;
    }

    /**
     * @param string|null $device
     */
    public function setDevice(?string $device): void
    {
        $this->device = $device;
    }

    /**
     * @return string|null
     */
    public function getOS(): ?string
    {
        return $this->OS;
    }

    /**
     * @param string|null $OS
     */
    public function setOS(?string $OS): void
    {
        $this->OS = $OS;
    }

    /**
     * @return string|null
     */
    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    /**
     * @param string|null $browser
     */
    public function setBrowser(?string $browser): void
    {
        $this->browser = $browser;
    }
}
