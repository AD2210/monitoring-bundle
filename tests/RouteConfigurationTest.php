<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\AttributeClassLoader;
use Symfony\Component\Routing\Loader\AttributeDirectoryLoader;
use Symfony\Component\Routing\Route;

/**
 * Verifies that the bundle exposes importable routes.
 */
final class RouteConfigurationTest extends TestCase
{
    /**
     * Ensures the liveness route is present in the bundle route collection.
     */
    public function testBundleRoutesCanBeImported(): void
    {
        $locator = new FileLocator(__DIR__.'/../src/Resources/config');
        $attributeClassLoader = new class () extends AttributeClassLoader {
            /**
             * @param \ReflectionClass<object> $class
             */
            protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, object $attribute): void
            {
                $route->setDefaults(['_controller' => $class->getName().'::'.$method->getName()]);
            }
        };
        $attributeLoader = new AttributeDirectoryLoader($locator, $attributeClassLoader);
        $loader = new PhpFileLoader($locator);
        $loader->setResolver(new LoaderResolver([$loader, $attributeLoader]));
        $routes = $loader->load('routes.php');

        self::assertInstanceOf(RouteCollection::class, $routes);
        self::assertNotNull($routes->get('ad2210_monitoring_health_live'));
    }
}
