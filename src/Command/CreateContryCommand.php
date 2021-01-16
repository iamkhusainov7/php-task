<?php

namespace App\Command;

use App\Controller\CountryController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateCountryCommand extends Command
{
    protected static $defaultName = 'app:create_country';

    private InputInterface $input;
    private OutputInterface $output;
    private SymfonyStyle $io;

    protected function configure()
    {
        $this->setDescription('Creating country');
        $this->addArgument('name', InputArgument::OPTIONAL, 'Please type country name');
        $this->addArgument('canonicalName', InputArgument::OPTIONAL, 'Please type country canonical name');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = strtolower($this->extractName());
        $canonicalName = strtolower($this->extractCanonicalName());

        $controller = (new CountryController())->create(
            trim($name),
            trim($canonicalName)
        );

        $this->io->success("Country has been successfully created!");

        return 0;
    }

    private function extractName(): string
    {
        $name = $this->input->getArgument('name');

        if ($name) {
            return $name;
        }

        return $this->askName();
    }

    private function extractCanonicalName(): string
    {
        $canonicalName = $this->input->getArgument('canonicalName');

        if ($canonicalName) {
            return $canonicalName;
        }

        return $this->askCanonicalName();
    }

    private function askName(): string
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please type country name: ');

        $name = $helper->ask($this->input, $this->output, $question);

        if (!$name) {
            return $this->askName();
        }

        return $name;
    }

    private function askCanonicalName(): string
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please type canonical name: ');

        $canonicalName = $helper->ask($this->input, $this->output, $question);

        if (!$canonicalName) {
            return $this->askCanonicalName();
        }

        return $canonicalName;
    }
}
