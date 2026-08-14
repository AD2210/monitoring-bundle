<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Metrics;

/**
 * Provides stable process-level metrics for a monitored application.
 */
final readonly class ApplicationMetricsProvider implements MetricsProviderInterface
{
    public function __construct(
        private string $applicationName,
        private string $environment,
    ) {
    }

    /**
     * Renders metrics that do not require local persistence.
     *
     * Prometheus owns historical values, so the bundle only needs to expose
     * the current observation and avoid introducing a second status database.
     */
    public function render(): string
    {
        return implode("\n", [
            '# HELP ad2210_monitoring_up Whether the monitoring integration is available.',
            '# TYPE ad2210_monitoring_up gauge',
            sprintf('ad2210_monitoring_up{application="%s",environment="%s"} 1', $this->escapeLabel($this->applicationName), $this->escapeLabel($this->environment)),
            '',
        ]);
    }

    /**
     * Escapes values according to the Prometheus label format.
     */
    private function escapeLabel(string $value): string
    {
        return str_replace(['\\', '"', "\n"], ['\\\\', '\\"', '\\n'], $value);
    }
}
