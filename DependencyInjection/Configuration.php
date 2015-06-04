<?php

namespace HP\Bundle\EmailApiBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('email_api')
            ->children()
            ->scalarNode('client_id')->end()
            ->scalarNode('client_secret')->end()
            ->scalarNode('redirect_uri')->end()
            ->end();

        return $treeBuilder;
    }
}
