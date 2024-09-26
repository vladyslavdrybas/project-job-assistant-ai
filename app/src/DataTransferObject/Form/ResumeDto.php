<?php
declare(strict_types=1);

namespace App\DataTransferObject\Form;

// Job Title
// First Name
// Last Name
// Email
// Phone
// Country
// City
// Address
// Postal Code

// Driving License
// Nationality
// Place Of Birth
// Date Of Birth
// Language Of Resume

// Professional Summary

// Employment History -- array

// Education -- array

// Websites & Social links -- array

// Skills -- array:tags

// references/testimonials -- array
// courses -- array
// Languages -- array
// hobbies-- array
// internships -- array

use App\DataTransferObject\Form\Contact\ContactsDto;
use App\DataTransferObject\Form\EducationHistory\EducationRecordDto;
use App\DataTransferObject\Form\EmploymentHistory\EmploymentRecordDto;
use App\DataTransferObject\Form\PetProjects\PetProjectDto;
use App\DataTransferObject\IDataTransferObject;
use App\Entity\UserInterface;

class ResumeDto implements IDataTransferObject
{
    public function __construct(
        public ?string $title = null,
        public ?UserInterface $owner = null,
        public ?string $language = null,
        public ?MediaCreatorFormDto $photo = null,
        public bool $includePhoto = true,
        public ?string $jobTitle = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?ContactsDto $contacts = null,
        public ?string $professionalSummary = null,

        /** @var array<EmploymentRecordDto> $employmentHistory*/
        public array $employmentHistory = [],

        /** @var array<EducationRecordDto> $educationHistory*/
        public array $educationHistory = [],

        /** @var array<PetProjectDto> $petProjects*/
        public array $petProjects = [],

        /** @var array<string> $skills*/
        public array $skills = [],

        /** @var array<LanguageDto> $languages*/
        public array $languages = [],

        /** @var array<LinkDto> $links*/
        public array $links = []
    ){}
}
