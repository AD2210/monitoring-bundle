<?php

declare(strict_types=1);

use Ad2210\MonitoringBundle\Controller\HealthController;
use Ad2210\MonitoringBundle\Health\ApplicationHealthChecker;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(ApplicationHealthChecker::class)
        ->arg('$applicationName', '%ad2210_monitoring.application_name%')
        ->arg('$environment', '%ad2210_monitoring.environment%');

    $services->alias(Ad2210\MonitoringBundle\Health\HealthCheckerInterface::class, ApplicationHealthChecker::class);

    $services->set(HealthController::class)
        ->arg('$healthToken', '%ad2210_monitoring.health_token%');
};
