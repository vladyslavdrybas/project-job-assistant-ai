<?php
declare(strict_types=1);

namespace App\DataTransferObject;

use Symfony\Component\HttpFoundation\Response;

readonly class ViewResponseDto
{
    public function __construct(
        public array   $data = [],
        public ?string $template = null,
        public int     $statusCode = Response::HTTP_OK,
        public array   $headers = [],
    ) {}
}
