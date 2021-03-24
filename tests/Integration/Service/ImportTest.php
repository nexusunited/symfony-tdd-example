<?php declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Repository\CarMakeRepository;
use App\Repository\CarRepository;
use App\Service\CarImport;
use App\Service\Import;
use App\Service\ImportReader;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ImportTest extends KernelTestCase
{
    private string $testJson;

    private Import $import;

    private CarRepository $carRepository;

    private CarMakeRepository $carMakeRepository;

    private ObjectManager $entityManager;

    protected function setUp(): void
    {
        $this->testJson = __DIR__ . '/import/car.json';
        copy(__DIR__ . '/car.json', $this->testJson);

        $kernel = self::bootKernel();

        $parameterBag = new ParameterBag([
            'kernel.project_dir' => __DIR__
        ]);

        /** @var CarImport $carImport */
        $carImport = self::$container->get(CarImport::class);
        $this->import = new Import(
            $carImport,
            new ImportReader($parameterBag)
        );

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var CarMakeRepository $carMakeRepository */
        $carMakeRepository = self::$container->get(CarMakeRepository::class);
        $this->carMakeRepository = $carMakeRepository;

        /** @var CarRepository $carRepository */
        $carRepository = self::$container->get(CarRepository::class);
        $this->carRepository = $carRepository;

    }

    protected function tearDown(): void
    {
        parent::tearDown();

        shell_exec('rm -f ' . __DIR__ . '/import/*.json');

        $connection = $this->entityManager->getConnection();

        $connection->executeUpdate('DELETE FROM car');
        $connection->executeUpdate('ALTER TABLE car AUTO_INCREMENT=0');
        $connection->executeUpdate('DELETE FROM car_make');
        $connection->executeUpdate('ALTER TABLE car_make AUTO_INCREMENT=0');
    }

    public function testImport()
    {
        $this->import->start();

        self::assertFileNotExists($this->testJson);

        $makeList = $this->carMakeRepository->findBy([], ['name' => 'ASC']);

        self::assertCount(5, $makeList);
        self::assertSame('Audi', $makeList[0]->getName());
        self::assertSame('BMW', $makeList[1]->getName());

        $carList = $this->carRepository->findBy([], ['model' => 'ASC']);

        self::assertCount(10, $carList);

        self::assertSame("Mercedes-Benz", $carList[0]->getMake()->getName());
        self::assertSame("500SL", $carList[0]->getModel());
        self::assertSame("consequat dui nec nisi volutpat", $carList[0]->getDescription());
    }
}
