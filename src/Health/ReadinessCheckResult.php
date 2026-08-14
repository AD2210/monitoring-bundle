<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Health;

/**
 * Represents one readiness dependency result.
 */
final readonly class ReadinessCheckResult
{
    public function __construct(
        public string $status,
        public ?string $message = null,
    ) {
    }

    /**
     * Indicates whether the dependency is available.
     */
    public function isSuccessful(): bool
    {
        return 'ok' === $this->status;
    }
}
