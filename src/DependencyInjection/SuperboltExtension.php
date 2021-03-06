<?php

namespace Superbolt\SuperboltBundle\DependencyInjection;

use Superbolt\SuperboltBundle\EventListener\ConsoleEventsListener;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SuperboltExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $definition = $container->getDefinition(ConsoleEventsListener::class);
        $definition->replaceArgument('$environment', $config['environment']);
        $definition->replaceArgument('$secret', $config['secret']);
        $definition->replaceArgument('$endpoint', $config['endpoint']);
        $definition->replaceArgument('$commands', $config['commands']);

        $container->setParameter('$environment', $config['environment']);
        $container->setParameter('$secret', $config['secret']);
        $container->setParameter('$endpoint', $config['endpoint']);
        $container->setParameter('$commands', $config['commands']);
    }
}
