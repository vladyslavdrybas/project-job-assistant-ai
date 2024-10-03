<?php
declare(strict_types=1);

namespace App\Services\Skills\Writer;

use App\Entity\Skill;
use App\Entity\UserInterface;
use App\Repository\SkillRepository;

class UserSkillsWriter
{
    public function __construct(
        protected readonly SkillRepository $skillRepository
    ) {}

    public function write(UserInterface $user, array $skills): void
    {
        $skillsToAdd = array_unique($skills);

        foreach ($skillsToAdd as $skillToAdd) {
            if (empty($skillToAdd)) {
                continue;
            }

            $skill = $this->skillRepository->findOneBy(['title' => $skillToAdd]);
            if (!$skill instanceof Skill) {
                $skill = new Skill();
                $skill->setTitle($skillToAdd);
                $this->skillRepository->add($skill);
            }
            $user->addSkill($skill);
        }

        $this->skillRepository->add($user);
        $this->skillRepository->save();
    }
}
