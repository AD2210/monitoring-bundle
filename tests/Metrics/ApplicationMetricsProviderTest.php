<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Tests\Metrics;

use Ad2210\MonitoringBundle\Metrics\ApplicationMetricsProvider;
use PHPUnit\Framework\TestCase;

/**
 * Verifies Prometheus output from the default metrics provider.
 */
final class ApplicationMetricsProviderTest extends TestCase
{
    /**
     * Ensures identity labels are escaped and the availability metric is valid.
     */
    public function testProviderRendersEscapedIdentityLabels(): void
    {
        $provider = new ApplicationMetricsProvider("demo\\app", "prod\"blue");

        self::assertSame(
            "# HELP ad2210_monitoring_up Whether the monitoring integration is available.\n# TYPE ad2210_monitoring_up gauge\nad2210_monitoring_up{application=\"demo\\\\app\",environment=\"prod\\\"blue\"} 1\n",
            $provider->render(),
        );
    }
}
