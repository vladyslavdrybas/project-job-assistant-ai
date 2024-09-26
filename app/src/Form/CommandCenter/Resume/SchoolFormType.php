<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Resume;

use App\DataTransferObject\Form\EducationHistory\SchoolDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
            ->add('title',
                TextType::class,
                [
                    'label' => 'School',
                    'required' => false,
                ]
            )
            ->add('aboutPage',
                UrlType::class,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SchoolDto::class,
        ]);
    }
}
