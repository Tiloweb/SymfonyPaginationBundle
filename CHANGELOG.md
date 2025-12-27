# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0] - 2024-XX-XX

### Added
- Full support for PHP 8.2, 8.3, 8.4, and 8.5
- Full support for Symfony 6.4, 7.x, and 8.x
- New `Paginator` service for easy pagination
- New `PaginationResult` class with comprehensive pagination metadata
- Configurable page range for pagination display
- Configurable default items per page
- Improved accessibility with ARIA attributes in default template
- PHP configuration files (replacing YAML)
- PHPStan static analysis
- PHP CS Fixer for code style
- GitHub Actions CI/CD pipeline

### Changed
- **BREAKING**: Moved all classes to `src/` directory (PSR-4 autoloading)
- **BREAKING**: Minimum PHP version is now 8.2
- **BREAKING**: Minimum Symfony version is now 6.4
- Updated to use Symfony's `AbstractBundle` instead of `Bundle`
- Modernized codebase with strict types and readonly properties
- Improved Twig template with Bootstrap 5 compatibility
- Enhanced documentation with modern examples

### Removed
- **BREAKING**: Removed PSR-0 autoloading
- **BREAKING**: Removed deprecated `target-dir` in composer.json
- Removed YAML configuration files (replaced with PHP)

### Fixed
- Various type hints and return type declarations

## [2.0.0] - Previous version

Legacy version supporting Symfony 5.x and PHP 8.0+.

[Unreleased]: https://github.com/Tiloweb/SymfonyPaginationBundle/compare/v3.0.0...HEAD
[3.0.0]: https://github.com/Tiloweb/SymfonyPaginationBundle/compare/v2.0.0...v3.0.0
[2.0.0]: https://github.com/Tiloweb/SymfonyPaginationBundle/releases/tag/v2.0.0
