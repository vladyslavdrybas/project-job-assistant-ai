<?php
declare(strict_types=1);

namespace App\Services\OpenAi\Client;

use App\Entity\OpenAIPrompt;
use App\Repository\OpenAiPromptRepository;
use DateTime;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenAiClient
{
    public function __construct(
        protected readonly HttpClientInterface $openAiV1Client,
        protected readonly ParameterBagInterface $parameterBag,
        protected readonly Security $security,
        protected readonly OpenAiPromptRepository $openAiPromptRepository
    ) {}

    public function prompt(string $prompt): array
    {
        $apiKey = $this->parameterBag->get('openai_api_secret_key');

        $requestAt = new DateTime('now');
        $start = microtime(true);
        $modelName = 'gpt-4o-mini';
        $data = [
            'model' => $modelName, // cheapest
            'messages' => [
                [
                    "role" => "system",
                    "content" => "You are a helpful assistant."
                ],
                [
                    "role" => "user",
                    "content" => $prompt
                ],
            ],
        ];

        $response = $this->openAiV1Client->request(
            'POST',
            'chat/completions',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]
        );

        // Handle the response
        if ($response->getStatusCode() === 200) {
            $content = $response->toArray();

            $end = microtime(true);
            $promptRequestExecutionTime = (int) ($end - $start) * 1000;

            $openAiPrompt = new OpenAIPrompt();
            $openAiPrompt->setOwner($this->security->getUser());
            $openAiPrompt->setModelName($modelName);
            $openAiPrompt->setPromptMeta($data);
            $openAiPrompt->setRequestAt($requestAt);
            $openAiPrompt->setExecutionMilliseconds($promptRequestExecutionTime);
            $openAiPrompt->setPromptAnswer($content);
            $openAiPrompt->setIsDone(true);
            $openAiPrompt->setPromptHash(
                hash('sha256', trim($content['choices'][0]['message']['content'] ?? ''))
            );

            $this->openAiPromptRepository->add($openAiPrompt);
            $this->openAiPromptRepository->save();

            return $content;
        }

        $content = $response->getContent(false);

        throw new \Exception(
            'API request failed: ' . $response->getStatusCode()
            .
            '.'
            .
            $content
        );
    }

    public function convertPromptJsonAnswerToArray(array $data): array
    {
        $content = $this->convertPromptPlainTextAnswerToText($data);
        if (empty($content)) {
            dump('prompt answer content not found.');
            // TODO add logging

            return [];
        }

        $content = json_decode($content, true);

        if (json_last_error()) {
            dump(json_last_error_msg());
            // TODO add logging

            return [];
        }

        return $content;
    }

    public function convertPromptPlainTextAnswerToText(array $data): ?string
    {
        $content = $data['choices'][0]['message']['content'] ?? null;
        if (empty($content)) {
            dump('prompt answer content not found.');
            // TODO add logging

            return null;
        }

        if (!is_string($content)) {
            dump('Content is not a string');
            // TODO add logging

            return null;
        }

        return trim($content);
    }
}
