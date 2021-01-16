<?php

namespace App\Command;

use App\Exception\ItemExistsException;
use App\Model\Country;
use InvalidArgumentException;
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
        $name = $this->extractName();
        $canonicalName = $this->extractCanonicalName();

        $this->assertIfExist($name, $canonicalName);

        $country = Country::create([
            'name'  => ucfirst($name),
            'canonicalName' => ucfirst($canonicalName)
        ]);

        $this->io->success("Country has been successfully created!");

        return 0;
    }

    private function extractName(): string
    {
        $name = $this->input->getArgument('name');

        if ($name) {
            $this->validateInput($name, 'name');

            return $name;
        }

        return $this->askName();
    }

    private function extractCanonicalName(): string
    {
        $canonicalName = $this->input->getArgument('canonicalName');

        if ($canonicalName) {
            $this->validateInput($canonicalName, 'canonical name');

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
            $this->askName();
        }

        $this->validateInput($name, 'name');

        return $name;
    }

    private function askCanonicalName(): string
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please type canonical name: ');

        $canonicalName = $helper->ask($this->input, $this->output, $question);

        if (!$canonicalName) {
            $this->askCanonicalName();
        }

        $this->validateInput($canonicalName, 'canonical name');

        return $canonicalName;
    }

    private function validateInput($input, string $inputName): bool
    {
        if (
            !preg_match("/^[A-Z][a-z]+$/", ucfirst($input))
        ) {
            throw new InvalidArgumentException("The {$inputName} must not include any numbers or special chars!");
        }

        if (
            mb_strlen($input) > 100
        ) {
            throw new InvalidArgumentException("The length of {$inputName} can not be more than 100!");
        }

        return true;
    }

    private function assertIfExist($name, $canonicalName)
    {
        $country = Country::where('name', $name)
            ->orWhere('canonicalName', $canonicalName)
            ->exists();
        
        if (
            $country
        ) {
            throw new ItemExistsException("The country with this name or canonical name already exist!");
        }

        return false;
    }
}
