<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Job;

use App\DataTransferObject\Form\Job\JobDto;
use App\Form\CommandCenter\Resume\ContactPersonFormType;
use App\Form\CommandCenter\Resume\EmployerFormType;
use App\Form\CommandCenter\Resume\LocationFormType;
use App\Form\TagsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',
                TextType::class,
                [
                    'required' => true,
                    'help' => 'Usually it is job position name.',
                ]
            )
            ->add('aboutPage',
                TextType::class,
                [
                    'required' => false,
                    'help' => 'Link to the page where job description is.',
                ]
            )
            ->add('formats',
                JobFormatsFormType::class,
                [
                    'required' => false,
                    'label' => 'Job formats',
                    'attr' => [
                        'class' => 'd-flex flex-row'
                    ]
                ]
            )
            ->add('location',
                LocationFormType::class,
                [
                    'required' => false,
                    'label' => 'Location details',
                ]
            )
            ->add('employer',
                EmployerFormType::class,
                [
                    'required' => false,
                    'help' => 'Hiring company.',
                ]
            )
            ->add('contactPerson',
                ContactPersonFormType::class,
                [
                    'required' => false,
                    'help' => 'Hiring contact person.',
                ]
            )
            ->add('skills',
                TagsType::class,
                [
                    'mapped' => true,
                    'label' => 'Required skills as you understand.'
                ]
            )
            ->add('content',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Job Description',
                    'help' => 'Add full text of the job. Ideally, do not change content of the job description.',
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
            'data_class' => JobDto::class,
            'allow_extra_fields' => true,
        ]);
    }
}
