<?php
declare(strict_types=1);

namespace App\Security\OAuth;

use App\DataTransferObject\Security\OAuth2ResourceOwnerDto;
use App\Entity\User;
use App\Entity\UserGoogle;
use App\Exceptions\RegistrationClosed;
use App\Exceptions\SocialOwnerAndUserMismatch;
use DateTime;

class GoogleAuthenticator extends BaseOAuthAuthenticator
{
    public const CONNECT_CHECK_ROUTE = 'security_google_connect_check';
    public const OAUTH_CLIENT = 'google';

    protected function buildUserFromOAuthDto(OAuth2ResourceOwnerDto $dto): ?User
    {
        $doctrineChanges = false;

        $userGoogleRepository = $this->entityManager->getRepository(UserGoogle::class);

        $user = $this->security->getUser();
        $userGoogle = $userGoogleRepository->findOneBy(['oAuthId' => $dto->id]);
        if (!$userGoogle instanceof UserGoogle) {
            if (null === $user && !$this->parameterBag->get('security_is_register_open')) {
                throw new RegistrationClosed();
            }

            $user = $this->security->getUser() ?? $this->userBuilder->google($dto);
            if (
                null == $user->getEmail()
                && null !== $dto->email
            ) {
                $user->setEmail($dto->email);
            }

            $isUserNew = (new DateTime())->diff($user->getCreatedAt())->s < 5;
            if ($isUserNew) {
                $userGoogleRepository->add($user);
            }

            $locale = $dto->locale;
            if (is_array($locale)) {
                $locale = implode('_', $locale);
            }

            if (empty($locale)) {
                $locale = null;
            }

            $userGoogle = new UserGoogle();
            $userGoogle->setOwner($user);
            $userGoogle->setOAuthId($dto->id);
            $userGoogle->setEmail($dto->email);
            $userGoogle->setFullName($dto->name);
            $userGoogle->setFirstName($dto->firstName);
            $userGoogle->setLastName($dto->lastName);
            $userGoogle->setAvatar($dto->avatar);
            $userGoogle->setIsEmailVerified($dto->isEmailVerified);
            $userGoogle->setHostedDomain($dto->hostedDomain);
            $userGoogle->setLocale($locale);

            $doctrineChanges = true;
            $userGoogleRepository->add($userGoogle);
        } else {
            if (null !== $user && $userGoogle->getOwner()->getUserIdentifier() !== $user->getUserIdentifier()) {
                throw new SocialOwnerAndUserMismatch();
            }

            $user = $userGoogle->getOwner();

            if (
                null == $user->getEmail()
                && null !== $dto->email
            ) {
                $doctrineChanges = true;
                $user->setEmail($dto->email);
            }
        }

        if ($doctrineChanges) {
            $userGoogleRepository->save();
        }

        return $user;
    }
}
