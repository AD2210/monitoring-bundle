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

The test environment must provide a PHP coverage driver such as Xdebug or
PCOV. The test command intentionally fails when coverage cannot be measured.

## Application integration

Register the bundle in `config/bundles.php`, then import its routes from the
application routing configuration:

```php
$routes->import('@Ad2210MonitoringBundle/Resources/config/routes.php');
```

The first endpoint is `/_monitoring/health/live`. It accepts the optional
`X-Monitoring-Token` header when `ad2210_monitoring.health.token` is configured.
