<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Tests\Heartbeat;

use Ad2210\MonitoringBundle\Heartbeat\HeartbeatProvider;
use PHPUnit\Framework\TestCase;

/**
 * Verifies the heartbeat payload contract.
 */
final class HeartbeatProviderTest extends TestCase
{
    /**
     * Ensures payloads are deterministic when the observation time is supplied.
     */
    public function testCreatesTransportNeutralPayload(): void
    {
        $provider = new HeartbeatProvider('demo', 'prod', '1.2.3');
        $observedAt = new \DateTimeImmutable('2026-08-14T08:00:00+00:00');

        self::assertSame([
            'application' => 'demo',
            'environment' => 'prod',
            'version' => '1.2.3',
            'observed_at' => '2026-08-14T08:00:00+00:00',
        ], $provider->create($observedAt)->toArray());
    }
}
