<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Controller;

use Ad2210\MonitoringBundle\Metrics\MetricsProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Exposes metrics for Prometheus scraping.
 */
#[AsController]
final readonly class MetricsController
{
    public function __construct(
        private MetricsProviderInterface $metricsProvider,
        private string $metricsToken,
    ) {
    }

    /**
     * Returns the current Prometheus exposition payload.
     */
    #[Route('/_monitoring/metrics', name: 'ad2210_monitoring_metrics', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        if ('' !== $this->metricsToken && !hash_equals($this->metricsToken, (string) $request->headers->get('X-Monitoring-Token'))) {
            return new Response("Unauthorized\n", Response::HTTP_UNAUTHORIZED, ['Content-Type' => 'text/plain; charset=utf-8']);
        }

        return new Response($this->metricsProvider->render(), Response::HTTP_OK, [
            'Content-Type' => 'text/plain; version=0.0.4; charset=utf-8',
        ]);
    }
}
