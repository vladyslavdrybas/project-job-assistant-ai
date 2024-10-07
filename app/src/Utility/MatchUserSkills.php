<?php
declare(strict_types=1);

namespace App\Utility;

use App\Entity\Skill;

class MatchUserSkills
{
    public function __invoke(array $mySkills, array $otherSkills): array
    {
        return $this->match($mySkills, $otherSkills);
    }

    /**
     * @param array $mySkills
     * @param array $otherSkills
     * @return array
     */
    protected function match(array $mySkills, array $otherSkills): array
    {
        $matchHashTable = new FilterHashMap();

        $mySkills = array_map(function(Skill $skill) use ($matchHashTable) {
            return [
                'id' => $skill->getId(),
                'title' => $skill->getTitle(),
                'key' => $matchHashTable->hashCode($skill->getTitle()),
            ];
        }, $mySkills);

        foreach ($mySkills as $skill) {
            $matchHashTable->put($skill['title'], false);
        }

        $otherSkills = array_map(
            function(string $skill) use ($matchHashTable) {
                return [
                    'title' => $skill,
                    'match' => $matchHashTable->has($skill),
                    'key' => $matchHashTable->hashCode($skill),
                ];
            },
            $otherSkills
        );

        foreach ($otherSkills as $skill) {
            $matchHashTable->put($skill['title'], $skill['match']);
        }

        $skillsMatched = $matchHashTable->countPositive();

        return [
            'mySkills' => $mySkills,
            'otherSkills' => $otherSkills,
            'skillsMatched' => $skillsMatched,
        ];
    }
}
