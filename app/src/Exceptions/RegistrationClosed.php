<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

class RegistrationClosed extends AuthenticationException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Registration is temporary closed.';
        }

        parent::__construct($message, $code, $previous);
    }
}
