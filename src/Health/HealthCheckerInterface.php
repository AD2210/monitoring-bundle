<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Health;

/**
 * Provides health reports for monitoring endpoints.
 */
interface HealthCheckerInterface
{
    /**
     * Checks whether the application process is alive.
     */
    public function checkLiveness(): HealthReport;
}
