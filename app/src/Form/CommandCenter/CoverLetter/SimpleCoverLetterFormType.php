<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\CoverLetter;

use App\DataTransferObject\Form\CoverLetterDto;
use App\Form\CommandCenter\Resume\ContactPersonFormType;
use App\Form\CommandCenter\Resume\EmployerFormType;
use App\Repository\EmploymentRepository;
use App\Repository\JobRepository;
use App\Services\OpenAi\OpenAiFacade;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleCoverLetterFormType extends AbstractType
{
    public function __construct(
        protected readonly OpenAiFacade $openAiFacade
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $builder->addEventListener(
//            FormEvents::PRE_SUBMIT,
//            function(FormEvent $event) {
//                $form = $event->getForm();
//                $data = $event->getData();
//                $actionBtn = $data['actionBtn'] ?? null;
//
//                $dto = $form->getData();
//                $dto->answer = $data['answer'] ?? null;
//                try {
//                    if ('ai' === $actionBtn) {
//                        $dto = $this->openAiFacade->interviewQuestionAnswer($dto);
//                        $data['answer'] = $dto->answer;
//                        $event->setData($data);
//                    }
//                } catch (\Exception $e) {
//                    $form->addError(new FormError($e->getMessage()));
//                }
//            }
//        );

        /** @var CoverLetterDto $data */
        $data = $builder->getData();

        $builder->add('title',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Title your Cover Letter',
                    'data' => $data->title,
                ]
            )
            ->add('jobTitle',
                TextType::class,
                [
                    'required' => false,
                    'data' => $data->jobTitle,
                ]
            )
            ->add('employer',
                EmployerFormType::class,
                [
                    'required' => false,
                    'data' => $data->employer,
                ]
            )
            ->add('sender',
                ContactPersonFormType::class,
                [
                    'required' => false,
                    'data' => $data->sender,
                ]
            )
            ->add('receiver',
                ContactPersonFormType::class,
                [
                    'required' => false,
                    'data' => $data->receiver,
                ]
            )
            ->add('content',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Letter Body',
                    'data' => $data->content,
                ]
            )
            ->add('promptTips',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'AI Tips',
                    'data' => $data->promptTips,
                ]
            )
            ->add('promptFramework',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'AI Framework',
                    'data' => $data->promptFramework,
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
            'data_class' => CoverLetterDto::class,
        ]);
    }
}
