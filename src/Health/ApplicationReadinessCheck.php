<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Health;

/**
 * Confirms that the host application has booted successfully.
 */
final class ApplicationReadinessCheck implements ReadinessCheckInterface
{
    /**
     * Returns a successful result because the kernel is already handling a request.
     */
    public function getName(): string
    {
        return 'application';
    }

    /**
     * Checks the application process itself.
     */
    public function check(): ReadinessCheckResult
    {
        return new ReadinessCheckResult('ok');
    }
}
