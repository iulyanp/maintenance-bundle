<?php

namespace Iulyanp\MaintenanceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('iulyanp_maintenance');

        $rootNode
            ->children()
                ->booleanNode('enabled')
                    ->defaultTrue()
                    ->info('Should be true for maintenance.')
                ->end()

                ->scalarNode('due_date')
                    ->info('After the due date maintenance will be off.')
                    ->defaultValue((new \DateTime('- 1 minute'))->getTimestamp())
                ->end()

                ->scalarNode('maintenance_route')
                    ->info('The maintenance route.')
                    ->defaultValue('iulyanp_maintenance_homepage')
                ->end()

                ->arrayNode('layout')
                ->info('All the configurations needed in the default twig template of maintenance bundle.')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('title')
                        ->defaultValue('iulyanp_maintenance.title')
                    ->end()

                    ->scalarNode('description')
                        ->defaultValue('iulyanp_maintenance.description')
                    ->end()

                    ->scalarNode('signature')
                        ->defaultValue('iulyanp')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
