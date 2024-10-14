<?php
declare(strict_types=1);

namespace App\Services\OpenAi;

use App\DataTransferObject\Form\InterviewQuestion\InterviewQuestionDto;
use App\Services\OpenAi\Business\InterviewQuestionAnswer;

class OpenAiFacade
{
    public function __construct(
       protected readonly InterviewQuestionAnswer $interviewQuestionAnswer
    ) {}
    public function interviewQuestionAnswer(InterviewQuestionDto $dto): InterviewQuestionDto
    {
        return $this->interviewQuestionAnswer->process($dto);
    }
}
