<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Tests\Health;

use Ad2210\MonitoringBundle\Health\ReadinessCheckInterface;
use Ad2210\MonitoringBundle\Health\ReadinessCheckResult;
use Ad2210\MonitoringBundle\Health\ReadinessChecker;
use PHPUnit\Framework\TestCase;

/**
 * Verifies readiness check aggregation and failure isolation.
 */
final class ReadinessCheckerTest extends TestCase
{
    /**
     * Ensures a failed dependency produces a 503-ready status.
     */
    public function testFailedCheckMakesTheApplicationNotReady(): void
    {
        $checker = new ReadinessChecker([
            $this->check('database', new ReadinessCheckResult('error')),
        ]);

        self::assertSame([
            'status' => 'error',
            'checks' => ['database' => 'error'],
        ], $checker->check());
    }

    /**
     * Ensures exceptions are converted to a safe public failure.
     */
    public function testBrokenCheckDoesNotExposeItsException(): void
    {
        $check = new class () implements ReadinessCheckInterface {
            public function getName(): string
            {
                return 'broken';
            }

            public function check(): ReadinessCheckResult
            {
                throw new \RuntimeException('private failure');
            }
        };

        self::assertSame('error', (new ReadinessChecker([$check]))->check()['status']);
    }

    /**
     * Creates a deterministic check double.
     */
    private function check(string $name, ReadinessCheckResult $result): ReadinessCheckInterface
    {
        return new class ($name, $result) implements ReadinessCheckInterface {
            public function __construct(private string $name, private ReadinessCheckResult $result)
            {
            }

            public function getName(): string
            {
                return $this->name;
            }

            public function check(): ReadinessCheckResult
            {
                return $this->result;
            }
        };
    }
}
