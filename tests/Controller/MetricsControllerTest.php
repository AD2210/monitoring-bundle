<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Tests\Controller;

use Ad2210\MonitoringBundle\Controller\MetricsController;
use Ad2210\MonitoringBundle\Metrics\ApplicationMetricsProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Verifies the Prometheus endpoint contract.
 */
final class MetricsControllerTest extends TestCase
{
    /**
     * Ensures metrics require the configured token.
     */
    public function testInvalidTokenIsRejected(): void
    {
        $controller = new MetricsController(new ApplicationMetricsProvider('demo', 'test'), 'secret');

        self::assertSame(401, $controller(Request::create('/_monitoring/metrics'))->getStatusCode());
    }

    /**
     * Ensures valid requests return Prometheus content.
     */
    public function testValidTokenReturnsMetrics(): void
    {
        $controller = new MetricsController(new ApplicationMetricsProvider('demo', 'test'), 'secret');
        $request = Request::create('/_monitoring/metrics', server: ['HTTP_X_MONITORING_TOKEN' => 'secret']);

        $response = $controller($request);

        self::assertSame(200, $response->getStatusCode());
        self::assertStringContainsString('ad2210_monitoring_up', (string) $response->getContent());
        self::assertStringContainsString('text/plain; version=0.0.4', (string) $response->headers->get('Content-Type'));
    }
}
