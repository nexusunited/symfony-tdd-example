<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Import;

class CarCommand extends Command
{
    protected static $defaultName = 'car:import';

    private Import $import;

    /**
     * @param \App\Service\Import $import
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Impor car and make');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->import->start();
        return Command::SUCCESS;
    }
}
