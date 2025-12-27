<?php

declare(strict_types=1);

namespace Tiloweb\PaginationBundle\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

/**
 * Paginator service for easy pagination of Doctrine queries.
 *
 * This service wraps Doctrine's Paginator to provide a simple and efficient
 * way to paginate database queries.
 *
 * @author Thibault HENRY <thibault@henry.pro>
 */
final readonly class Paginator
{
    public function __construct(
        private int $defaultItemsPerPage = 10,
    ) {
    }

    /**
     * Paginate a QueryBuilder with the specified page and items per page.
     *
     * @param QueryBuilder $queryBuilder The Doctrine QueryBuilder to paginate
     * @param int $page The current page number (1-indexed)
     * @param int|null $itemsPerPage The number of items per page (null uses default)
     * @param bool $fetchJoinCollection Whether the query contains a fetch-join collection
     *
     * @return PaginationResult<mixed> The pagination result containing items and metadata
     */
    public function paginate(
        QueryBuilder $queryBuilder,
        int $page = 1,
        ?int $itemsPerPage = null,
        bool $fetchJoinCollection = true,
    ): PaginationResult {
        $itemsPerPage ??= $this->defaultItemsPerPage;
        $page = max(1, $page);

        $query = $queryBuilder->getQuery();
        $query->setFirstResult(($page - 1) * $itemsPerPage);
        $query->setMaxResults($itemsPerPage);

        $paginator = new DoctrinePaginator($query, $fetchJoinCollection);

        return new PaginationResult(
            paginator: $paginator,
            currentPage: $page,
            itemsPerPage: $itemsPerPage,
        );
    }
}
