<?php

declare(strict_types=1);

namespace Tiloweb\PaginationBundle\Twig;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\HttpFoundation\RequestStack;
use Tiloweb\PaginationBundle\Service\PaginationResult;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension for rendering pagination controls.
 *
 * Provides the `pagination()` function to render pagination links in Twig templates.
 *
 * @author Thibault HENRY <thibault@henry.pro>
 */
final class PaginationExtension extends AbstractExtension
{
    private Environment $twig;
    private RequestStack $requestStack;
    private string $template;
    private int $pageRange;

    public function __construct(
        Environment $twig,
        RequestStack $requestStack,
        string $template,
        int $pageRange,
    ) {
        $this->twig = $twig;
        $this->requestStack = $requestStack;
        $this->template = $template;
        $this->pageRange = $pageRange;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pagination', $this->renderPagination(...), ['is_safe' => ['html']]),
        ];
    }

    /**
     * Render pagination controls.
     *
     * @param PaginationResult<mixed>|DoctrinePaginator<mixed> $pagination The pagination object
     * @param string $pageParam The query parameter name for the page number
     * @param array<string, mixed> $options Additional options for rendering
     */
    public function renderPagination(
        PaginationResult|DoctrinePaginator $pagination,
        string $pageParam = 'page',
        array $options = [],
    ): string {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return '';
        }

        // Handle both PaginationResult and legacy DoctrinePaginator
        if ($pagination instanceof PaginationResult) {
            $page = $pagination->getCurrentPage();
            $pages = $pagination->getTotalPages();
            $pageRangeArray = $pagination->getPageRange($options['page_range'] ?? $this->pageRange);
        } else {
            // Legacy support for DoctrinePaginator
            $page = $request->query->getInt($pageParam, 1);
            $maxResults = $pagination->getQuery()->getMaxResults();
            $pages = (int) ceil($pagination->count() / ($maxResults ?: 1));
            $range = $options['page_range'] ?? $this->pageRange;
            $start = max(1, $page - $range);
            $end = min($pages, $page + $range);
            $pageRangeArray = $pages > 0 ? range($start, $end) : [];
        }

        if ($pages <= 1) {
            return '';
        }

        return $this->twig->render($options['template'] ?? $this->template, [
            'page' => $page,
            'pages' => $pages,
            'page_range' => $pageRangeArray,
            'page_param' => $pageParam,
            'has_previous' => $page > 1,
            'has_next' => $page < $pages,
            'previous_page' => max(1, $page - 1),
            'next_page' => min($pages, $page + 1),
        ]);
    }
}
