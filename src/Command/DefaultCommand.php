<?php

namespace Rtl\Command;

use Rtl\Data\Month;
use Rtl\Dto\Date;
use Rtl\Service\DateService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DefaultCommand extends Command
{
    protected static $defaultName = "populate";

    /**
     * @param DateService $dateService
     */
    public function __construct(private DateService $dateService)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription("Use to populate payment date csv files.");

        $this->addArgument("filepath", InputArgument::OPTIONAL, "File path for the csv output.");

        $this->addOption("yes", "y", InputOption::VALUE_NONE, "Yes to all. (overwrites files and directories.)");
        $this->addOption(
            "date",
            "d",
            InputOption::VALUE_REQUIRED,
            "Start date. Default: " . $this->dateService->getTodayAsDate()
        );
        $this->addOption("std", "s", InputOption::VALUE_NONE, "Output to stdout instead of file.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filepath = $input->getArgument("filepath");
        $isStd = $input->getOption("std");

        if (!$filepath && !$isStd) {
            $output->writeln("You have to provide filepath or --std option. Exiting..");

            return 1;
        }

        if ($filepath) {
            $this->processFile($filepath, $input, $output);
        }

        $dates = $this->populateData($input, $output);

        $fp = $isStd ? fopen("php://stdout", "w") : fopen($filepath, "w");

        foreach ($dates as $line) {
            fputcsv($fp, $line);
        }

        return 0;
    }

    /**
     * @param string $filepath
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function processFile(string $filepath, InputInterface $input, OutputInterface $output): void
    {
        if (file_exists($filepath) && !$input->getOption("yes")) {
            $question = new ConfirmationQuestion(
                "$filepath already exists. Do you want to overwrite its content [y/N]?",
                false,
                "/^(y|Y)/i"
            );

            $answer = $this->getHelper("question")->ask($input, $output, $question);

            if (!$answer) {
                $output->writeln("File is not changed. Bye!");

                return;
            }
        }

        $dir = dirname($filepath);

        if (!file_exists($dir) && !$input->getOption("yes")) {
            $question = new ConfirmationQuestion(
                "$dir is not exists. Do you want to create it [y/N]?",
                false,
                "/^(y|Y)/i"
            );

            $answer = $this->getHelper("question")->ask($input, $output, $question);

            if (!$answer) {
                $output->writeln("Directory is not created. Bye!");

                return;
            }

            mkdir($dir, 0777, true);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return array
     */
    private function populateData(InputInterface $input, OutputInterface $output): array
    {
        $date = $this->dateService->getTodayAsDate();
        if ($dateOption = $input->getOption("date")) {
            $pattern = "/^(0[1-9]|1\d|2[0-8]|29(?=\.\d\d\.(?!1[01345789]00|2[1235679]00)\d\d(?:[02468][048]|[13579][26]))|30(?!\.02)|31(?=\.0[13578]|\.1[02]))\.(0[1-9]|1[0-2])\.([12]\d{3})$/";

            if (preg_match($pattern, $dateOption) !== 1) {
                $output->writeln("You did not provide a valid date.");

                exit;
            }

            $date = Date::fromdmY($dateOption);
        }

        $dates = [];
        for ($m = $date->getMonth(); $m <= 12; $m++) {
            $line = new Date(1, $m, $date->getYear());

            $dates[] = [
                Month::MonthName($m),
                (string)$this->dateService->getLastWorkDateFromDate($line),
                (string)$this->dateService->getBonusDateFromDate($line),
            ];
        }

        array_unshift($dates, ["Month", "Salary", "Bonus"]);

        return $dates;
    }
}
