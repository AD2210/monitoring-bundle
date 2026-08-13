<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Tests;

use Ad2210\MonitoringBundle\Ad2210MonitoringBundle;
use Ad2210\MonitoringBundle\DependencyInjection\Ad2210MonitoringExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Verifies the public bundle bootstrap contract.
 */
final class Ad2210MonitoringBundleTest extends TestCase
{
    /**
     * Ensures Symfony can discover the bundle extension.
     */
    public function testBundleExposesItsExtension(): void
    {
        $bundle = new Ad2210MonitoringBundle();

        self::assertInstanceOf(Ad2210MonitoringExtension::class, $bundle->getContainerExtension());
    }

    /**
     * Ensures default configuration is loaded into the container.
     */
    public function testDefaultConfigurationIsLoaded(): void
    {
        $container = new ContainerBuilder();
        $extension = new Ad2210MonitoringExtension();

        $extension->load([], $container);

        self::assertTrue($container->getParameter('ad2210_monitoring.enabled'));
        self::assertSame('unknown', $container->getParameter('ad2210_monitoring.application_name'));
        self::assertSame('prod', $container->getParameter('ad2210_monitoring.environment'));
    }
}
