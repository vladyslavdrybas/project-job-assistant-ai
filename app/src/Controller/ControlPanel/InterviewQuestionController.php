<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\InterviewQuestion\InterviewQuestionCategory;
use App\Constants\RouteRequirements;
use App\DataTransferObject\Form\InterviewQuestion\InterviewQuestionDto;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\InterviewQuestion;
use App\EntityTransformer\InterviewQuestionTransformer;
use App\Form\CommandCenter\InterviewQuestion\InterviewQuestionFormType;
use App\Repository\InterviewQuestionRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/cp/interview/question",
    name: "cp_interview_question",
    requirements: [
        'interviewQuestion' => RouteRequirements::UUID->value,
    ]
)]
class InterviewQuestionController extends AbstractControlPanelController
{
    #[Route(
        's',
        name: '_board',
        methods: ['GET']
    )]
    public function list(
        InterviewQuestionRepository $interviewQuestionRepository,
        InterviewQuestionTransformer $interviewQuestionTransformer
    ): ViewResponseDto {
        $questionsDefault = $interviewQuestionRepository->findDefaults();
        $questionsDefault = array_map(function (InterviewQuestion $question) use ($interviewQuestionTransformer) {
            return $interviewQuestionTransformer->reverseTransform($question);
        }, $questionsDefault);

        $questionsMy = $interviewQuestionRepository->findByUser($this->getUser());
        $questionsMy = array_map(function (InterviewQuestion $question) use ($interviewQuestionTransformer) {
            return $interviewQuestionTransformer->reverseTransform($question);
        }, $questionsMy);

        /** @var array<InterviewQuestionDto> $data */
        $data = array_merge($questionsDefault, $questionsMy);

        $questions = [];

        foreach ($data as $question) {
            if (!isset($questions[$question->hash])) {
                $questions[$question->hash] = $question;
                continue;
            }

            if ($questions[$question->hash]->isDefault) {
                $questions[$question->hash] = $question;
            } else if (!$question->isDefault) {

                $questions[$question->hash . bin2hex(random_bytes(2))] = $question;
            }
        }

        return $this->response(
            [
                'questions' => $questions,
                'formActions' => ['add'],
            ],
            'control-panel/interview-question/board.html.twig'
        );
    }

    #[Route(
        '/add',
        name: '_add',
        methods: ['GET']
    )]
    public function add(): ViewResponseDto {
        $entity = new InterviewQuestion();
        $entity->setOwner($this->getUser());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->response(
            [
                'interviewQuestion' => $entity->getRawId(),
            ],
            'cp_interview_question_edit'
        );
    }

    #[Route(
        '/{interviewQuestion}/edit',
        name: '_edit',
        methods: ['GET','POST']
    )]
    public function edit(
        Request $request,
        InterviewQuestion $interviewQuestion,
        InterviewQuestionTransformer $transformer
    ): ViewResponseDto {
        $dto = $transformer->reverseTransform($interviewQuestion);
        $canClone = $interviewQuestion->canClone($this->getUser());
        // clone default to user's personal
        if ($canClone) {
            $dto->id = null;
            $dto->owner = $this->getUser();
            $dto->updatedAt = null;
            $dto->createdAt = new DateTime();
            $dto->isPublic = false;
            $dto->isDefault = false;
        }

        $form = $this->createForm(InterviewQuestionFormType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var InterviewQuestionDto $dto */
            $dto = $form->getData();
            $actionBtn = $form->get('actionBtn')->getData();

            $dto->category = InterviewQuestionCategory::fromName($dto->category)->value;

            $entity = $transformer->transform($dto);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            // redirect editing to user's personal
            if ($canClone) {
                return $this->response(
                    [
                        'interviewQuestion' => $entity,
                    ],
                    'cp_interview_question_edit'
                );
            }

            if ('view' === $actionBtn) {
                return $this->response(
                    [
                        'interviewQuestion' => $entity,
                    ],
                    'cp_interview_question_show'
                );
            } else if ('saveandnew' === $actionBtn) {
                return $this->response(
                    [],
                    'cp_interview_question_add'
                );
            }
        }

        return $this->response(
            [
                'form' => $form,
                'formActions' => ['save', 'view', 'saveandnew', 'ai']
            ],
            'control-panel/interview-question/edit.html.twig'
        );
    }

    #[Route(
        '/{interviewQuestion}',
        name: '_show',
        methods: ['GET']
    )]
    public function show(
        InterviewQuestion $interviewQuestion,
        InterviewQuestionTransformer $transformer
    ): ViewResponseDto {
        $dto = $transformer->reverseTransform($interviewQuestion);

        return $this->response(
            [
                'interviewQuestion' => $dto,
                'navActions' => [
                    'edit' => [
                        'type' => 'link',
                        'title' => 'Edit',
                        'link' => $this->generateUrl('cp_interview_question_edit', ['interviewQuestion' => $interviewQuestion]),
                    ],
                ],
            ],
            'control-panel/interview-question/show.html.twig'
        );
    }
}
