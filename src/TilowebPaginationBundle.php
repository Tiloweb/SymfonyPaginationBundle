<?php

declare(strict_types=1);

namespace Tiloweb\PaginationBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * TilowebPaginationBundle - A simple and elegant pagination bundle for Symfony.
 *
 * @author Thibault HENRY <thibault@henry.pro>
 */
class TilowebPaginationBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('template')
                    ->defaultValue('@TilowebPagination/pagination.html.twig')
                    ->info('The Twig template used to render the pagination.')
                ->end()
                ->integerNode('page_range')
                    ->defaultValue(4)
                    ->min(1)
                    ->max(20)
                    ->info('Number of page links to show before and after the current page.')
                ->end()
                ->integerNode('default_items_per_page')
                    ->defaultValue(10)
                    ->min(1)
                    ->max(1000)
                    ->info('Default number of items per page.')
                ->end()
            ->end();
    }

    /**
     * @param array<string, mixed> $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->parameters()
            ->set('tiloweb_pagination.template', $config['template'])
            ->set('tiloweb_pagination.page_range', $config['page_range'])
            ->set('tiloweb_pagination.default_items_per_page', $config['default_items_per_page']);

        $container->import('../config/services.php');
    }
}
