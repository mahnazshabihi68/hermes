<?php

namespace App\Repositories\Log\Impls;

use App\Models\LogMongoDB;
use App\Repositories\Base\Impls\BaseRepository;
use App\Repositories\Log\Interfaces\ILogMongoDBRepository;
use JetBrains\PhpStorm\Pure;

class LogMongoDBRepository extends BaseRepository implements ILogMongoDBRepository
{
    /**
     * @param  LogMongoDB  $model
     */
    #[Pure] public function __construct(LogMongoDB $model)
    {
        parent::__construct($model);
    }
}
