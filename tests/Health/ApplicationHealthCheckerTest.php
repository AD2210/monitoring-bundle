<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Tests\Health;

use Ad2210\MonitoringBundle\Health\ApplicationHealthChecker;
use PHPUnit\Framework\TestCase;

/**
 * Verifies the liveness report produced by the default checker.
 */
final class ApplicationHealthCheckerTest extends TestCase
{
    /**
     * Ensures the report contains only process-level information.
     */
    public function testLivenessDoesNotDependOnExternalServices(): void
    {
        $checker = new ApplicationHealthChecker('demo-app', 'test');

        self::assertSame([
            'status' => 'ok',
            'application' => 'demo-app',
            'environment' => 'test',
            'checks' => ['application' => 'ok'],
        ], $checker->checkLiveness()->toArray());
    }
}
