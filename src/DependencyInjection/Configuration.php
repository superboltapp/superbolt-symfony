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
                ->scalarNode('environment')->end()
                ->scalarNode('secret')->end()
                ->scalarNode('endpoint')->defaultNull()->end()
                ->arrayNode('commands')->scalarPrototype()->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
