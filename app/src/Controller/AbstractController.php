<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\ViewResponseDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractController extends SymfonyAbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected UrlGeneratorInterface $urlGenerator,
        protected SerializerInterface $serializer,
        protected string $projectDir,
        protected string $projectEnvironment
    ) {}

    protected function getUser(): ?User
    {
        $user = parent::getUser();

        if (null === $user) {
            return null;
        }

        return $this->entityManager->getRepository(User::class)->loadUserByIdentifier($user->getUserIdentifier());
    }

    protected function response(
        array   $data = [],
        ?string $template = null,
        int     $statusCode = Response::HTTP_OK,
        array   $headers = []
    ): ViewResponseDto {
        return new ViewResponseDto(
            $data,
            $template,
            $statusCode,
            $headers
        );
    }

    protected function closeFromPublic(): void
    {
        if (!$this->getParameter('security_is_register_open')) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
    }
}
