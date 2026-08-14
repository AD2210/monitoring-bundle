<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Heartbeat;

/**
 * Represents one heartbeat observation emitted by a monitored application.
 */
final readonly class HeartbeatPayload
{
    public function __construct(
        public string $application,
        public string $environment,
        public string $version,
        public \DateTimeImmutable $observedAt,
    ) {
    }

    /**
     * Converts the heartbeat to the transport-neutral payload contract.
     *
     * @return array{application: string, environment: string, version: string, observed_at: string}
     */
    public function toArray(): array
    {
        return [
            'application' => $this->application,
            'environment' => $this->environment,
            'version' => $this->version,
            'observed_at' => $this->observedAt->format(\DateTimeInterface::ATOM),
        ];
    }
}
