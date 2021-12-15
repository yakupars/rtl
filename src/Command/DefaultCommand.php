<?php

namespace Rtl\Command;

use Rtl\Service\DummyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommand extends Command
{
    protected static $defaultName = "populate";

    private DummyService $dummyService;

    /**
     * @param DummyService $dummyService
     */
    public function __construct(DummyService $dummyService)
    {
        parent::__construct();

        $this->dummyService = $dummyService;
    }

    protected function configure()
    {
        $this->setDescription("Use to populate payment date csv files.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("{placeholder}");

        $output->writeln($this->dummyService->returnOk());

        return 0;
    }
}
