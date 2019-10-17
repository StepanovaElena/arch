<?php


namespace framework\Command;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class RegistreConfigsCommand extends KernelCommand
{
    /**
     * @return void
     */
    public function registerConfigs(): void
    {
        try {
            $fileLocator = new FileLocator(__DIR__ . DIRECTORY_SEPARATOR . 'config');
            $loader = new PhpFileLoader($this->containerBuilder, $fileLocator);
            $loader->load('parameters.php');
        } catch (\Throwable $e) {
            die('Cannot read the config file. File: ' . __FILE__ . '. Line: ' . __LINE__);
        }
    }
}