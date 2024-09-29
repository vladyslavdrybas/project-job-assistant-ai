<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\CoverLetter;

use App\DataTransferObject\Form\CoverLetterContentDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoverLetterContentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('opening',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Opening',
                    'help' => 'What I\'am applying for. Where I\'am applying to. Why I\'am applying.',
                ]
            )
            ->add('whyMe',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Why Me',
                    'help' => 'Why do I chose company in context of my achievements and plans. List relevant experience. Achievements and facts connected to the company goals and position responsibilities.',
                ]
            )
            ->add('conclusion',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Conclusion',
                    'help' => 'Mention future plans. Call to action. Thank the reader.',
                ]
            )
            ->add('signoff',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Sign Off',
                    'help' => 'Wish Best Regards and Sign the letter, add date etc.',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoverLetterContentDto::class,
        ]);
    }
}
