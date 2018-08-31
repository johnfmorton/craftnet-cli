<?php

namespace CraftnetCli\Command\CraftnetCli;

use CraftnetCli\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateLicenseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('licenses:create')
            ->setDescription('Create a license')
            ->addOption('edition', null, InputOption::VALUE_OPTIONAL, 'Which edition (defaults to standard)', 'standard')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Licencee’s email address')
            ->addOption('pluginHandle', 'handle', InputOption::VALUE_REQUIRED, 'Plugin handle')
            ->addOption('notes', null, InputOption::VALUE_OPTIONAL, 'Customer notes')
            ->setHelp('Creates a new license for a given plugin and email.');

        // extra command line arguments and options go here.
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->validate()) {
            $output->writeln([
                "You need to provide all required arguments",
            ]);

            return false;
        }

        $client = new Client();
        $data   = [
            'edition' => $input->getOption('edition'),
            'plugin'  => $input->getOption('pluginHandle'),
            'email'   => $input->getOption('email'),
        ];

        foreach (['notes'] as $option) {
            if ($optionValue = $input->getOption($option)) {
                $data[ $option ] = $optionValue;
            }
        }

        $response = $client->createLicense([
            'json' => $data,
        ]);

        $output->writeln(PrettyJson($response->getBody()));
    }
}