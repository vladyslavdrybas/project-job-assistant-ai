<?php
declare(strict_types=1);

namespace App\Services\Skills\Writer;

use App\Entity\Resume;
use App\Entity\Skill;
use App\Repository\SkillRepository;

class ResumeSkillsWriter
{
    public function __construct(
        protected readonly SkillRepository $skillRepository
    ) {}

    public function write(Resume $resume, array $skills): void
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
            $resume->addFilterSkill($skill);
        }

        $this->skillRepository->add($resume);
        $this->skillRepository->save();
    }
}
