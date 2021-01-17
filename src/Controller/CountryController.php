<?php

namespace App\Controller;

use App\Exception\ItemExistsException;
use App\Form\CountryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Country;
use Symfony\Component\Form\Forms;
use InvalidArgumentException;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class CountryController extends AbstractController
{
    /**
     * @Route("/country/{canonName}", name="country_show")
     */
    public function show(string $canonName): Response
    {
        $country = Country::getByCanonName($canonName);
        $country->cities;

        return $this->json([
            'data' => $country,
        ]);
    }

    /**
     * This method is only to be accessed from command line
     * 
     * @param string $name
     * @param string $canonName
     * 
     * @return App\Model\Country
     */
    public function create(string $name, string $canonName)
    {
        $validator = Validation::createValidator();
        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new ValidatorExtension($validator))
            ->getFormFactory();

        $form = $formFactory->createBuilder(
            CountryType::class,
            new Country()
        )
            ->getForm();

        $data = [
            'name' => $name,
            'canonicalName' => $canonName,
        ];

        $form->submit($data);

        if (
            !$form->isValid()
        ) {
            throw new InvalidArgumentException(
                (string)$form->getErrors(true)
            );
        }

        $this->assertIfExist($name, $canonName);

        $country = new Country($data);
        $country->save();

        return $country;
    }

    protected function assertIfExist($name, $canonicalName)
    {
        $country = Country::where('name', $name)
            ->orWhere('canonicalName', $canonicalName)
            ->exists();

        if (
            $country
        ) {
            throw new ItemExistsException("The country with this name or canonical name already exists!");
        }

        return false;
    }
}
