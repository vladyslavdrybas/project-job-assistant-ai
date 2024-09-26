<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Resume;

use App\Constants\LanguageLevelChoicesEnum;
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
        $levelChoices = LanguageLevelChoicesEnum::array();

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
                    'choices' => $levelChoices,
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
