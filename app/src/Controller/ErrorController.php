<?php
declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\ViewResponseDto;
use App\Exceptions\AlreadyExists;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function show(Exception $exception): ViewResponseDto
    {
        $message = 'Hm... Do you really want to try this page?';
        $code = 400;
        if ($exception->getCode() > 0) {
            $code = $exception->getCode();
        } elseif (method_exists($exception, 'getStatusCode')) {
            $code = $exception->getStatusCode();
        }

        if ($code === Response::HTTP_BAD_REQUEST) {
            $message = $exception->getMessage();
        } elseif ($code === Response::HTTP_BAD_GATEWAY) {
            $message = 'Server Error';
        } elseif ($code === Response::HTTP_NOT_FOUND) {
            $message = 'Page not found';
        }

        return $this->response(
            [
                'exception' => [
                    'message' => $message,
                    'code' => $code,
                ],
            ],
            '_error/error.html.twig',
        );
    }
}
