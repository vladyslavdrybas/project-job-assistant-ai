<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Job;

use App\Constants\Job\JobSalaryPeriod;
use App\DataTransformer\SwitchEnumFormTransformer;
use App\Form\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class JobSalaryPeriodFormType extends AbstractType
{
    public function __construct(
        protected readonly SwitchEnumFormTransformer $modelTransformer
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->modelTransformer);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData() ?? [];
            foreach($data as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                    $event->setData([$key => $value]);
                }
                return;
            }
        });

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
