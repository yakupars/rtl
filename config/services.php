<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Rtl\Application;
use Symfony\Component\Console\Command\Command;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->instanceof(Command::class)
        ->tag("command");

    $services->load("Rtl\\", __DIR__ . "/../src/*")
        ->public()
        ->exclude(__DIR__ . "/../src/{Application.php}");

    $services
        ->set(Application::class)
        ->args([tagged_iterator("command")])
        ->public();
};
