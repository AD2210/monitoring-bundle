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

Development follows Git Flow: work is done on `feature/*` branches, merged into
`develop`, and released through `release/*` branches. A release is not valid
until the CI quality gate succeeds.

## Application integration

Register the bundle in `config/bundles.php`, then import its routes from the
application routing configuration:

```php
$routes->import('@Ad2210MonitoringBundle/Resources/config/routes.php');
```

The health endpoints are:

- `/_monitoring/health/live` for process liveness;
- `/_monitoring/health/ready` for application and dependency readiness;
- `/_monitoring/metrics` for Prometheus exposition.

The health endpoint accepts the `X-Monitoring-Token` header when
`ad2210_monitoring.health.token` is configured. Metrics use the independent
`ad2210_monitoring.metrics.token` option.

Readiness checks implement `ReadinessCheckInterface` and are registered with
the `ad2210_monitoring.readiness_check` service tag. A failed check returns
HTTP 503 while internal failure details remain in application logs.
