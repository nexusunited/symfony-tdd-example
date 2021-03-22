<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Car as CarEntity;
use App\Entity\CarMake;
use App\Repository\CarMakeRepository;
use App\Repository\CarRepository;
use App\Service\Mapping\Car;
use Doctrine\ORM\EntityManagerInterface;

class CarImport
{
    /**
     * @var \App\Service\Mapping\Car
     */
    private Car $carMapping;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var \App\Repository\CarRepository
     */
    private CarRepository $carRepository;

    /**
     * @var \App\Repository\CarMakeRepository
     */
    private CarMakeRepository $carMakeRepository;

    public function __construct(
        Car $carMapping,
        EntityManagerInterface $entityManager,
        CarRepository $carRepository,
        CarMakeRepository $carMakeRepository
    )
    {
        $this->carMapping = $carMapping;
        $this->entityManager = $entityManager;
        $this->carRepository = $carRepository;
        $this->carMakeRepository = $carMakeRepository;
    }

    public function import(string $pathToJson)
    {
        $carDtoList = $this->convertJsonToCarDtoList($pathToJson);

        foreach ($carDtoList as $carDto) {
            $carMakeEntity = $this->carMakeRepository->findOneBy(['name' => $carDto->getMake()]);
            if(!$carMakeEntity instanceof CarMake) {
                $carMakeEntity = new CarMake();
                $carMakeEntity->setName($carDto->getMake());
                $this->entityManager->persist($carMakeEntity);
                $this->entityManager->flush();
            }

            $carEntity = $this->carRepository->findOneBy(['model' => $carDto->getModel(), 'make' => $carMakeEntity]);
            if(!$carEntity instanceof CarEntity) {
                $carEntity = new CarEntity();
                $carEntity->setModel($carDto->getModel());
                $carEntity->setMake($carMakeEntity);
            }

            $carEntity->setDescription($carDto->getDescription());

            $this->entityManager->persist($carEntity);
        }

        $this->entityManager->flush();
    }

    /**
     * @param string $pathToJson
     *
     * @return \App\Service\Dto\CarImportDto[]
     */
    private function convertJsonToCarDtoList(string $pathToJson): array
    {
        $carDtoList = [];
        $carInfoList = json_decode(file_get_contents($pathToJson), true, 512, JSON_THROW_ON_ERROR);

        foreach ($carInfoList as $carInfo) {
            $carDtoList[] = $this->carMapping->mapJsonToCarImportDto($carInfo);
        }
        
        return $carDtoList;
    }
}
