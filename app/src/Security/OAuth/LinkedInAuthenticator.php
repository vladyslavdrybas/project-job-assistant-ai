<?php
declare(strict_types=1);

namespace App\Security\OAuth;

use App\DataTransferObject\Security\OAuth2ResourceOwnerDto;
use App\Entity\User;
use App\Entity\UserLinkedIn;
use App\Exceptions\RegistrationClosed;
use DateTime;

class LinkedInAuthenticator extends BaseOAuthAuthenticator
{
    public const CONNECT_CHECK_ROUTE = 'security_linkedin_connect_check';
    public const OAUTH_CLIENT = 'linkedin_2024';

    protected function buildUserFromOAuthDto(OAuth2ResourceOwnerDto $dto): ?User
    {
        $doctrineChanges = false;

        $userLinkedInRepository = $this->entityManager->getRepository(UserLinkedIn::class);

        $user = $this->userBuilder->linkedin($dto);

        $isUserNew = (new DateTime())->diff($user->getCreatedAt())->s < 5;
        if ($isUserNew) {
            $doctrineChanges = true;
            $userLinkedInRepository->add($user);
        }

        $userLinkedIn = $userLinkedInRepository->findOneBy(['oAuthId' => $dto->id]);
        if (!$userLinkedIn instanceof UserLinkedIn) {
            $locale = $dto->locale;
            if (is_array($locale)) {
                $locale = implode('_', $locale);
            }

            $userLinkedIn = new UserLinkedIn();
            $userLinkedIn->setOwner($user);
            $userLinkedIn->setOAuthId($dto->id);
            $userLinkedIn->setEmail($dto->email);
            $userLinkedIn->setFullName($dto->name);
            $userLinkedIn->setFirstName($dto->firstName);
            $userLinkedIn->setLastName($dto->lastName);
            $userLinkedIn->setAvatar($dto->avatar);
            $userLinkedIn->setIsEmailVerified($dto->isEmailVerified);
            $userLinkedIn->setHostedDomain($dto->hostedDomain);
            $userLinkedIn->setLocale($locale);

            $doctrineChanges = true;
            $userLinkedInRepository->add($userLinkedIn);
        }

        if ($doctrineChanges) {
            if (!$this->parameterBag->get('security_is_register_open')) {
                throw new RegistrationClosed();
            }

            $userLinkedInRepository->save();
        }

        return $user;
    }
}
