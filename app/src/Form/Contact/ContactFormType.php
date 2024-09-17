<?php
declare(strict_types=1);

namespace App\Form\Contact;

use App\DataTransferObject\Form\Contact\ClientRequestCallBackDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clientEmail',
                EmailType::class,
                [
                    'required' => true,
                    'label' => 'Email',
                    'row_attr' => [
                        'class' => 'mb-3 input-group',
                    ],
                    'label_attr' => [
                        'class' => 'input-group-text',
                    ],
                    'attr' => [
                        'class' => 'form-control rounded-0',
                    ]
                ]
            )
            ->add('clientName',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Full Name',
                    'row_attr' => [
                        'class' => 'mb-3 input-group',
                    ],
                    'label_attr' => [
                        'class' => 'input-group-text'
                    ],
                    'attr' => [
                        'class' => 'form-control rounded-0',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter your name.',
                        ]),
                        new Length([
                            'min' => 3,
                            'minMessage' => 'Your name should be at least 3 and no more than 100 characters',
                            'max' => 100,
                        ]),
                    ],
                ]
            )
            ->add('projectDescription', TextareaType::class,
                [
                    'required' => true,
                    'label' => 'Project description',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'attr' => [
                        'class' => 'form-control rounded-0 h-100',
                        'rows' => 8,
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a project description.',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your project description should be at least 6 and no more than 4096 characters',
                            'max' => 4096,
                        ]),
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientRequestCallBackDto::class,
            'allow_extra_fields' => true,
        ]);
    }
}
