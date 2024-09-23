<?php
declare(strict_types=1);

namespace App\DataTransferObject\Security;

use Symfony\Component\Serializer\Attribute\SerializedPath;

class OAuth2ResourceOwnerDto
{
    public function __construct(
        #[SerializedPath('[sub]')]
        public string $id,
        public ?string $email = null,
        public ?string $name = null,
        #[SerializedPath('[given_name]')]
        public ?string $firstName = null,
        #[SerializedPath('[family_name]')]
        public ?string $lastName = null,
        #[SerializedPath('[picture]')]
        public ?string $avatar = null,
        #[SerializedPath('[email_verified]')]
        public bool $isEmailVerified = false,
        public string|array $locale = [],
        #[SerializedPath('[hd]')]
        public ?string $hostedDomain = null,
    ) {}
}
