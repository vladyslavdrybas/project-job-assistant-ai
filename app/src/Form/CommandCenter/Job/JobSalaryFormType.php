<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Job;

use App\DataTransferObject\Form\Job\SalaryDto;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;

class JobSalaryFormType extends AbstractType
{
    protected function strToInt(string $value): ?int
    {
        if (empty($value)) {
            return null;
        }
        return (int) $value;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (isset($data['min'])) {
                $data['min'] = $this->strToInt($data['min']);
            }
            if (isset($data['max'])) {
                $data['max'] = $this->strToInt($data['max']);
            }

            if ($data['min'] !== null && $data['max'] !== null) {
                if ($data['min'] > $data['max']) {
                    $data['min'] = $data['max'];
                }
            }

            $event->setData($data);
        });

        $builder
            ->add('min',
                NumberType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Type('number'),
                    ]
                ]
            )
            ->add('max',
                NumberType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Type('number'),
                    ]
                ]
            )
            ->add('period',
                JobSalaryPeriodFormType::class,
                [
                    'label' => 'Salary period',
                    'required' => false,
                    'attr' => [
                        'class' => 'd-flex flex-row flex-wrap justify-content-start align-items-baseline',
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SalaryDto::class,
        ]);
    }
}
