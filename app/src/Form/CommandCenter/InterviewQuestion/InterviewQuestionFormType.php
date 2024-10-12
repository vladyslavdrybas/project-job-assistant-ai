<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\InterviewQuestion;

use App\Constants\InterviewQuestion\InterviewQuestionCategory;
use App\DataTransferObject\Form\InterviewQuestion\InterviewQuestionDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewQuestionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Title your Interview Question',
                ]
            )->add('description',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )->add('category',
                ChoiceType::class,
                [
                    'required' => false,
                    'choices' => array_flip(InterviewQuestionCategory::array()),
                    'placeholder' => false,
                ]
            )->add('tips',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )->add('answerFramework',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )->add('answer',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )->add('actionBtn',
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
            'data_class' => InterviewQuestionDto::class,
        ]);
    }
}
