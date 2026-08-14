<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Tests\Controller;

use Ad2210\MonitoringBundle\Controller\HealthController;
use Ad2210\MonitoringBundle\Health\ApplicationHealthChecker;
use Ad2210\MonitoringBundle\Health\ApplicationReadinessCheck;
use Ad2210\MonitoringBundle\Health\ReadinessChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Verifies the liveness HTTP contract.
 */
final class HealthControllerTest extends TestCase
{
    /**
     * Ensures a configured token is required.
     */
    public function testInvalidTokenIsRejected(): void
    {
        $controller = new HealthController($this->healthChecker(), true, 'secret');

        $response = $controller->live(Request::create('/_monitoring/health/live'));

        self::assertSame(401, $response->getStatusCode());
        self::assertSame(['status' => 'unauthorized'], json_decode((string) $response->getContent(), true));
    }

    /**
     * Ensures a valid token returns the normalized health report.
     */
    public function testValidTokenReturnsHealthReport(): void
    {
        $controller = new HealthController($this->healthChecker(), true, 'secret');
        $request = Request::create('/_monitoring/health/live', server: ['HTTP_X_MONITORING_TOKEN' => 'secret']);

        $response = $controller->live($request);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('ok', json_decode((string) $response->getContent(), true)['status']);
    }

    /**
     * Ensures an empty token keeps local liveness checks unauthenticated.
     */
    public function testEmptyTokenAllowsTheRequest(): void
    {
        $controller = new HealthController($this->healthChecker(), true, '');

        self::assertSame(200, $controller->live(Request::create('/_monitoring/health/live'))->getStatusCode());
    }

    /**
     * Ensures readiness uses the same authentication contract as liveness.
     */
    public function testReadyReturnsTheReadinessReport(): void
    {
        $controller = new HealthController($this->healthChecker(), true, 'secret');
        $request = Request::create('/_monitoring/health/ready', server: ['HTTP_X_MONITORING_TOKEN' => 'secret']);

        $response = $controller->ready($request);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('ok', json_decode((string) $response->getContent(), true)['status']);
    }

    /**
     * Ensures disabled health endpoints are not exposed.
     */
    public function testDisabledHealthReturnsNotFound(): void
    {
        $controller = new HealthController($this->healthChecker(), false, '');

        self::assertSame(404, $controller->live(Request::create('/_monitoring/health/live'))->getStatusCode());
    }

    /**
     * Builds the default health checker used by controller tests.
     */
    private function healthChecker(): ApplicationHealthChecker
    {
        return new ApplicationHealthChecker('demo-app', 'test', new ReadinessChecker([new ApplicationReadinessCheck()]));
    }
}
