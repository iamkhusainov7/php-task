<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints'  => [
                    new NotBlank(),
                    new Length(null, null, 100),
                    new Regex('/^[A-Za-z]+$/', 'Name can not include any numbers or special chars')
                ],
            ])
            ->add('canonicalName', TextType::class, [
                'constraints'  => [
                    new NotBlank(),
                    new Length(null, null, 100),
                    new Regex('/^[a-z]+$/', 'Canonical name can not include any numbers or special chars')
                ],
            ]);
    }
}
