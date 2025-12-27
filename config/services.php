<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

use Tiloweb\PaginationBundle\Service\Paginator;
use Tiloweb\PaginationBundle\Twig\PaginationExtension;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    // Paginator service
    $services->set(Paginator::class)
        ->arg('$defaultItemsPerPage', param('tiloweb_pagination.default_items_per_page'))
        ->alias('tiloweb_pagination.paginator', Paginator::class)
        ->public();

    // Twig extension
    $services->set(PaginationExtension::class)
        ->arg('$twig', service('twig'))
        ->arg('$requestStack', service('request_stack'))
        ->arg('$template', param('tiloweb_pagination.template'))
        ->arg('$pageRange', param('tiloweb_pagination.page_range'))
        ->tag('twig.extension');
};
