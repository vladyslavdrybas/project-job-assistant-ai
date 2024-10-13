<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Profile;

use App\Entity\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class UserBiographyEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();
        dump($data);

        $builder->add('biography',
            TextAreaType::class,
                [
                    'required' => false,
                    'label' => 'My biography',
                    'help' => 'Describe your work experience, habits, achievements, hobbies in a few paragraphs. It will help our AI tool to create more accurate content for you.',
                    'data' => $data['biography'] ?? null,
                    'constraints' => [
                        new Length(['min' => 100, 'max' => UserInterface::VALIDATE_BIOGRAPHY_MAX_LENGTH]),
                    ]
                ]
            )->add('actionBtn',
                HiddenType::class,
                [
                    'mapped' => false,
                    'empty_data' => 'save'
                ]
            )
        ;
    }
}
