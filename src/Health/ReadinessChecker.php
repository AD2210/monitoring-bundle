<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Health;

/**
 * Executes all registered readiness checks.
 */
final readonly class ReadinessChecker
{
    /**
     * @param iterable<ReadinessCheckInterface> $checks
     */
    public function __construct(private iterable $checks)
    {
    }

    /**
     * Runs checks and returns public status values only.
     *
     * A failing or broken dependency must make readiness fail, while its
     * internal exception remains in application logs and is not exposed.
     *
     * @return array{status: string, checks: array<string, string>}
     */
    public function check(): array
    {
        $status = 'ok';
        $results = [];

        foreach ($this->checks as $check) {
            try {
                $result = $check->check();
            } catch (\Throwable) {
                $result = new ReadinessCheckResult('error');
            }

            $results[$check->getName()] = $result->status;
            if (!$result->isSuccessful()) {
                $status = 'error';
            }
        }

        return ['status' => $status, 'checks' => $results];
    }
}
