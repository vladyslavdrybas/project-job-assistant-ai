<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\CoverLetter;

use App\DataTransferObject\Form\CoverLetterDto;
use App\Form\CommandCenter\Resume\ContactPersonFormType;
use App\Form\CommandCenter\Resume\EmployerFormType;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleCoverLetterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title',
                TextType::class,
                [
                    'required' => false,
                ]
            )->add('jobTitle',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('employer',
                EmployerFormType::class,
                [
                    'required' => false,
                ]
            )
            ->add('sender',
                ContactPersonFormType::class,
                [
                    'required' => false,
                ]
            )
            ->add('receiver',
                ContactPersonFormType::class,
                [
                    'required' => false,
                ]
            )
            ->add('content',
            TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add('isNeedAiHelp',
            SwitchType::class,
                [
                    'required' => false,
                    'data' => false,
                    'attr' => [
                        'class' => 'd-none',
                    ],
                    'row_attr' => [
                        'class' => 'd-none',
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoverLetterDto::class,
        ]);
    }
}
