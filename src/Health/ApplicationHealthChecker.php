<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Health;

/**
 * Builds the basic health report for the host application.
 */
final readonly class ApplicationHealthChecker implements HealthCheckerInterface
{
    public function __construct(
        private string $applicationName,
        private string $environment,
    ) {
    }

    /**
     * Returns a liveness result without calling external dependencies.
     *
     * Liveness must remain independent from the database and other services;
     * otherwise a dependency outage can be mistaken for a dead process.
     */
    public function checkLiveness(): HealthReport
    {
        return new HealthReport(
            status: 'ok',
            application: $this->applicationName,
            environment: $this->environment,
            checks: ['application' => 'ok'],
        );
    }

    /**
     * Returns the initial readiness result until dependency checks are added.
     */
    public function checkReadiness(): HealthReport
    {
        return new HealthReport(
            status: 'ok',
            application: $this->applicationName,
            environment: $this->environment,
            checks: ['application' => 'ok'],
        );
    }
}
