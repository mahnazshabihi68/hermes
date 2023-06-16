<?php

namespace App\Repositories\Log\Impls;

use App\Models\Log;
use App\Repositories\Base\Impls\BaseRepository;
use App\Repositories\Log\Interfaces\ILogRepository;
use JetBrains\PhpStorm\Pure;

class LogRepository extends BaseRepository implements ILogRepository
{
    /**
     * @param  Log  $model
     */
    #[Pure] public function __construct(Log $model)
    {
        parent::__construct($model);
    }
}
