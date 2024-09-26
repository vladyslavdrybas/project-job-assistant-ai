<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Resume;

use App\DataTransferObject\Form\ResumeDto;
use App\Form\MediaCreatorFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResumeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
            ->add('title',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('jobTitle',
                TextType::class,
                [
                    'required' => false,
                    'data' => $data->jobTitle,
                ]
            )
            ->add('photo',
                MediaCreatorFormType::class,
                [
                    'required' => false,
                ]
            )
            ->add('firstName',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('lastName',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('contacts',
                ContactsFormType::class,
                [
                    'required' => false,
                ]
            )
            ->add('professionalSummary',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ResumeDto::class,
        ]);
    }
}
