<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Resume;

use App\DataTransferObject\Form\EmploymentHistory\EmploymentRecordDto;
use App\Form\CommandCenter\Job\JobFormatsFormType;
use App\Form\TagsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmploymentRecordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
            ->add('jobTitle',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('projectTitle',
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
            ->add('contactPerson',
                ContactPersonFormType::class,
                [
                    'required' => false,
                ]
            )
            ->add('startDate',
                DateType::class,
                [
                    'required' => false,
                ]
            )
            ->add('endDate',
                DateType::class,
                [
                    'required' => false,
                ]
            )
            ->add('formats',
                JobFormatsFormType::class,
                [
                    'required' => false,
                    'label' => 'Job formats',
                    'attr' => [
                        'class' => 'd-flex flex-row flex-wrap justify-content-start align-items-baseline',
                    ]
                ]
            )
            ->add('skills',
                TagsType::class,
                [
                    'mapped' => true,
                    'label' => 'Skills applied',
                ]
            )
            ->add('description',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Project Description',
                    'help' => 'Recruiter tip: write 200+ characters to increase interview chances.',
                    'attr' => [
                        'rows' => 10,
                    ],
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
            'data_class' => EmploymentRecordDto::class,
        ]);
    }
}
