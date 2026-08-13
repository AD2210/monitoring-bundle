# ad2210/monitoring-bundle

Symfony bundle providing the monitoring integration for applications managed by
the `ad2210/monitoring` project.

The package is currently under development. Its public HTTP contract and
configuration will be documented before the first release.

## Development quality gate

Every release must pass:

```bash
composer qa
```

The gate includes PHPUnit with at least 90% coverage, PHPStan, and PHP-CS-Fixer
using PSR-12-compatible rules.
