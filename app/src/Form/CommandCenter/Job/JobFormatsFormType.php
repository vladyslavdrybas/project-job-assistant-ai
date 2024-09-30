<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Job;

use App\Constants\Job\JobFormats;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class JobFormatsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = JobFormats::values();

        foreach($choices as $format) {
            $builder->add($format,
                SwitchType::class,
                [
                    'label' => ucfirst($format),
                ]
            );
        }
    }
}
