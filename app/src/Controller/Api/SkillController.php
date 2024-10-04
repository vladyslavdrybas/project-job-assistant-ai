<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\DataTransferObject\ViewResponseDto;
use App\Entity\Skill;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/api/skill",
    name: "api_skill"
)]
class SkillController extends AbstractApiController
{
    #[Route(
        '{skill}/remove',
        name: '_remove',
        methods: ['GET']
    )]
    public function list(
        Skill $skill
    ): ViewResponseDto {
        $user = $this->getUser();
        $user->removeFilterSkill($skill);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->response(
            [
                'message' => 'Skill detached from user.',
                'skill' => [
                    'id' => $skill->getId(),
                    'title' => $skill->getTitle(),
                ],
            ]
        );
    }
}
