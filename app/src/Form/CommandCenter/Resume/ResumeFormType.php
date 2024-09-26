<?php
declare(strict_types=1);

namespace App\Form\CommandCenter\Resume;

use App\DataTransferObject\Form\ResumeDto;
use App\Form\MediaCreatorFormType;
use App\Form\SwitchType;
use App\Form\TagsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
            ->add('title',
                TextType::class,
                [
                    'label' => 'Title your Resume',
                    'required' => false,
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->add('jobTitle',
                TextType::class,
                [
                    'required' => false,
                    'data' => $data->jobTitle,
                ]
            )
            ->add('photo',
                MediaCreatorFormType::class,
                [
                    'required' => false,
                ]
            )
            ->add('includePhoto',
                SwitchType::class,
                [
                    'label' => 'Show photo',
                    'required' => false,
                ]
            )
            ->add('firstName',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('lastName',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('contacts',
                ContactsFormType::class,
                [
                    'required' => false,
                ]
            )
            ->add('professionalSummary',
                TextareaType::class,
                [
                    'required' => false,
                    'help' => 'Write 2-4 short, energetic sentences about how great you are. Mention the role and what you did. What were the big achievements? Describe your motivation and list your skills. Recruiter tip: write 400-600 characters to increase interview chances'
                ]
            )
            ->add('employmentHistory',
                CollectionType::class,
                [
                    'entry_type' => EmploymentRecordFormType::class,
                    'entry_options' => [
                        'label' => false
                    ],
                    'required' => false,
                    'help' => 'Show your relevant experience (last 10 years). Use bullet points to note your achievements, if possible - use numbers/facts (Achieved X, measured by Y, by doing Z).'
                ]
            )
            ->add('educationHistory',
                CollectionType::class,
                [
                    'entry_type' => EducationRecordFormType::class,
                    'entry_options' => [
                        'label' => false
                    ],
                    'required' => false,
                    'help' => 'A varied education on your resume sums up the value that your learnings and background will bring to job.'
                ]
            )
            ->add('links',
                CollectionType::class,
                [
                    'label' => 'Websites & Social Links',
                    'entry_type' => LinkFormType::class,
                    'entry_options' => [
                        'label' => false
                    ],
                    'required' => false,
                    'help' => 'You can add links to websites you want hiring managers to see! Perhaps It will be  a link to your portfolio, LinkedIn profile, or personal website.'
                ]
            )
            ->add('skills',
                TagsType::class,
                [
                    'mapped' => true,
//                    'data' => $data?->skills ?? []
                ]
            )
            ->add('languages',
                CollectionType::class,
                [
                    'label' => 'Languages',
                    'entry_type' => LanguageFormType::class,
                    'entry_options' => [
                        'label' => false
                    ],
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ResumeDto::class,
        ]);
    }
}
