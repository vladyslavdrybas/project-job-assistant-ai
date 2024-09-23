<?php
declare(strict_types=1);

namespace App\Security\OAuth;

use App\DataTransferObject\Security\OAuth2ResourceOwnerDto;
use App\Entity\User;
use App\Entity\UserGoogle;
use App\Exceptions\RegistrationClosed;
use DateTime;

class GoogleAuthenticator extends BaseOAuthAuthenticator
{
    public const CONNECT_CHECK_ROUTE = 'security_google_connect_check';
    public const OAUTH_CLIENT = 'google';

    protected function buildUserFromOAuthDto(OAuth2ResourceOwnerDto $dto): ?User
    {
        $doctrineChanges = false;

        $userGoogleRepository = $this->entityManager->getRepository(UserGoogle::class);

        $user = $this->userBuilder->google($dto);

        $isUserNew = (new DateTime())->diff($user->getCreatedAt())->s < 5;
        if ($isUserNew) {
            $doctrineChanges = true;
            $userGoogleRepository->add($user);
        }

        $userGoogle = $userGoogleRepository->findOneBy(['oAuthId' => $dto->id]);
        if (!$userGoogle instanceof UserGoogle) {
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
        }

        if ($doctrineChanges) {
            if (!$this->parameterBag->get('security_is_register_open')) {
                throw new RegistrationClosed();
            }

            $userGoogleRepository->save();
        }

        return $user;
    }
}
