# Changelog

## 0.2.2

- Fixed explicit registration of the heartbeat console command.

## 0.2.1

- Fixed registration of the `monitoring:heartbeat` console command.

## 0.2.0

- Added `monitoring:heartbeat` for worker and scheduler integrations.
- Added application version to the heartbeat payload.
- Added extensible readiness checks with safe failure handling.
- Added HTTP 503 responses for failed readiness checks.

## 0.1.0

- Added Symfony bundle bootstrap and typed configuration.
- Added authenticated liveness and readiness endpoints.
- Added extensible readiness checks through a service tag.
- Added authenticated Prometheus metrics endpoint.
- Added mandatory PHPUnit, PHPStan and PHP-CS-Fixer quality gate.
