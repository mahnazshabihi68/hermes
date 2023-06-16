<?php

namespace App\DTO\Log;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

class LogCreationDTO extends DataTransferObject
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
     * @var string
     */
    #[MapFrom('data')]
    #[MapTo('data')]
    public string $data;

    /**
     * @var string|null
     */
    #[MapFrom('loggable_type')]
    #[MapTo('loggable_type')]
    public ?string $loggableType;

    /**
     * @var string|null
     */
    #[MapFrom('loggable_id')]
    #[MapTo('loggable_id')]
    public ?string $loggableId;

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
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function getLoggableType(): ?string
    {
        return $this->loggableType;
    }

    /**
     * @param string|null $loggableType
     */
    public function setLoggableType(?string $loggableType): void
    {
        $this->loggableType = $loggableType;
    }

    /**
     * @return string|null
     */
    public function getLoggableId(): ?string
    {
        return $this->loggableId;
    }

    /**
     * @param string|null $loggableId
     */
    public function setLoggableId(?string $loggableId): void
    {
        $this->loggableId = $loggableId;
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
