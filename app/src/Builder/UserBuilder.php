<?php

declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\Security\OAuth2ResourceOwnerDto;
use App\Entity\User;
use App\Entity\UserGoogle;
use App\Entity\UserLinkedIn;
use App\Exceptions\AlreadyExists;
use App\Repository\UserGoogleRepository;
use App\Repository\UserLinkedInRepository;
use App\Repository\UserRepository;
use App\Utility\RandomGenerator;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function strlen;

class UserBuilder implements IEntityBuilder
{
    public const VALIDATE_EMAIL_MIN_LENGTH = 6;
    public const VALIDATE_USERNAME_MIN_LENGTH = 6;
    public const VALIDATE_USERNAME_MAX_LENGTH = 100;

    public function __construct(
        protected readonly UserPasswordHasherInterface $passwordHasher,
        protected readonly RandomGenerator $randomGenerator,
        protected readonly UserRepository $userRepository,
        protected readonly UserGoogleRepository $googleRepository,
        protected readonly UserLinkedInRepository $userLinkedInRepository
    ) {}

    public function base(
        string $email,
        string $password,
        ?string $username = null
    ): User {
        $this->throwInvalidEmail($email);
        $this->throwIfExists($email);

        $user = $this->initUser();
        $user->setEmail(trim($email));

        $this->setUsername($user, $username);
        $this->setPassword($user, $password);

        return $this->createWithoutValidation($password, $email, $username);
    }

    public function google(OAuth2ResourceOwnerDto $dto): User
    {
        if (null !== $dto->email) {
            $this->throwInvalidEmail($dto->email);
        }

        $userGoogle = $this->googleRepository->findOneBy(['oAuthId' => $dto->id]);
        if ($userGoogle instanceof UserGoogle) {
            $user = $userGoogle->getOwner();
        } else {
            $user = $this->createWithoutValidation(
                bin2hex(random_bytes(3)) . substr($dto->id, 0, 10),
                $dto->email
            );
        }

        $user->setFirstName($dto->firstName);
        $user->setLastname($dto->lastName);

        return $user;
    }

    public function linkedin(OAuth2ResourceOwnerDto $dto): User
    {
        if (null !== $dto->email) {
            $this->throwInvalidEmail($dto->email);
        }

        $userLinkedIn = $this->userLinkedInRepository->findOneBy(['oAuthId' => $dto->id]);
        if ($userLinkedIn instanceof UserLinkedIn) {
            $user = $userLinkedIn->getOwner();
        } else {
            $user = $this->createWithoutValidation(
                bin2hex(random_bytes(3)) . substr($dto->id, 0, 10),
                $dto->email
            );
        }

        $user->setFirstName($dto->firstName);
        $user->setLastname($dto->lastName);

        return $user;
    }

    public function hashPassword(User $user, string $password): string
    {
        return $this->passwordHasher->hashPassword(
            $user,
            trim($password)
        );
    }

    protected function createWithoutValidation(
        string $password,
        string $email = null,
        ?string $username = null
    ): User {
        $user = new User();

        if (null !== $email) {
            $user->setEmail(trim($email));
        }

        $this->setUsername($user, $username);

        $user->setPassword($this->hashPassword($user, $password));

        return $user;
    }

    protected function initUser(): User
    {
        return new User();
    }

    protected function setUsername(User $user, ?string $username = null): User
    {
        if (
            null === $username
            || strlen($username) > static::VALIDATE_USERNAME_MAX_LENGTH
            || strlen($username) < static::VALIDATE_USERNAME_MIN_LENGTH
            || null !== $this->userRepository->findOneBy(['username' => $username])
        ) {
            $rndGen = new RandomGenerator();
            $username = $rndGen->uniqueId('u');
        }
        $user->setUsername($username);

        return $user;
    }

    protected function setPassword(User $user, string $password): User
    {
        $user->setPassword($this->hashPassword($user, $password));

        return $user;
    }

    protected function throwIfExists(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user instanceof User) {
            throw new AlreadyExists('Such a user already exists.');
        }
    }

    protected function throwInvalidEmail(string $email): void
    {
        if (strlen($email) < static::VALIDATE_EMAIL_MIN_LENGTH) {
            throw new InvalidArgumentException('Invalid email length. Expect string length greater than 5.');
        }
    }
}
