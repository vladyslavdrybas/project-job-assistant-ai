<?php
declare(strict_types=1);

namespace App\Controller\AdminPanel;

use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use App\Entity\RequestCallBack;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route(
    "/ap/contact",
    name: "admin_panel_contact",
    requirements: [
        'requestCallBack' => RouteRequirements::UUID->value
    ]
)]
class ContactController extends AbstractAdminPanelController
{
    #[Route(
        '/request-call-back',
        '_request_call_back',
        methods: ['GET']
    )]
    public function index(

    ): ViewResponseDto {
        $requestCallBackRepository = $this->entityManager->getRepository(RequestCallBack::class);

        $rcb = $requestCallBackRepository->findAll(['createdAt', 'DESC']);

        $serialized = $this->serializer->serialize(
            $rcb,
            'json',
            [
                'groups' => ['base', 'ap-table'],
                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                    'updatedAt',
                ]
            ]
        );

        return $this->response(
            [
                'requestCallBacksJson' => $serialized,
            ],
            'admin-panel/contact.html.twig',
        );
    }
}
