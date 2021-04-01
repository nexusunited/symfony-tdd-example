<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Controller;

use App\Entity\Car;
use App\Entity\CarMake;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CarControllerTest extends WebTestCase
{
    private ?ObjectManager $entityManager;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();

        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeUpdate('DELETE FROM car');
        $connection->executeUpdate('ALTER TABLE car AUTO_INCREMENT=0');
        $connection->executeUpdate('DELETE FROM car_make');
        $connection->executeUpdate('ALTER TABLE car_make AUTO_INCREMENT=0');

        $this->entityManager = null;
    }

    public function testHomePage()
    {
        $this->createData();
        $crawler = $this->client->request(
            'GET',
            '/'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', 'Car');

        $makeList = $crawler->filter('ul.car > li > a');

        self::assertCount(2, $makeList);

        $bmwInfo = $makeList->getNode(0);
        self::assertSame('BMW', $bmwInfo->nodeValue);
        self::assertSame('http://localhost/make/1', $bmwInfo->attributes->item(0)->nodeValue);

        $audiInfo = $makeList->getNode(1);
        self::assertSame('Audi', $audiInfo->nodeValue);
        self::assertSame('http://localhost/make/2', $audiInfo->attributes->item(0)->nodeValue);
    }

    public function testMakeBmwPage()
    {
        $this->createData();
        $crawler = $this->client->request(
            'GET',
            '/make/1'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', 'BMW');

        $makeList = $crawler->filter('ul.car > li > a');

        self::assertCount(1, $makeList);

        $bmwInfo = $makeList->getNode(0);
        self::assertSame('6 Series', $bmwInfo->nodeValue);
        self::assertSame('http://localhost/car/1', $bmwInfo->attributes->item(0)->nodeValue);
    }

    public function testMakeAudiPage()
    {
        $this->createData();
        $crawler = $this->client->request(
            'GET',
            '/make/2'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', 'Audi');

        $makeList = $crawler->filter('ul.car > li > a');

        self::assertCount(3, $makeList);

        $bmwInfo = $makeList->getNode(0);
        self::assertSame('TT', $bmwInfo->nodeValue);
        self::assertSame('http://localhost/car/2', $bmwInfo->attributes->item(0)->nodeValue);

        $bmwInfo = $makeList->getNode(1);
        self::assertSame('A8', $bmwInfo->nodeValue);
        self::assertSame('http://localhost/car/3', $bmwInfo->attributes->item(0)->nodeValue);

        $bmwInfo = $makeList->getNode(2);
        self::assertSame('S4', $bmwInfo->nodeValue);
        self::assertSame('http://localhost/car/4', $bmwInfo->attributes->item(0)->nodeValue);
    }

    public function testMakeNotFound()
    {
        $this->client->request(
            'GET',
            '/make/99'
        );
        self::assertResponseStatusCodeSame(404);
    }

    public function testAudiTTCarPage()
    {
        $this->createData();
        $this->client->request(
            'GET',
            '/car/2'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', 'TT');
        self::assertSelectorTextContains('h2', 'Audi');
        self::assertSelectorTextContains('h3', 'id luctus nec molestie sed justo');
    }

    public function testCarPageWhenCarNotFound()
    {
        $this->client->request(
            'GET',
            '/car/99'
        );
        self::assertResponseStatusCodeSame(404);
    }


    private function createData()
    {
        $data = [
            'BMW' =>
                [
                    [
                        'model' => '6 Series',
                        'desc' => 'id luctus nec molestie sed justo ',
                    ],
                ],
            'Audi' =>
                [
                    [
                        'model' => 'TT',
                        'desc' => 'id luctus nec molestie sed justo',
                    ],
                    [
                        'model' => 'A8',
                        'desc' => 'dictumst morbi vestibulum velit id pretium iaculis diam erat fermentum',
                    ],
                    [
                        'model' => 'S4',
                        'desc' => 'volutpat dui maecenas tristique est',
                    ],
                ],
        ];

        foreach ($data as $make => $modelList) {
            $carMake = new CarMake();
            $carMake->setName($make);
            foreach ($modelList as $model) {
                $car = new Car();
                $car->setModel($model['model']);
                $car->setDescription($model['desc']);

                $carMake->addCar($car);
                $this->entityManager->persist($car);
            }
            $this->entityManager->persist($carMake);
        }

        $this->entityManager->flush();
    }

}
