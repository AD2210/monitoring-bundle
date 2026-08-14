<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Defines and loads the ad2210 monitoring configuration.
 */
final class Ad2210MonitoringExtension extends Extension
{
    /**
     * Loads and validates the bundle configuration.
     *
     * @param array<array<string, mixed>> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $processedConfiguration = $this->processConfiguration($configuration, $configs);

        $container->setParameter('ad2210_monitoring.enabled', $processedConfiguration['enabled']);
        $container->setParameter('ad2210_monitoring.application_name', $processedConfiguration['application_name']);
        $container->setParameter('ad2210_monitoring.environment', $processedConfiguration['environment']);
        $container->setParameter('ad2210_monitoring.version', $processedConfiguration['version']);
        $container->setParameter('ad2210_monitoring.health_token', $processedConfiguration['health']['token']);
        $container->setParameter('ad2210_monitoring.metrics_token', $processedConfiguration['metrics']['token']);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');
    }

    /**
     * @param array<string, mixed> $config
     *
     * Returns the configuration tree used by Symfony's config processor.
     */
    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }
}

/**
 * Describes the public configuration options of the bundle.
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * Builds the configuration tree.
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ad2210_monitoring');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('enabled')->defaultTrue()->end()
                ->scalarNode('application_name')->defaultValue('unknown')->cannotBeEmpty()->end()
                ->scalarNode('environment')->defaultValue('prod')->cannotBeEmpty()->end()
                ->scalarNode('version')->defaultValue('unknown')->cannotBeEmpty()->end()
                ->arrayNode('health')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->scalarNode('token')->defaultValue('')->end()
                    ->end()
                ->end()
                ->arrayNode('metrics')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->scalarNode('token')->defaultValue('')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
