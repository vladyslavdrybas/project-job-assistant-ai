<?php
declare(strict_types=1);

namespace App\DataTransferObject\Security;

use Symfony\Component\Serializer\Attribute\SerializedPath;

class GoogleUserDto
{
    public function __construct(
        #[SerializedPath('[sub]')]
        public string $id,
        public string $email,
        public string $name,
        #[SerializedPath('[given_name]')]
        public string $firstName,
        #[SerializedPath('[family_name]')]
        public string $lastName,
        #[SerializedPath('[picture]')]
        public string $avatar,
        #[SerializedPath('[email_verified]')]
        public bool $isEmailVerified = false,
        public ?string $locale = null,
        #[SerializedPath('[hd]')]
        public ?string $hostedDomain = null,
    ) {}
}
