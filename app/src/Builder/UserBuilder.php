<?php

declare(strict_types=1);

namespace App\Builder;

use App\DataTransferObject\Security\GoogleUserDto;
use App\Entity\User;
use App\Exceptions\AlreadyExists;
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
        protected readonly UserRepository $userRepository
    ) {}

    public function base(
        string $email,
        string $password,
        ?string $username = null
    ): User {
        $this->throwInvalidEmail($email);
        $this->throwIfExists($email);

        return $this->createWithoutValidation($email, $password, $username);
    }

    public function google(GoogleUserDto $dto): User
    {
        $this->throwInvalidEmail($dto->email);

        $user = $this->userRepository->findByEmail($dto->email);
        if ($user instanceof User) {
            return $user;
        }

        $user = $this->createWithoutValidation(
            $dto->email,
            $dto->email . microtime(),
        );

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
        string $email,
        string $password,
        ?string $username = null
    ): User {
        $user = new User();

        $user->setEmail(trim($email));
        $user->setPassword($this->hashPassword($user, $password));

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
