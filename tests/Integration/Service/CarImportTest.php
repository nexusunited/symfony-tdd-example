<?php declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Entity\Car;
use App\Entity\CarMake;
use App\Repository\CarMakeRepository;
use App\Repository\CarRepository;
use App\Service\CarImport;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CarImportTest extends KernelTestCase
{
    private CarImport $carImport;

    private CarRepository $carRepository;

    private CarMakeRepository $carMakeRepository;

    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var CarMakeRepository $carMakeRepository */
        $carMakeRepository = self::$container->get(CarMakeRepository::class);
        $this->carMakeRepository = $carMakeRepository;

        /** @var CarRepository $carRepository */
        $carRepository = self::$container->get(CarRepository::class);
        $this->carRepository = $carRepository;

        /** @var CarImport $carImport */
        $carImport = self::$container->get(CarImport::class);
        $this->carImport = $carImport;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeUpdate('DELETE FROM car');
        $connection->executeUpdate('ALTER TABLE car AUTO_INCREMENT=0');
        $connection->executeUpdate('DELETE FROM car_make');
        $connection->executeUpdate('ALTER TABLE car_make AUTO_INCREMENT=0');

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testInsert()
    {
        $this->carImport->import(__DIR__ . '/car.json');

        $makeList = $this->carMakeRepository->findBy([], ['name' => 'ASC']);

        self::assertCount(5, $makeList);
        self::assertSame('Audi', $makeList[0]->getName());
        self::assertSame('BMW', $makeList[1]->getName());
        self::assertSame('Mazda', $makeList[2]->getName());
        self::assertSame('Mercedes-Benz', $makeList[3]->getName());
        self::assertSame('Volvo', $makeList[4]->getName());

        $carList = $this->carRepository->findBy([], ['model' => 'ASC']);

        self::assertCount(10, $carList);

        self::assertSame("Mercedes-Benz", $carList[0]->getMake()->getName());
        self::assertSame("500SL", $carList[0]->getModel());
        self::assertSame("consequat dui nec nisi volutpat", $carList[0]->getDescription());

        self::assertSame("BMW", $carList[1]->getMake()->getName());
        self::assertSame("6 Series", $carList[1]->getModel());
        self::assertSame("id luctus nec molestie sed justo pellentesque viverra pede ac", $carList[1]->getDescription());

        self::assertSame("Audi", $carList[2]->getMake()->getName());
        self::assertSame("A8", $carList[2]->getModel());
        self::assertSame("dictumst morbi vestibulum velit id pretium iaculis diam erat fermentum", $carList[2]->getDescription());

        self::assertSame("Mazda", $carList[3]->getMake()->getName());
        self::assertSame("CX-7", $carList[3]->getModel());
        self::assertSame("rhoncus dui vel sem sed sagittis", $carList[3]->getDescription());

        self::assertSame("Mazda", $carList[4]->getMake()->getName());
        self::assertSame("Mazda3", $carList[4]->getModel());
        self::assertSame("amet turpis elementum ligula vehicula consequat morbi a", $carList[4]->getDescription());

        self::assertSame("Audi", $carList[5]->getMake()->getName());
        self::assertSame("S4", $carList[5]->getModel());
        self::assertSame("volutpat dui maecenas tristique est", $carList[5]->getDescription());

        self::assertSame("Mercedes-Benz", $carList[6]->getMake()->getName());
        self::assertSame("SL-Class", $carList[6]->getModel());
        self::assertSame("id sapien in sapien iaculis congue vivamus", $carList[6]->getDescription());

        self::assertSame("Mazda", $carList[7]->getMake()->getName());
        self::assertSame("Tribute", $carList[7]->getModel());
        self::assertSame("nunc viverra dapibus nulla suscipit", $carList[7]->getDescription());

        self::assertSame("Audi", $carList[8]->getMake()->getName());
        self::assertSame("TT", $carList[8]->getModel());
        self::assertSame("id luctus nec molestie sed justo", $carList[8]->getDescription());

        self::assertSame("Volvo", $carList[9]->getMake()->getName());
        self::assertSame("XC90", $carList[9]->getModel());
        self::assertSame("rutrum nulla tellus in sagittis dui vel nisl", $carList[9]->getDescription());
    }

    public function testUpdate()
    {
        $carMake = new CarMake();
        $carMake->setName('Honda');

        $car = new Car();
        $car->setModel('Accord');
        $car->setDescription('Unit test');

        $carMake->addCar($car);

        $this->entityManager->persist($car);
        $this->entityManager->persist($carMake);

        $carMake = new CarMake();
        $carMake->setName('BMW');

        $car = new Car();
        $car->setModel('6 Series');
        $car->setDescription('Unit test');

        $carMake->addCar($car);

        $this->entityManager->persist($car);
        $this->entityManager->persist($carMake);

        $this->entityManager->flush();

        $this->carImport->import(__DIR__ . '/car.json');

        $makeList = $this->carMakeRepository->findBy([], ['name' => 'ASC']);

        self::assertCount(6, $makeList);

        self::assertSame('Audi', $makeList[0]->getName());
        self::assertSame('BMW', $makeList[1]->getName());
        self::assertSame('Honda', $makeList[2]->getName());
        self::assertSame('Mazda', $makeList[3]->getName());
        self::assertSame('Mercedes-Benz', $makeList[4]->getName());
        self::assertSame('Volvo', $makeList[5]->getName());

        $carList = $this->carRepository->findBy([], ['model' => 'ASC']);

        self::assertCount(11, $carList);

        self::assertSame("Mercedes-Benz", $carList[0]->getMake()->getName());
        self::assertSame("500SL", $carList[0]->getModel());
        self::assertSame("consequat dui nec nisi volutpat", $carList[0]->getDescription());

        self::assertSame("BMW", $carList[1]->getMake()->getName());
        self::assertSame("6 Series", $carList[1]->getModel());
        self::assertSame("id luctus nec molestie sed justo pellentesque viverra pede ac", $carList[1]->getDescription());

        self::assertSame("Audi", $carList[2]->getMake()->getName());
        self::assertSame("A8", $carList[2]->getModel());
        self::assertSame("dictumst morbi vestibulum velit id pretium iaculis diam erat fermentum", $carList[2]->getDescription());

        self::assertSame("Honda", $carList[3]->getMake()->getName());
        self::assertSame("Accord", $carList[3]->getModel());
        self::assertSame("Unit test", $carList[3]->getDescription());

        self::assertSame("Mazda", $carList[4]->getMake()->getName());
        self::assertSame("CX-7", $carList[4]->getModel());
        self::assertSame("rhoncus dui vel sem sed sagittis", $carList[4]->getDescription());

        self::assertSame("Mazda", $carList[5]->getMake()->getName());
        self::assertSame("Mazda3", $carList[5]->getModel());
        self::assertSame("amet turpis elementum ligula vehicula consequat morbi a", $carList[5]->getDescription());

        self::assertSame("Audi", $carList[6]->getMake()->getName());
        self::assertSame("S4", $carList[6]->getModel());
        self::assertSame("volutpat dui maecenas tristique est", $carList[6]->getDescription());

        self::assertSame("Mercedes-Benz", $carList[7]->getMake()->getName());
        self::assertSame("SL-Class", $carList[7]->getModel());
        self::assertSame("id sapien in sapien iaculis congue vivamus", $carList[7]->getDescription());

        self::assertSame("Mazda", $carList[8]->getMake()->getName());
        self::assertSame("Tribute", $carList[8]->getModel());
        self::assertSame("nunc viverra dapibus nulla suscipit", $carList[8]->getDescription());

        self::assertSame("Audi", $carList[9]->getMake()->getName());
        self::assertSame("TT", $carList[9]->getModel());
        self::assertSame("id luctus nec molestie sed justo", $carList[9]->getDescription());

        self::assertSame("Volvo", $carList[10]->getMake()->getName());
        self::assertSame("XC90", $carList[10]->getModel());
        self::assertSame("rutrum nulla tellus in sagittis dui vel nisl", $carList[10]->getDescription());
    }
}
