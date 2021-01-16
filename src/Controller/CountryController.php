<?php

namespace App\Controller;

use App\Exception\ItemExistsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Country;
use InvalidArgumentException;
use Throwable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class CountryController extends AbstractController
{
    /**
     * @Route("/country/{canonName}", name="country_show")
     */
    public function show(string $canonName): Response
    {
        try {
            $country = Country::find($canonName);
            return $this->json([
                'message' => 'Welcome to your new controller!',
                'path' => 'src/Controller/CountryController.php',
            ]);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function create($name, $canonName)
    {
        try {
            $this->validate($name, $canonName);

            $this->assertIfExist($name, $canonName);

            $country = Country::create([
                'name' => $name,
                'canonicalName' => $canonName
            ]);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    protected function assertIfExist($name, $canonicalName)
    {
        $country = Country::where('name', $name)
            ->orWhere('canonicalName', $canonicalName)
            ->exists();

        if (
            $country
        ) {
            throw new ItemExistsException("The country with this name or canonical name already exist!");
        }

        return false;
    }

    protected function validate($name, $canonicalName)
    {
        $validator = Validation::createValidator();

        $errors = $validator->validate(
            [
                'name' => $name,
                'canonicalName' => $canonicalName
            ],
            $this->validationRules()
        );

        if (
            count($errors) > 0
        ) {
            throw new InvalidArgumentException((string) $errors);
        }

        return true;
    }

    private function validationRules()
    {
        $regex = new Assert\Regex([
            'pattern' => '/^[a-z]+$/'
        ]);

        $notBlank = new Assert\NotBlank();
        $length = new Assert\Length([
            'max' => 100
        ]);

        return new Assert\Collection([
            'name' => [
                $notBlank,
                $regex,
                $length
            ],
            'canonicalName' => [
                $notBlank,
                $regex,
                $length
            ]
        ]);
    }
}
