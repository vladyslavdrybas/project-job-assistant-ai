<?php
declare(strict_types=1);

namespace App\Form;

class SkillTagsType extends TagsType
{
    public const REPLACE_TEMPLATE = '[^-\w\s\.:_#+&]+';
}
