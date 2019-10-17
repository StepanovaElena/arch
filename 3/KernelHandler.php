<?php

declare(strict_types=1);

namespace framework\Command;

use Command\Contract\CommandInterface;

class KernelHandler implements CommandInterface
{
    /**
     * @var RegistreConfigsCommand
     */
    private $commandConfigs;

    /**
     * @var RegisterRoutesCommand
     */
    private $commandRouts;

    /**
     * @param RegistreConfigsCommand $commandConfigs
     * @param RegisterRoutesCommand $commandRouts
     */
    public function __construct(RegistreConfigsCommand  $commandConfigs, RegisterRoutesCommand $commandRouts)
    {
        $this->commandConfigs = $commandConfigs;
        $this->commandRouts = $commandRouts;
    }

    /**
     * Выполнение команды.
     */
    public function execute(): void
    {
        $this->commandConfigs->registerConfigs();
        $this->commandRouts->registerRoutes();
    }
}