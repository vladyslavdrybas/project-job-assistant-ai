<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Constants\RouteRequirements;
use App\DataTransferObject\Form\InterviewQuestion\InterviewQuestionDto;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\InterviewQuestion;
use App\EntityTransformer\InterviewQuestionTransformer;
use App\Form\CommandCenter\InterviewQuestion\InterviewQuestionFormType;
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
    ): ViewResponseDto {

        $questionsDefault = $this->fakeDefaultQuestions();

        $questionsMy = [];

        $questions = array_merge($questionsDefault, $questionsMy);

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
        dump($dto);

        $form = $this->createForm(InterviewQuestionFormType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var InterviewQuestionDto $dto */
            $dto = $form->getData();

            $entity = $transformer->transform($dto);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        return $this->response(
            [
                'form' => $form,
                'formActions' => ['save', 'view', 'save&new']
            ],
            'control-panel/interview-question/edit.html.twig'
        );
    }

    protected function fakeDefaultQuestions(): array
    {
        return [
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about yourself.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 1, 2,000 characters max',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer 1',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'What is your greatest strength?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => '',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'What is your greatest weakness?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Why should we hire you?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Why do you want to work here?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you showed leadership.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you were successful on a team.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'What would your co-workers say about you?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Why do you want to leave your current role?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Why do you want to leave your current role?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about something you’ve accomplished that you are proud of.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Can you explain your employment gap?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'What are your salary expectations?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'What do you like to do outside of work?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you had to manage conflicting priorities.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Where do you see yourself in 5 years?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Describe your leadership style.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you failed or made a mistake.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you worked with a difficult person.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you had to persuade someone.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you disagreed with someone.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you created a goal and achieved it.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you surpassed people’s expectations.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you had to handle pressure.',
                'content' => [
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Tell me about a time you had to learn something quickly.',
                'content' => [
                    'category' => 'common',
                    'tips' => 'question 2',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
            [
                'id' => bin2hex(random_bytes(16)),
                'title' => 'Do you have any questions for me?',
                'content' => [
                    'category' => 'common',
                    'tips' => 'Tips
Come prepared with 3-5 thoughtful questions.
Ask questions that show you’re engaged, intelligent and interested.
Avoid no-brainer questions or ones related to salary / benefits.',
                    'examples' => [],
                    'answer_framework' => '',
                    'answer' => 'answer2',
                ],
            ],
        ];
    }
}
