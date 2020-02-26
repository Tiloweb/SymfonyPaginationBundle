<?php

namespace Tiloweb\PaginationBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder('tiloweb_pagination');

        $rootNode = method_exists($treeBuilder, 'getRootNode') ? $treeBuilder->getRootNode(); : $treeBuilder->root('tiloweb_pagination');
        
        $rootNode
            ->children()
                ->scalarNode('template')
                ->defaultValue('@TilowebPagination/pagination.html.twig')
                ->end()
            ->end();
        
        return $treeBuilder;
    }
}
