<?php declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\ImportReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ImportReaderTest extends TestCase
{
    public function testExcetionWhenFolderNotExist()
    {
        $importFolder = '/no/exist/folder';
        $parameterBag = new ParameterBag([
            'kernel.project_dir' => $importFolder
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Folder "'.$importFolder .'/import" not exist');

        new ImportReader($parameterBag);
    }

}
