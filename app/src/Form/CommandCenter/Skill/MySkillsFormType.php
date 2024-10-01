<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Skill;

use App\Form\TagsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MySkillsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();

        $builder->add(
                'skills',
                TagsType::class,
                [
                    'label' => 'My Skills',
                    'data' => $data
                ]
            )
        ;
    }
}
