<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Health;

/**
 * Defines one dependency check used by the readiness endpoint.
 */
interface ReadinessCheckInterface
{
    /**
     * Returns the stable public name of the check.
     */
    public function getName(): string;

    /**
     * Executes the dependency check.
     */
    public function check(): ReadinessCheckResult;
}
