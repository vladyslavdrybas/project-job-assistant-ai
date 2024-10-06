<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Job;

use App\DataTransferObject\Form\Job\SalaryDto;
use App\DataTransformer\SwitchEnumFormTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobSalaryFormType extends AbstractType
{
    public function __construct(
        protected readonly SwitchEnumFormTransformer $transformer
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('min',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('max',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('period',
                JobSalaryPeriodFormType::class,
                [
                    'label' => 'Salary period',
                    'required' => false,
                    'attr' => [
                        'class' => 'd-flex flex-row flex-wrap justify-content-start align-items-baseline',
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SalaryDto::class,
        ]);
    }
}
