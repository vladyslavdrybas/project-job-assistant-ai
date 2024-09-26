<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Resume;

use App\DataTransferObject\Form\LanguageDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
            ->add('code',
                HiddenType::class,
                [
                    'required' => false,
                ]
            )
            ->add('title',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('level',
                ChoiceType::class,
                [
                    'choices' => [
                        'Native speaker' => 'native',
                        'Fluent' => 'fluent',
                        'Working knowledge' => 'working_knowledge',
                        'Documentation reader' => 'documentation_reader',
                        'Highly proficient' => 'highly_proficient',
                        'A1' => 'a1',
                        'B1' => 'b1',
                        'B2' => 'b2',
                        'C1' => 'c1',
                        'C2' => 'c2',
                    ],
                    'required' => false,
                    'data' => 'b1'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LanguageDto::class,
        ]);
    }
}
