<?php

declare(strict_types=1);

namespace Ad2210\MonitoringBundle\Command;

use Ad2210\MonitoringBundle\Heartbeat\HeartbeatProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Emits one heartbeat payload for cron, systemd or a worker supervisor.
 */
#[AsCommand(name: 'monitoring:heartbeat', description: 'Emits a monitoring heartbeat payload')]
final class HeartbeatCommand extends Command
{
    public function __construct(private readonly HeartbeatProvider $heartbeatProvider)
    {
        parent::__construct();
    }

    /**
     * Writes a machine-readable heartbeat to standard output.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(json_encode($this->heartbeatProvider->create()->toArray(), JSON_THROW_ON_ERROR));

        return Command::SUCCESS;
    }
}
