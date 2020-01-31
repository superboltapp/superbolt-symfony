<?php
namespace Superbolt\SuperboltBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('superbolt');

        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('environment')->defaultValue('dev')->end()
            ->scalarNode('secret')->defaultValue('test-api-key')->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
