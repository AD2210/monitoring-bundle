<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Heartbeat;

/**
 * Creates heartbeat observations for the host application.
 */
final readonly class HeartbeatProvider
{
    public function __construct(
        private string $applicationName,
        private string $environment,
        private string $version,
    ) {
    }

    /**
     * Creates a heartbeat at the instant requested by the caller.
     */
    public function create(?\DateTimeImmutable $observedAt = null): HeartbeatPayload
    {
        return new HeartbeatPayload(
            application: $this->applicationName,
            environment: $this->environment,
            version: $this->version,
            observedAt: $observedAt ?? new \DateTimeImmutable(),
        );
    }
}
