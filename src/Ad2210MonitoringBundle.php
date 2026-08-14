<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle;

use Ad2210\MonitoringBundle\DependencyInjection\Ad2210MonitoringExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Provides monitoring endpoints and instrumentation for Symfony applications.
 */
final class Ad2210MonitoringBundle extends Bundle
{
    /**
     * Returns the dependency injection extension handled by this bundle.
     */
    public function getContainerExtension(): Ad2210MonitoringExtension
    {
        return new Ad2210MonitoringExtension();
    }

    /**
     * Builds the bundle container configuration.
     *
     * The method remains explicit so future service loading has one stable
     * integration point instead of relying on implicit framework conventions.
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}
