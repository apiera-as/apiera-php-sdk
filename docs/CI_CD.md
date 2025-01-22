# CI/CD Documentation

This document describes the Continuous Integration and Continuous Deployment (CI/CD) setup for the Apiera PHP SDK.

## Table of Contents
- [Overview](#overview)
- [Workflows](#workflows)
    - [Continuous Integration](#continuous-integration)
    - [Release Process](#release-process)
- [Code Quality Tools](#code-quality-tools)
- [Testing](#testing)
- [GitHub Configuration](#github-configuration)
- [Development Process](#development-process)

## Overview

Our CI/CD pipeline is built using GitHub Actions and consists of two main workflows:
- Continuous Integration (`ci.yml`) - For code quality and testing
- Release Management (`release.yml`) - For managing releases and deployments

## Workflows

### Continuous Integration

Located in `.github/workflows/ci.yml`, this workflow runs on:
- Every push to the `main` branch
- All pull requests targeting `main`

#### Jobs:

1. **Validate**
    - Validates composer.json and composer.lock files
    - Ensures package dependencies are properly defined

2. **Code Quality**
    - Runs on PHP 8.3
    - Executes:
        - PHPStan static analysis
        - PHP_CodeSniffer style checks
    - Caches Composer dependencies for faster runs

3. **Tests**
    - Matrix testing with:
        - PHP versions: 8.3
        - Dependency versions: lowest, highest
    - Runs PHPUnit test suite
    - Generates and uploads code coverage reports to Codecov

### Release Process

Located in `.github/workflows/release.yml`, triggered by pushing version tags (e.g., `v1.2.3`).

#### Steps:

1. Creates a production build
2. Generates a release archive excluding development files
3. Creates a GitHub release with:
    - Auto-generated release notes
    - Attached SDK archive
    - Version information update

## Code Quality Tools

### PHPStan

- Configuration: `phpstan.neon`
- Level: 8 (Maximum)
- Custom rules:
    - Required docblock tags
    - Strict type declarations

### PHP_CodeSniffer

- Configuration: `phpcs.xml`
- Standard: PSR-12
- Custom sniffs:
    - Strict types declaration check
    - Code documentation requirements

## Testing

### PHPUnit Configuration

File: `phpunit.xml.dist`

```xml
<testsuites>
    <testsuite name="Unit">
        <directory>tests/Unit</directory>
    </testsuite>
    <testsuite name="Integration">
        <directory>tests/Integration</directory>
    </testsuite>
</testsuites>
```

### Code Coverage

- Tool: Xdebug
- Platform: Codecov
- Requirements: Minimum 80% coverage

## GitHub Configuration

### Branch Protection Rules

For `main` branch:
- Require status checks to pass
- Require branches to be up to date
- Required checks:
    - validate
    - code-quality
    - tests

### Required Secrets

- `CODECOV_TOKEN`: For uploading coverage reports

## Development Process

### Creating a New Release

1. Update version number in relevant files
2. Create and push a new tag:
   ```bash
   git tag -a v1.2.3 -m "Release version 1.2.3"
   git push origin v1.2.3
   ```
3. The release workflow will automatically:
    - Create GitHub release
    - Generate release notes
    - Upload SDK archive

### Development Workflow

1. Create feature branch from `main`
2. Make changes and commit
3. Push branch and create pull request
4. Ensure all CI checks pass
5. Get code review
6. Merge to `main`

### Running Checks Locally

```bash
# Install dependencies
composer install

# Run static analysis
composer phpstan

# Check code style
composer phpcs-check

# Fix code style
composer phpcs-fix

# Run tests
vendor/bin/phpunit
```

## Maintenance

### Updating Dependencies

Regular security updates:
```bash
composer update --with-all-dependencies
```

### Monitoring

- Check Codecov reports for coverage trends
- Review GitHub Actions logs for performance
- Monitor dependency vulnerabilities through GitHub alerts

## Troubleshooting

### Common Issues

1. **Failed CI Checks**
    - Verify local tests pass
    - Check PHPStan and PHPCS logs
    - Ensure proper PHP version

2. **Release Failures**
    - Verify tag format (v*.*.*)
    - Check GitHub permissions
    - Review action logs

### Support

For CI/CD issues:
1. Check workflow run logs
2. Review job steps outputs
3. Verify environment configuration
4. Contact development team lead