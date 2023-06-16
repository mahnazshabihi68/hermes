<?php

namespace App\Services\Log\Impls;

use App\DTO\Log\LogEventDTO;
use App\Repositories\Log\Interfaces\ILogMongoDBRepository;
use App\Repositories\Log\Interfaces\ILogRepository;
use App\Services\Log\Interfaces\ILogService;
use App\Transformers\LogTransformer;
use Illuminate\Support\Facades\Log;
use Throwable;

class LogService implements ILogService
{
    /**
     * @param  ILogRepository  $logRepository
     * @param  ILogMongoDBRepository  $logMongoDBRepository
     */
    public function __construct(
        private readonly ILogRepository $logRepository,
        private readonly ILogMongoDBRepository $logMongoDBRepository
    ) {
    }

    /**
     * @inheritDoc
     */
    public function logToMongoDB(LogEventDTO $logEventDto): void
    {
        try {
            $logCreationDTO = LogTransformer::toLogCreationDTO($logEventDto);
            $logMongoDBModel = LogTransformer::toLogMongoCreateEntity($logCreationDTO);
            $this->logMongoDBRepository->create($logMongoDBModel);
        } catch (Throwable $throwable) {
            $data = ['line' => $throwable->getLine(), 'file' => $throwable->getFile()];
            Log::critical($throwable->getMessage(), $data);
        }
    }

    /**
     * @inheritDoc
     */
    public function logToMySQL(LogEventDTO $logEventDto): void
    {
        try {
            $logCreationDTO = LogTransformer::toLogCreationDto($logEventDto);
            $logModel = LogTransformer::toLogCreateEntity($logCreationDTO);
            $this->logRepository->create($logModel);
        } catch (Throwable $throwable) {
            $data = ['line' => $throwable->getLine(), 'file' => $throwable->getFile()];
            Log::critical($throwable->getMessage(), $data);
        }
    }
}
