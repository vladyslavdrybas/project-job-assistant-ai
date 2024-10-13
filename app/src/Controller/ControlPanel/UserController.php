<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\UserBuilder;
use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\User;
use App\Entity\UserGoogle;
use App\Entity\UserLinkedIn;
use App\Exceptions\AccessDenied;
use App\Form\CommandCenter\Profile\UserBiographyEditFormType;
use App\Form\CommandCenter\Profile\UserEditForm;
use App\Form\CommandCenter\Profile\UserPasswordChangeForm;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\ValueResolver\UserValueResolver;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/u/{user}",
    name: "cp_user",
    requirements: [
        'user' => RouteRequirements::USER_ALIAS->value
    ]
)]
class UserController extends AbstractControlPanelController
{
    #[Route(path: '', name: '_show', methods: ['GET'])]
    public function show(
        #[ValueResolver(UserValueResolver::class)] User $user
    ): ViewResponseDto {
        $googleUsers = $this->entityManager->getRepository(UserGoogle::class)->findBy(['owner' => $user]);
        $linkedinUsers = $this->entityManager->getRepository(UserLinkedIn::class)->findBy(['owner' => $user]);

        return $this->response(
            [
                'user' => $user,
                'googleUsers' => $googleUsers,
                'linkedinUsers' => $linkedinUsers,
            ],
            'control-panel/user/show.html.twig'
        );
    }

    #[Route(
        path: '/{socialType}/{socialId}/disconnect',
        name: '_social_disconnect',
        requirements: [
            'social' => 'google|linkedin',
            'socialId' => RouteRequirements::UUID->value
        ],
        methods: ['GET']
    )]
    public function socialDisconnect(
        #[ValueResolver(UserValueResolver::class)] User $user,
        string $socialType,
        string $socialId
    ): ViewResponseDto {
        if ($user !== $this->getUser()) {
            throw new AccessDenied();
        }

        $social = match ($socialType) {
            'google' => $this->entityManager->getRepository(UserGoogle::class)->findOneBy(['owner' => $user, 'id' => $socialId]),
            'linkedin' => $this->entityManager->getRepository(UserLinkedIn::class)->findOneBy(['owner' => $user, 'id' => $socialId]),
            default => throw new BadRequestHttpException("Unknown social type '$socialType'"),
        };

        if (null === $social) {
            throw new NotFoundHttpException("Not found social #$socialId.");
        }

        $this->entityManager->remove($social);
        $this->entityManager->flush();

        return $this->response(
            [
                'user' => $user->getUsername(),
            ],
            'cp_user_show'
        );
    }

    #[Route(path: '/edit', name: '_edit', methods: ['GET','POST'])]
    public function edit(
        #[ValueResolver(UserValueResolver::class)] User $user,
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        UserBuilder $userBuilder
    ): ViewResponseDto {
        $emailBuffer = $user->getEmail();

        $userPasswordChangeForm = $this->createForm(UserPasswordChangeForm::class, $user);
        $userPasswordChangeForm->handleRequest($request);

        $userEditForm = $this->createForm(UserEditForm::class, $user);
        $userEditForm->handleRequest($request);

        try {
            if ($userEditForm->isSubmitted() && $userEditForm->isValid()) {
                if ($emailBuffer !== $user->getEmail()) {
                    $user->setIsEmailVerified(false);
                }

                if (strlen($user->getUsername()) < 3 || strlen($user->getUsername()) > 100) {
                    throw new BadRequestHttpException('Username must be between 3 and 100 characters long');
                }

                if (null !== $user->getFirstname() && strlen($user->getFirstname()) > 100) {
                    throw new BadRequestHttpException('First name must be less than 100 characters long');
                }

                if (null !== $user->getLastname() && strlen($user->getLastname()) > 100) {
                    throw new BadRequestHttpException('Last name must be less than 100 characters long');
                }

                $userRepository->add($user);
                $userRepository->save();

                // sendPasswordChangeEmail
                $this->addFlash('success', 'Data changed.');
            }
        } catch (BadRequestHttpException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        try {
            if ($userPasswordChangeForm->isSubmitted() && $userPasswordChangeForm->isValid()) {
//                $currentPassword = trim($userPasswordChangeForm->get('currentPassword')->getData());
                $newPassword = trim($userPasswordChangeForm->get('newPassword')->getData());
                $confirmPassword = trim($userPasswordChangeForm->get('confirmPassword')->getData());

                if ($newPassword !== $confirmPassword) {
                    throw new BadRequestHttpException('Passwords do not match');
                }

//                if(!$userPasswordHasher->isPasswordValid($user, $currentPassword)) {
//                    throw new BadRequestHttpException('Bad credentials');
//                }

//                if ($currentPassword !== $newPassword) {
                    $user->setPassword($userBuilder->hashPassword($user, $newPassword));

                    $userRepository->add($user);
                    $userRepository->save();
//                }

                // sendPasswordChangeEmail
                $this->addFlash('success', 'Password changed.');
            }
        } catch (BadRequestHttpException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->response(
            [
                'user' => $user,
                'userPasswordChangeForm' => $userPasswordChangeForm,
                'userEditForm' => $userEditForm,
            ],
            'control-panel/user/edit.html.twig'
        );
    }

    #[Route(path: '/verify/email', name: '_verify_email', methods: ['GET'])]
    public function sendVerificationEmail(
        #[ValueResolver(UserValueResolver::class)] User $user,
        EmailVerifier $emailVerifier
    ): RedirectResponse {
        if (!$user->isEmailVerified()) {
            $this->sendValidationEmail($user, $emailVerifier);
        }

        return $this->redirectToRoute('cp_user_show', ['user' => $user->getUsername()]);
    }

    #[Route(
        '/biography',
        name: '_biography',
        methods: ['GET', 'POST']
    )]
    public function editBiography(
        Request $request,
    ): ViewResponseDto {
        $user = $this->getUser();
        $form = $this->createForm(UserBiographyEditFormType::class, [
            'biography' => $user->getBiography(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $actionBtn = $form->get('actionBtn')->getData();

            $user->setBiography($data['biography']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            if ('view' === $actionBtn) {
                return $this->response(
                    [
                        'user' => $user->getUsername(),
                    ],
                    'cp_user_show'
                );
            }
        }

        return $this->response(
            [
                'form' => $form,
                'formActions' => ['save', 'view'],
            ],
            'control-panel/user/biography-edit.html.twig'
        );
    }

    protected function sendValidationEmail(User $user, EmailVerifier $emailVerifier): void
    {
        // generate a signed url and email it to the user
        $emailVerifier->sendEmailConfirmation('security_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('noreply@prototyper.com', 'Prototyper Info'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('_notification/email/confirmation_email.html.twig')
        );
    }
}
