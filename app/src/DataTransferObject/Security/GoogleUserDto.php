<?php
declare(strict_types=1);

namespace App\DataTransferObject\Security;

use Symfony\Component\Serializer\Attribute\SerializedPath;

class GoogleUserDto
{
    public function __construct(
        #[SerializedPath('[sub]')]
        public string $id,
        public string $name,
        #[SerializedPath('[given_name]')]
        public string $firstName,
        #[SerializedPath('[family_name]')]
        public string $lastName,
        #[SerializedPath('[picture]')]
        public string $avatar,
        public string $email,
        #[SerializedPath('[email_verified]')]
        public bool $emailVerified = false,
        public ?string $locale = null,
        #[SerializedPath('[hd]')]
        public ?string $hostedDomain = null,
    ) {}
}
