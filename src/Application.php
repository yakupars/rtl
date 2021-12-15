<?php

namespace Rtl;

class Application extends \Symfony\Component\Console\Application
{
    public function __construct(
        iterable $commands,
        string $name = "Payroll Date File Population Application",
        string $version = "0.0.1"
    ) {
        parent::__construct($name, $version);

        foreach ($commands as $command) {
            $this->add($command);
        }
    }
}
