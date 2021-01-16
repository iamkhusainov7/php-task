<?php

namespace App\Controller;

use App\Form\CityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\City;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class CityController extends AbstractController
{
    /**
     * @Route("/city", name="city_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->json([
            'data' => City::with('country')
                ->get()
                ->toArray()
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
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * @Route("/city", name="city_create", methods={"POST"})
     */
    public function create(Request $request)
    {
        try {
            $form = $this->createForm(CityType::class, new City(), ['csrf_protection' => false]);

            $data = [
                'name' => $request->get('name'),
                'country_id' => (int)$request->get('country_id'),
            ];
            
            $form->submit($data);

            if (
                !$form->isValid()
            ) {
                throw new InvalidArgumentException(
                    (string)$form->getErrors()
                );
            }

            $city = new City($data);

            $city->save();

            return $this->json([
                'data' => $city
            ], 201);
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
