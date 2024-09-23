<?php
declare(strict_types=1);

namespace App\Security\OAuth;

use App\DataTransferObject\Security\OAuth2ResourceOwnerDto;
use App\Entity\User;
use App\Entity\UserLinkedIn;
use App\Exceptions\RegistrationClosed;
use App\Exceptions\SocialOwnerAndUserMismatch;
use DateTime;

class LinkedInAuthenticator extends BaseOAuthAuthenticator
{
    public const CONNECT_CHECK_ROUTE = 'security_linkedin_connect_check';
    public const OAUTH_CLIENT = 'linkedin_2024';

    protected function buildUserFromOAuthDto(OAuth2ResourceOwnerDto $dto): ?User
    {
        $doctrineChanges = false;

        $userLinkedInRepository = $this->entityManager->getRepository(UserLinkedIn::class);

        $user = $this->security->getUser();
        $userLinkedIn = $userLinkedInRepository->findOneBy(['oAuthId' => $dto->id]);
        if (!$userLinkedIn instanceof UserLinkedIn) {
            if (null === $user && !$this->parameterBag->get('security_is_register_open')) {
                throw new RegistrationClosed();
            }

            $user = $this->security->getUser() ?? $this->userBuilder->linkedin($dto);
            if (
                null == $user->getEmail()
                && null !== $dto->email
            ) {
                $user->setEmail($dto->email);
            }

            $isUserNew = (new DateTime())->diff($user->getCreatedAt())->s < 5;
            if ($isUserNew) {
                $userLinkedInRepository->add($user);
            }

            $locale = $dto->locale;
            if (is_array($locale)) {
                $locale = implode('_', $locale);
            }

            if (empty($locale)) {
                $locale = null;
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
        } else {
            if ($userLinkedIn->getOwner() !== $user && null !== $user) {
                throw new SocialOwnerAndUserMismatch();
            }

            $user = $userLinkedIn->getOwner();

            if (
                null == $user->getEmail()
                && null !== $dto->email
            ) {
                $doctrineChanges = true;
                $user->setEmail($dto->email);
            }
        }

        if ($doctrineChanges) {
            $userLinkedInRepository->save();
        }

        return $user;
    }
}
