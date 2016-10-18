<?php

namespace Iulyanp\MaintenanceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class IulyanpMaintenanceExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('iulyanp_maintenance.enabled', $config['enabled']);
        $container->setParameter('iulyanp_maintenance.due_date', $config['due_date']);
        $container->setParameter('iulyanp_maintenance.signature', $config['layout']['signature']);
        $container->setParameter('iulyanp_maintenance.title', $config['layout']['title']);
        $container->setParameter('iulyanp_maintenance.description', $config['layout']['description']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $definition = $container->findDefinition('iulyanp_maintenance.maintenance_subscriber');
        $definition->addArgument($config);
    }
}
