<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\InterviewQuestion;

use App\Constants\InterviewQuestion\InterviewQuestionCategory;
use App\DataTransferObject\Form\InterviewQuestion\InterviewQuestionDto;
use App\Services\OpenAi\OpenAiFacade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewQuestionFormType extends AbstractType
{
    public function __construct(
        protected readonly OpenAiFacade $openAiFacade
    ) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                $actionBtn = $data['actionBtn'] ?? null;

                $dto = $form->getData();
                $dto->answer = $data['answer'] ?? null;
                try {
                    if ('ai' === $actionBtn) {
                        $dto = $this->openAiFacade->interviewQuestionAnswer($dto);
                        $data['answer'] = $dto->answer;
                        $event->setData($data);
                    }
                } catch (\Exception $e) {
                    $form->addError(new FormError($e->getMessage()));
                }
            }
        );

        /** @var InterviewQuestionDto $data */
        $data = $builder->getData();

        $builder->add('title',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Title your Interview Question',
                    'data' => $data->title,
                ]
            )->add('description',
                TextareaType::class,
                [
                    'required' => false,
                    'data' => $data->description,
                    'help' => 'Try to describe what you expect from the answer. How should the answer bring out the best in you?'
                ]
            )->add('category',
                ChoiceType::class,
                [
                    'required' => false,
                    'choices' => array_flip(InterviewQuestionCategory::array()),
                    'placeholder' => false,
                    'data' => InterviewQuestionCategory::from($data->category)->name,
                ]
            )->add('tips',
                TextareaType::class,
                [
                    'required' => false,
                    'data' => $data->tips,
                    'help' => 'Give a 2-4 tips or advices how to answer.'
                ]
            )->add('answerFramework',
                TextareaType::class,
                [
                    'required' => false,
                    'data' => $data->answerFramework,
                    'help' => 'Explain the best way of answering. Provide formal structure for your answer.'
                ]
            )->add('answer',
                TextareaType::class,
                [
                    'required' => false,
                    'data' => $data->answer,
                    'help' => 'Use AI creator to generate your ideal answer. AI tool need some predefined information: your biography, question title, tips, framework. Remember: as more information our AI tool gets the better result you will receive.'
                ]
            )->add('actionBtn',
                HiddenType::class,
                [
                    'mapped' => false,
                    'empty_data' => 'save',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InterviewQuestionDto::class,
            'allow_extra_fields' => true,
        ]);
    }
}
