# Contributing to TilowebPaginationBundle

First off, thank you for considering contributing to TilowebPaginationBundle! 🎉

## Code of Conduct

This project and everyone participating in it is governed by our commitment to maintaining a welcoming and inclusive environment. Please be respectful and constructive in all interactions.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues to avoid duplicates.

When creating a bug report, include:

- **Clear title** describing the issue
- **Steps to reproduce** the behavior
- **Expected behavior** vs **actual behavior**
- **Environment details**: PHP version, Symfony version, bundle version
- **Code samples** if applicable

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Clear title** describing the enhancement
- **Detailed description** of the proposed functionality
- **Use case** explaining why this would be useful
- **Possible implementation** if you have ideas

### Pull Requests

1. **Fork** the repository
2. **Create a branch** from `main` for your changes:
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Make your changes** following our coding standards
4. **Write or update tests** as needed
5. **Run the test suite** to ensure everything passes:
   ```bash
   composer test
   ```
6. **Run static analysis**:
   ```bash
   composer phpstan
   ```
7. **Fix code style**:
   ```bash
   composer cs-fix
   ```
8. **Commit your changes** with a clear message:
   ```bash
   git commit -m 'Add amazing feature'
   ```
9. **Push** to your fork and **submit a Pull Request**

## Development Setup

### Prerequisites

- PHP 8.2 or higher
- Composer

### Installation

```bash
git clone https://github.com/YOUR_USERNAME/SymfonyPaginationBundle.git
cd SymfonyPaginationBundle
composer install
```

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage
```

### Code Quality Tools

```bash
# Static analysis with PHPStan
composer phpstan

# Fix code style with PHP CS Fixer
composer cs-fix

# Check code style without fixing
composer cs-check
```

## Coding Standards

This project follows:

- **PSR-12** coding style
- **Symfony coding standards**
- **Strict types** declaration in all PHP files
- **Type hints** for all parameters and return types

### PHP Standards

```php
<?php

declare(strict_types=1);

namespace Tiloweb\PaginationBundle;

final readonly class Example
{
    public function __construct(
        private string $property,
    ) {}

    public function doSomething(): string
    {
        return $this->property;
    }
}
```

## Release Process

Releases are managed by maintainers. The project follows [Semantic Versioning](https://semver.org/).

## Questions?

Feel free to open an issue with the `question` label if you have any questions!

---

Thank you for contributing! 💙
