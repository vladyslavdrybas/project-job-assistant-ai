<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Job;

use App\DataTransferObject\Form\Job\JobDto;
use App\Entity\CoverLetter;
use App\Entity\Resume;
use App\Form\CommandCenter\Resume\ContactPersonFormType;
use App\Form\CommandCenter\Resume\EmployerFormType;
use App\Form\CommandCenter\Resume\LocationFormType;
use App\Form\TagsType;
use App\Repository\CoverLetterRepository;
use App\Repository\ResumeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobFormType extends AbstractType
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly ResumeRepository $resumeRepository,
        protected readonly CoverLetterRepository $coverLetterRepository,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var JobDto $data */
        $data = $builder->getData();
        $owner = $data->owner;

        $resumes = $this->resumeRepository->createQueryBuilder('t')
            ->where('t.owner = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('t.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        $resumeChoices = [];
        array_map(function(Resume $resume) use (&$resumeChoices) {
            $title = $resume->getTitle() ?? '';

            if (empty($title)) {
                $title = 'Untitled .. ' . substr($resume->getRawId(), -5);
            }

            $resumeChoices[$title] = $resume->getRawId();
        }, $resumes);

        $coverLetters = $this->coverLetterRepository->createQueryBuilder('t')
            ->where('t.owner = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('t.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        $coverLetterChoices = [];
        array_map(function(CoverLetter $coverLetter) use (&$coverLetterChoices) {
            $title = $coverLetter->getTitle() ?? '';

            if (empty($title)) {
                $title = 'Untitled .. ' . substr($coverLetter->getRawId(), -5);
            }

            $coverLetterChoices[$title] = $coverLetter->getRawId();
        }, $coverLetters);

        $builder
            ->add('title',
                TextType::class,
                [
                    'label' => 'Job Title',
                    'required' => true,
                    'help' => 'Usually it is job position name.',
                ]
            )
            ->add('aboutPage',
                TextType::class,
                [
                    'required' => false,
                    'help' => 'Link to the page where job description is.',
                ]
            )
            ->add('formats',
                JobFormatsFormType::class,
                [
                    'required' => false,
                    'label' => 'Job formats',
                    'attr' => [
                        'class' => 'd-flex flex-row flex-wrap justify-content-start align-items-baseline',
                    ]
                ]
            )
            ->add('location',
                LocationFormType::class,
                [
                    'required' => false,
                    'label' => 'Location details',
                ]
            )
            ->add('employer',
                EmployerFormType::class,
                [
                    'required' => false,
                    'help' => 'Hiring company.',
                ]
            )
            ->add('contactPerson',
                ContactPersonFormType::class,
                [
                    'required' => false,
                    'help' => 'Hiring contact person.',
                ]
            )
            ->add('skills',
                TagsType::class,
                [
                    'mapped' => true,
                    'label' => 'Required skills as you understand.'
                ]
            )
            ->add('salary',
                JobSalaryFormType::class,
                [
                    'required' => false,
                    'label' => 'Salary',
                ]
            )
            ->add('resumeId',
                ChoiceType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'choices' => $resumeChoices,
                    'data' => $data->resume->id ?? null,
                    'placeholder' => 'None',
                ]
            )
            ->add('coverLetterId',
                ChoiceType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'choices' => $coverLetterChoices,
                    'data' => $data->coverLetter->id ?? null,
                    'placeholder' => 'None',
                ]
            )
            ->add('content',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Job Description',
                    'help' => 'Add full text of the job. Ideally, do not change content of the job description.',
                ]
            )
            ->add('estimateContent',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Estimate description',
                    'help' => 'Description of how do I fit to the company and how company fit to me.',
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
            'data_class' => JobDto::class,
            'allow_extra_fields' => true,
        ]);
    }
}
