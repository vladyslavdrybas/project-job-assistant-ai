<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Achievement;

use App\DataTransferObject\Form\Achievement\AchievementDto;
use App\Entity\Achievement;
use App\Entity\Employment;
use App\Form\SkillTagsType;
use App\Repository\EmploymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AchievementFormType extends AbstractType
{
    public function __construct(
       protected readonly EntityManagerInterface $entityManager,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var AchievementDto $data */
        $data = $builder->getData();
        $owner = $data->owner;
        $employment = null;
        if (null !== $data->employment && null !== $data->employment->employmentId) {
            $employment = $this->entityManager->find(Employment::class, $data->employment->employmentId);
        }

        $builder->add('title',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Title your Achievement.',
                    'help' => 'Be concrete and short.',
                    'data' => $data->title,
                ]
            )->add('description',
                TextareaType::class,
                [
                    'required' => false,
                    'data' => $data->description,
                    'help' => 'Describe your achievement. Also add impact of your achievement on the project. Use present time. Max length: ' . Achievement::CONSTRAINT_DESCRIPTION_MAX_LENGTH,
                    'constraints' => [
                        new Length(['max' => Achievement::CONSTRAINT_DESCRIPTION_MAX_LENGTH]),
                    ],
                ]
            )->add('doneAt',
                DateType::class,
                [
                    'required' => false,
                    'widget' => 'single_text',
                    'input'  => 'datetime_immutable',
                    'html5' => true,
                    'data' => $data->doneAt,
                    'constraints' => [
                    ],
                ]
            )->add(
                'skills',
                SkillTagsType::class,
                [
                    'label' => 'My Skills',
                    'data' => $data->skills,
                    'mapped' => true,
                    'help' => 'List skills that you use or learn.',
                ]
            )->add('employment',
                EntityType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'class' => Employment::class,
                    'query_builder' => function (EmploymentRepository $repo) use ($owner) {
                        return $repo->createQueryBuilder('t')
                            ->where('t.owner = :owner')
                            ->setParameter('owner', $owner)
                            ->orderBy('t.endDate', 'DESC')
                        ;
                    },
                    'choice_label' => function (Employment $employment) {
                        $title = '';
                        if (null !== $employment->getEmployer()?->title) {
                            $title .= $employment->getEmployer()->title;
                        }

                        if (null !== $employment->getJobTitle()) {
                            if (!empty($title)) {
                                $title .= ' as ';
                            }
                            $title .= $employment->getJobTitle();
                        }

                        if (empty($title)) {
                            $title = $employment->getRawId();
                        }

                        return $title;
                    },
                    'data' => $employment,
                    'placeholder' => 'None',
                ]
            )->add('actionBtn',
                HiddenType::class,
                [
                    'mapped' => false,
                    'empty_data' => 'save',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AchievementDto::class,
            'allow_extra_fields' => true,
        ]);
    }
}
