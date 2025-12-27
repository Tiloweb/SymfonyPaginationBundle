# TilowebPaginationBundle

[![CI](https://github.com/Tiloweb/SymfonyPaginationBundle/actions/workflows/ci.yml/badge.svg)](https://github.com/Tiloweb/SymfonyPaginationBundle/actions/workflows/ci.yml)
[![Latest Stable Version](https://poser.pugx.org/tiloweb/pagination-bundle/v/stable)](https://packagist.org/packages/tiloweb/pagination-bundle)
[![Total Downloads](https://poser.pugx.org/tiloweb/pagination-bundle/downloads)](https://packagist.org/packages/tiloweb/pagination-bundle)
[![License](https://poser.pugx.org/tiloweb/pagination-bundle/license)](https://packagist.org/packages/tiloweb/pagination-bundle)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-8892BF.svg)](https://php.net/)

A simple and elegant pagination bundle for Symfony using Doctrine ORM Paginator. This bundle provides an easy-to-use service for paginating database queries and a Twig function for rendering pagination controls.

## ✨ Features

- 🚀 **Simple API** - Paginate any Doctrine QueryBuilder with a single method call
- 🎨 **Customizable templates** - Use the default Bootstrap-compatible template or create your own
- ♿ **Accessible** - Default template includes ARIA attributes for screen readers
- 🔧 **Configurable** - Adjust page range, items per page, and template path
- 📦 **Lightweight** - Minimal dependencies, leverages Doctrine's built-in Paginator
- ✅ **Modern PHP** - Strict types, readonly classes, PHP 8.2+ features

## 📋 Requirements

- PHP 8.2 or higher
- Symfony 6.4, 7.x, or 8.x
- Doctrine ORM 2.14+ or 3.x
- Twig 3.x

## 📥 Installation

Install the bundle using Composer:

```bash
composer require tiloweb/pagination-bundle
```

If you're using Symfony Flex, the bundle will be automatically enabled. Otherwise, add it to your `config/bundles.php`:

```php
return [
    // ...
    Tiloweb\PaginationBundle\TilowebPaginationBundle::class => ['all' => true],
];
```

## ⚙️ Configuration

The bundle works out of the box with sensible defaults. You can customize it by creating a configuration file:

```yaml
# config/packages/tiloweb_pagination.yaml
tiloweb_pagination:
    template: '@TilowebPagination/pagination.html.twig'  # Default template
    page_range: 4                                         # Pages shown before/after current
    default_items_per_page: 10                           # Default items per page
```

## 🚀 Usage

### Basic Usage with the Paginator Service

```php
<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Tiloweb\PaginationBundle\Service\Paginator;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(
        Request $request,
        UserRepository $userRepository,
        Paginator $paginator,
    ): Response {
        $queryBuilder = $userRepository->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC');

        $pagination = $paginator->paginate(
            queryBuilder: $queryBuilder,
            page: $request->query->getInt('page', 1),
            itemsPerPage: 20,
        );

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
```

### In Your Twig Template

```twig
{# templates/user/index.html.twig #}
<h1>Users</h1>

<p>
    Showing {{ pagination.firstItemOnPage }} to {{ pagination.lastItemOnPage }} 
    of {{ pagination.totalItems }} users
</p>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        {% for user in pagination %}
            <tr>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.createdAt|date('Y-m-d') }}</td>
            </tr>
        {% else %}
            <tr>
                <td colspan="3" class="text-center">
                    <em>No users found</em>
                </td>
            </tr>
        {% endfor %}
    </tbody>
</table>

{# Render pagination controls #}
{{ pagination(pagination) }}
```

### Advanced Repository Pattern

Create a reusable pagination method in your repository:

```php
<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tiloweb\PaginationBundle\Service\PaginationResult;
use Tiloweb\PaginationBundle\Service\Paginator;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly Paginator $paginator,
    ) {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return PaginationResult<Article>
     */
    public function findPublishedPaginated(int $page = 1, int $limit = 10): PaginationResult
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.published = :published')
            ->setParameter('published', true)
            ->orderBy('a.publishedAt', 'DESC');

        return $this->paginator->paginate($qb, $page, $limit);
    }
}
```

### Custom Pagination Template

Create your own template for complete control over the pagination markup:

```twig
{# templates/pagination/custom.html.twig #}
{% if pages > 1 %}
<div class="my-pagination">
    {% if has_previous %}
        <a href="{{ path(app.request.attributes.get('_route'), 
            app.request.query.all|merge({(page_param): previous_page})) }}">
            ← Previous
        </a>
    {% endif %}

    <span>Page {{ page }} of {{ pages }}</span>

    {% if has_next %}
        <a href="{{ path(app.request.attributes.get('_route'), 
            app.request.query.all|merge({(page_param): next_page})) }}">
            Next →
        </a>
    {% endif %}
</div>
{% endif %}
```

Use it in your template:

```twig
{{ pagination(pagination, 'page', {template: 'pagination/custom.html.twig'}) }}
```

Or set it globally in your configuration:

```yaml
tiloweb_pagination:
    template: 'pagination/custom.html.twig'
```

## 📖 PaginationResult API

The `PaginationResult` object provides useful methods for pagination:

| Method | Description |
|--------|-------------|
| `getCurrentPage()` | Returns the current page number |
| `getTotalPages()` | Returns the total number of pages |
| `getTotalItems()` | Returns the total count of items |
| `getItemsPerPage()` | Returns items per page |
| `hasPreviousPage()` | Returns `true` if there's a previous page |
| `hasNextPage()` | Returns `true` if there's a next page |
| `getPreviousPage()` | Returns previous page number or `null` |
| `getNextPage()` | Returns next page number or `null` |
| `getFirstItemOnPage()` | Returns first item index on current page |
| `getLastItemOnPage()` | Returns last item index on current page |
| `isFirstPage()` | Returns `true` if on first page |
| `isLastPage()` | Returns `true` if on last page |
| `getPageRange(int $range)` | Returns array of page numbers for display |

## 🔄 Migration from v2.x

If you're upgrading from version 2.x, please note the following changes:

1. **Namespace change**: Classes moved from root to `src/` directory
2. **PHP requirement**: Minimum PHP version is now 8.2
3. **Symfony requirement**: Minimum Symfony version is now 6.4
4. **New service**: Use the `Paginator` service instead of static methods
5. **Return type**: `paginate()` now returns `PaginationResult` instead of Doctrine's `Paginator`

## 🤝 Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## 📄 License

This bundle is released under the [MIT License](LICENSE).

## 👤 Author

**Thibault HENRY**
- Website: [tiloweb.com](https://tiloweb.com)
- Email: thibault@henry.pro
- GitHub: [@Tiloweb](https://github.com/Tiloweb)
