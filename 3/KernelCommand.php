<?php

declare(strict_types=1);

namespace framework\Command;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollection;

class KernelCommand
{
    /**
     * @var RouteCollection
     */
    protected $routeCollection;
    /**
     * @var ContainerBuilder
     */
    protected $containerBuilder;

    public function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
    }

    /**
     * @return ContainerBuilder
     */
    public function getContainerBuilder(): ContainerBuilder
    {
        return $this->containerBuilder;
    }

    public function getRouteCollection()
    {
        return $this->routeCollection;
    }
}