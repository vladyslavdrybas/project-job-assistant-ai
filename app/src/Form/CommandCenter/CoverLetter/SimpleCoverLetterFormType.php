<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\CoverLetter;

use App\DataTransferObject\Form\CoverLetterDto;
use App\Form\CommandCenter\Resume\ContactPersonFormType;
use App\Form\CommandCenter\Resume\EmployerFormType;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
                    'label' => 'Title your Cover Letter'
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
                CoverLetterContentFormType::class,
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
            ->add('actionBtn',
                HiddenType::class,
                [
                    'mapped' => false,
                    'empty_data' => 'save'
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
