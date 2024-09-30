<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\CoverLetter;

use App\DataTransferObject\Form\CoverLetterAiDto;
use App\DataTransferObject\Form\CoverLetterDto;
use App\DataTransferObject\Form\ResumeDto;
use App\Entity\Resume;
use App\EntityTransformer\ResumeTransformer;
use App\Repository\ResumeRepository;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AiCoverLetterFormType extends AbstractType
{
    public function __construct(
        protected ResumeRepository $resumeRepository,
        protected ResumeTransformer $transformer,
        protected UrlGeneratorInterface $urlGenerator,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var CoverLetterDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('coverLetter',
                SimpleCoverLetterFormType::class,
                [
                    'required' => false,
                ]
            )
        ;

        $query = $this->resumeRepository->createQueryBuilder('t')
            ->where('t.owner = :owner')
            ->andWhere('t.title IS NOT NULL')
            ->setParameter('owner', $dto->owner)
            ->orderBy('t.jobTitle', 'ASC')
            ->addOrderBy('t.updatedAt', 'DESC');

        $choices = $query->getQuery()->getResult();
        $choices = array_map(function (Resume $resume) {
            return $this->transformer->reverseTransform($resume);
        }, $choices);

        dump($choices);

        if (empty($choices)) {
            throw new Exception(
                sprintf(
                    'Not enough data. <a href="%s">Create</a> and fill your first resume.',
                    $this->urlGenerator->generate('cp_document_list', [], UrlGeneratorInterface::ABSOLUTE_URL)
                )
            );
        }

        $builder->add('resume',
                ChoiceType::class,
                [
                    'required' => false,
                    'multiple' => false,
                    'choices' => $choices,
                    'choice_label' => 'title',
                    'choice_value' => 'id',
                    'group_by' => function (ResumeDto $dto): string {
                        return $dto->jobTitle ?? 'Untitled';
                    },
                    'data' => $dto->resume ?? $choices[0],
                    'placeholder' => false,
                    'help' => 'Select a resume to feed data to AI.'
                ]
            )
            ->add('actionBtn',
                HiddenType::class,
                [
                    'mapped' => false,
                    'empty_data' => 'save'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoverLetterAiDto::class,
        ]);
    }
}
