<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Controller;

use Ad2210\MonitoringBundle\Health\HealthCheckerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Exposes the liveness endpoint used by monitoring systems.
 */
#[AsController]
final readonly class HealthController
{
    public function __construct(
        private HealthCheckerInterface $healthChecker,
        private string $healthToken,
    ) {
    }

    /**
     * Returns the current liveness status of the application.
     */
    #[Route('/_monitoring/health/live', name: 'ad2210_monitoring_health_live', methods: ['GET'])]
    public function live(Request $request): JsonResponse
    {
        if ('' !== $this->healthToken && !hash_equals($this->healthToken, (string) $request->headers->get('X-Monitoring-Token'))) {
            return new JsonResponse(['status' => 'unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse($this->healthChecker->checkLiveness()->toArray());
    }
}
