<?php

declare(strict_types=1);

namespace Tiloweb\PaginationBundle\Service;

use Countable;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use IteratorAggregate;
use Traversable;

/**
 * Represents the result of a pagination operation.
 *
 * This class encapsulates all pagination metadata and provides convenient
 * methods to access pagination information.
 *
 * @template T
 *
 * @implements IteratorAggregate<int, T>
 *
 * @author Thibault HENRY <thibault@henry.pro>
 */
final class PaginationResult implements Countable, IteratorAggregate
{
    private DoctrinePaginator $paginator;
    private int $currentPage;
    private int $itemsPerPage;
    private int $totalItems;
    private int $totalPages;

    /**
     * @param DoctrinePaginator<T> $paginator The Doctrine paginator instance
     * @param int $currentPage The current page number
     * @param int $itemsPerPage Number of items per page
     */
    public function __construct(
        DoctrinePaginator $paginator,
        int $currentPage,
        int $itemsPerPage,
    ) {
        $this->paginator = $paginator;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
        $this->totalItems = $paginator->count();
        $this->totalPages = (int) ceil($this->totalItems / $itemsPerPage);
    }

    /**
     * Get the Doctrine Paginator instance.
     *
     * @return DoctrinePaginator<T>
     */
    public function getPaginator(): DoctrinePaginator
    {
        return $this->paginator;
    }

    /**
     * Get the current page number.
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Get the number of items per page.
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Get the total number of items across all pages.
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * Get the total number of pages.
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Check if there is a previous page.
     */
    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    /**
     * Check if there is a next page.
     */
    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    /**
     * Get the previous page number.
     *
     * @return int|null The previous page number or null if on the first page
     */
    public function getPreviousPage(): ?int
    {
        return $this->hasPreviousPage() ? $this->currentPage - 1 : null;
    }

    /**
     * Get the next page number.
     *
     * @return int|null The next page number or null if on the last page
     */
    public function getNextPage(): ?int
    {
        return $this->hasNextPage() ? $this->currentPage + 1 : null;
    }

    /**
     * Get the first item number on the current page (1-indexed).
     */
    public function getFirstItemOnPage(): int
    {
        if (0 === $this->totalItems) {
            return 0;
        }

        return ($this->currentPage - 1) * $this->itemsPerPage + 1;
    }

    /**
     * Get the last item number on the current page (1-indexed).
     */
    public function getLastItemOnPage(): int
    {
        return min($this->currentPage * $this->itemsPerPage, $this->totalItems);
    }

    /**
     * Check if the current page is the first page.
     */
    public function isFirstPage(): bool
    {
        return 1 === $this->currentPage;
    }

    /**
     * Check if the current page is the last page.
     */
    public function isLastPage(): bool
    {
        return $this->currentPage >= $this->totalPages;
    }

    /**
     * Get an array of page numbers for pagination display.
     *
     * @param int $range Number of pages to show before and after current page
     *
     * @return int[] Array of page numbers
     */
    public function getPageRange(int $range = 4): array
    {
        $start = max(1, $this->currentPage - $range);
        $end = min($this->totalPages, $this->currentPage + $range);

        return range($start, $end);
    }

    /**
     * @return Traversable<int, T>
     */
    public function getIterator(): Traversable
    {
        return $this->paginator->getIterator();
    }

    /**
     * Get the number of items on the current page.
     */
    public function count(): int
    {
        return $this->totalItems;
    }
}
