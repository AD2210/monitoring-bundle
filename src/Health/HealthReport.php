<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Health;

/**
 * Represents the public result of a health check.
 */
final readonly class HealthReport
{
    /**
     * @param array<string, string> $checks
     */
    public function __construct(
        public string $status,
        public string $application,
        public string $environment,
        public array $checks,
    ) {
    }

    /**
     * Converts the report to the stable JSON response structure.
     *
     * @return array{status: string, application: string, environment: string, checks: array<string, string>}
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'application' => $this->application,
            'environment' => $this->environment,
            'checks' => $this->checks,
        ];
    }
}
