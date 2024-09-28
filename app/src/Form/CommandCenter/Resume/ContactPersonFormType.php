<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Resume;

use App\DataTransferObject\Form\Contact\ContactPersonDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactPersonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
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
            ->add('employer',
                EmployerFormType::class,
                [
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactPersonDto::class,
        ]);
    }
}
