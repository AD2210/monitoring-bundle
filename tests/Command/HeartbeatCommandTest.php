<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Tests\Command;

use Ad2210\MonitoringBundle\Command\HeartbeatCommand;
use Ad2210\MonitoringBundle\Heartbeat\HeartbeatProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Verifies CLI heartbeat output.
 */
final class HeartbeatCommandTest extends TestCase
{
    /**
     * Ensures the command emits valid JSON and succeeds.
     */
    public function testCommandEmitsHeartbeatJson(): void
    {
        $command = new HeartbeatCommand(new HeartbeatProvider('demo', 'test', 'dev'));
        $tester = new CommandTester($command);

        self::assertSame(0, $tester->execute([]));
        self::assertSame('demo', json_decode($tester->getDisplay(), true)['application']);
    }
}
