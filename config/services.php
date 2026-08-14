<?php

declare(strict_types=1);

use Ad2210\MonitoringBundle\Controller\HealthController;
use Ad2210\MonitoringBundle\Health\ApplicationHealthChecker;
use Ad2210\MonitoringBundle\Health\ApplicationReadinessCheck;
use Ad2210\MonitoringBundle\Health\ReadinessChecker;
use Ad2210\MonitoringBundle\Heartbeat\HeartbeatProvider;
use Ad2210\MonitoringBundle\Command\HeartbeatCommand;
use Ad2210\MonitoringBundle\Controller\MetricsController;
use Ad2210\MonitoringBundle\Metrics\ApplicationMetricsProvider;
use Ad2210\MonitoringBundle\Metrics\MetricsProviderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(ApplicationHealthChecker::class)
        ->arg('$applicationName', '%ad2210_monitoring.application_name%')
        ->arg('$environment', '%ad2210_monitoring.environment%');

    $services->set(ApplicationReadinessCheck::class)
        ->tag('ad2210_monitoring.readiness_check');

    $services->set(ReadinessChecker::class)
        ->arg('$checks', tagged_iterator('ad2210_monitoring.readiness_check'));

    $services->alias(Ad2210\MonitoringBundle\Health\HealthCheckerInterface::class, ApplicationHealthChecker::class);

    $services->set(HealthController::class)
        ->arg('$healthToken', '%ad2210_monitoring.health_token%');

    $services->set(ApplicationMetricsProvider::class)
        ->arg('$applicationName', '%ad2210_monitoring.application_name%')
        ->arg('$environment', '%ad2210_monitoring.environment%');

    $services->set(HeartbeatProvider::class)
        ->arg('$applicationName', '%ad2210_monitoring.application_name%')
        ->arg('$environment', '%ad2210_monitoring.environment%')
        ->arg('$version', '%ad2210_monitoring.version%');

    $services->set(HeartbeatCommand::class);

    $services->alias(MetricsProviderInterface::class, ApplicationMetricsProvider::class);

    $services->set(MetricsController::class)
        ->arg('$metricsToken', '%ad2210_monitoring.metrics_token%');
};
