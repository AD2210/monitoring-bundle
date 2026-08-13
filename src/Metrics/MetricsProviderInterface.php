<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Metrics;

/**
 * Provides metrics in Prometheus exposition format.
 */
interface MetricsProviderInterface
{
    /**
     * Returns the current metrics payload.
     */
    public function render(): string;
}
