<?php
declare(strict_types=1);

namespace App\Services\OpenAi\Business;

use App\DataTransferObject\Form\InterviewQuestion\InterviewQuestionDto;
use App\Services\OpenAi\Client\OpenAiClient;
use Exception;

class InterviewQuestionAnswer
{
    public function __construct(
        protected readonly OpenAiClient $openAiClient
    ) {}

    public function process(InterviewQuestionDto $dto): InterviewQuestionDto
    {
        if (empty($dto->title)) {
            throw new Exception('I need a title of the question to help you.');
        }

        if ($dto->owner === null) {
            throw new Exception('I need information about user. No user found.');
        }

        if (
            $dto->description === null
            && $dto->tips === null
            && $dto->answerFramework === null
        ) {
            throw new Exception('I need more information to help you. Please, add description, tips or framework.');
        }

        $prompt = 'Ignore all previous instructions.
Respond only in the English language.
Do not self-reference.
Do not explain what you are doing. Do not comment answer. Act as You\'re a professional content writer. I want you to write me an answer on the question I will ask.
You can use tips I may provide, framework how to build answer, and context why do I need this answer. All additional information you can find above between this symbols [].
Use formal language. Use all data to highlight my strongest sides. Pay attention on markdown and highlights in text I will provide.
Provide short answer in 500-4096 characters as a consistent story. Short is better!!!
Do not try to cover all my skills, achievements and experience.
I want you to describe general picture with one or two specific examples.
Important: follow timeline of my education, work experience and and achievements!!!';

        $prompt .= "\n" . 'QUESTION YOU MUST ANSWER ON: [' . $dto->title . ']. FOCUS ON the question, everything else is a helpful information.';

        if (null !== $dto->owner->getBiography()) {
            $prompt .= "\n" . 'Use my biography to write more accurate answer: [' . $dto->owner->getBiography() . ']';
        }

        if (null !== $dto->description) {
            $prompt .= "\n" . 'Use question overview to get a reason why do I need this answer: [' . $dto->description . ']';
        }

        if (null !== $dto->tips) {
            $prompt .= "\n" . 'Use my tips that I usually use to build a general picture of the answer: [' . $dto->tips . ']';
        }

        if (null !== $dto->answerFramework) {
            $prompt .= "\n" . 'Use my framework to get what I want to see and how to build answer: [' . $dto->answerFramework . ']';
        }

        if (null !== $dto->answer) {
            $prompt .= "\n" . 'Use my answer as an example, but feel free to rewrite it: [' . $dto->answer . ']';
        }

        $answer = $this->openAiClient->prompt($prompt);

        $answer = $this->openAiClient->convertPromptPlainTextAnswerToText($answer);

        $dto->answer = $answer;

        return $dto;
    }
}
