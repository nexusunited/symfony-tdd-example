<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

class ImportReader
{
    private string $importFolder;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $importFolder = $parameterBag->get('kernel.project_dir') . '/import';
        if (!is_dir($importFolder)) {
            throw new \RuntimeException('Folder "'.$importFolder .'" not exist');
        }

        $this->importFolder = $importFolder;
    }

    public function read(): Finder
    {
        $finder = new Finder();
        $finder->depth('== 0')
            ->in($this->importFolder)
            ->name('*.json')
            ->sortByName();

        return $finder;
    }
}
