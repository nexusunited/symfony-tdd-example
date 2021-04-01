<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Car;
use App\Entity\CarMake;
use App\Repository\CarMakeRepository;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    private CarMakeRepository $carMakeRepository;

    private CarRepository $carRepository;

    /**
     * @param \App\Repository\CarMakeRepository $carMakeRepository
     * @param \App\Repository\CarRepository $carRepository
     */
    public function __construct(
        CarMakeRepository $carMakeRepository,
        CarRepository $carRepository
    )
    {
        $this->carMakeRepository = $carMakeRepository;
        $this->carRepository = $carRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $makeList = $this->carMakeRepository->findAll();

        return $this->render('home.html.twig', [
            'makeList' => $makeList,
        ]);
    }

    /**
     * @Route("/make/{makeId}", name="make")
     */
    public function make(int $makeId): Response
    {
        $make = $this->carMakeRepository->find($makeId);

        if (!$make instanceof CarMake) {
            throw $this->createNotFoundException('The make does not exist');
        }

        return $this->render('make.html.twig', [
            'make' => $make,
        ]);
    }

    /**
     * @Route("/car/{carId}", name="car")
     */
    public function car(int $carId): Response
    {
        $car = $this->carRepository->find($carId);
        if (!$car instanceof Car) {
            throw $this->createNotFoundException('The car does not exist');
        }

        return $this->render('car.html.twig', [
            'car' => $car,
        ]);
    }

}
