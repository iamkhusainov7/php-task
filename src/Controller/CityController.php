<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\City;
use Throwable;

class CityController extends AbstractController
{
    /**
     * @Route("/city", name="city_index")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CityController.php',
        ]);
    }

    /**
     * @Route("/city/{id}", name="city_show")
     */
    public function show(int $id): Response
    {
        try {
            $city = City::find($id);

            return $this->json([
                'message' => 'Welcome to your new controller!',
                'path' => 'src/Controller/CityController.php',
            ]);
        } catch(Throwable $e) {
            throw $e;
        }
    }
}
