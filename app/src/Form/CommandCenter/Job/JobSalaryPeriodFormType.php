<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Job;

use App\Constants\Job\JobFormats;
use App\Constants\Job\JobSalaryPeriod;
use App\DataTransformer\SwitchEnumFormTransformer;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class JobSalaryPeriodFormType extends AbstractType
{
    public function __construct(
        protected readonly SwitchEnumFormTransformer $transformer
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->transformer);

        $choices = JobSalaryPeriod::array();

        foreach($choices as $name => $value) {
            $builder->add(strtolower($name),
                SwitchType::class,
                [
                    'label' => ucfirst($value),
                ]
            );
        }
    }
}
