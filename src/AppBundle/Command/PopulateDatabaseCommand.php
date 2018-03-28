<?php

namespace AppBundle\Command;

use AppBundle\Service\DataImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateUserCommand
 * @package App\Command
 */
class PopulateDatabaseCommand extends Command
{
    /**
     * @var DataImporter
     */
    private $dataImporter;

    /**
     * PopulateDatabaseCommand constructor.
     * @param DataImporter $dataImporter
     */
    public function __construct(DataImporter $dataImporter)
    {
        $this->dataImporter = $dataImporter;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('timeout:populate-database')
            ->setDescription('Populate database from users.json and venues.json')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dataImporter->importUserData();
        $this->dataImporter->importVenueData();

        return true;
    }
}