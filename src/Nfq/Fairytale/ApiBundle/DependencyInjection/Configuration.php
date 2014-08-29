<?php

namespace Nfq\Fairytale\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rest_api');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode->children()
            ->scalarNode('index_size')->defaultValue(10)->cannotBeEmpty()->end()
            ->arrayNode('mapping')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('name')
                ->prototype('scalar')->end()
            ->end()
            ->arrayNode('data')
                ->children()
                    ->scalarNode('type')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('source')->isRequired()->cannotBeEmpty()->end()
                ->end()
            ->end()
            ->arrayNode('security')
                ->children()
                    ->arrayNode('acl')
                        ->requiresAtLeastOneElement()
                        ->useAttributeAsKey('name')
                        ->prototype('variable')
                        ->end()
                    ->end()
                    ->scalarNode('default_credential')->isRequired()->cannotBeEmpty()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
