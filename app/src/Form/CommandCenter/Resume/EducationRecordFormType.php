<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Resume;

use App\DataTransferObject\Form\EducationHistory\EducationRecordDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EducationRecordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
            ->add('degree',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('school',
                SchoolFormType::class,
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
            ->add('description',
                TextareaType::class,
                [
                    'required' => false,
                    'help' => 'Recruiter tip: write 200+ characters to increase interview chances.'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EducationRecordDto::class,
        ]);
    }
}
