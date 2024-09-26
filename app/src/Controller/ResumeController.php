<?php
declare(strict_types=1);

namespace App\Controller;

use App\Constants\RouteRequirements;
use App\DataTransferObject\ViewResponseDto;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/resume",
    name: "app_resume",
    requirements: [
        'resume' => RouteRequirements::USER_SHA256->value
    ]
)]
class ResumeController extends AbstractController
{
    #[Route(
        "/",
        name: "_show",
        methods: ["GET"]
    )]
    public function show(): ViewResponseDto
    {
        return $this->response(
            [
                'isPrintPdf' => false,
                'data' => $this->getResumeData(),
            ],
            'resume/index.html.twig'
        );
    }

    #[Route(
        "/print/pdf",
        name: "_print_pdf",
        methods: ["GET"]
    )]
    public function printPdf(
        Pdf $pdf
    ): BinaryFileResponse {

        $data = $this->getResumeData();

        $pdf->setOptions([
//            'grayscale' => true,
            'orientation' => 'portrait',
            'enable-local-file-access' => true,
            'enable-internal-links' => true,
            'enable-external-links' => true,
            'viewport-size' => '1280x1024',
//            'viewport-size' => '1480x4024',

            "margin-bottom" => 8,
            "margin-left" => 3,
            "margin-right" => 3,
            "margin-top" => 8,
            "page-height" => null,
            "page-size" => 'A4',
            "page-width" => null,
//            "viewport-size" => 1.0,
            "no-header-line" => true,
            "no-footer-line" => true,
            "zoom" => 1,
            "lowquality" => true,
        ]);

        $filepath = $this->projectDir . '/public/pdf/' . md5($data['owner']['fullName'] . $data['title']['position']) . '.pdf';

        $pdf->generateFromHtml(
            $this->renderView(
                'resume-print/index-print.html.twig',
                [
                    'isPrintPdf' => true,
                    'data' => $data,
                ]
            ),
            $filepath,
            [],
            true
        );

        return new BinaryFileResponse($filepath);
    }

    protected function getResumeData(): array
    {
        return [
            'owner' => [
                'fullName' => $this->getResumeOwnerFullName(),
            ],
            'avatar' => [
                'url' => $this->getEmployeeAvatarUrl(),
                'title' => $this->getResumeOwnerFullName(),
                'alt' => '',
            ],
            'summary' => $this->getProfessionalSummary(),
            'title' => [
                'position' => $this->getJobPosition(),
                'ownerFullName' => $this->getResumeOwnerFullName(),
            ],
            'contacts' => $this->getContacts(),
            'skills' => $this->getSkills(),
            'languages' => $this->getLanguages(),
            'socials' => $this->getSocialLinks(),
            'experience' => [
                'work' => $this->getWorkExperience(),
                'projects' => [],
            ],
            'education' => $this->getEducationHistory(),
            'testimonials' => [
                'aside' => $this->getAsideTestimonials(),
            ]
        ];
    }

    protected function getAsideTestimonials(): array
    {
        return [
          [
              'fullName' => 'Dolev Sabbah',
              'position' => 'Development Team Lead',
              'company' => 'SciPlay',
              'link' => 'https://www.linkedin.com/in/dolev-sabbah-52aaa832/',
              'nickname' => 'dolev-sabbah-52aaa832',
              'icon' => 'linkedin',
          ],
            [
                'fullName' => 'George Fironov',
                'position' => 'Co-Founder and CEO',
                'company' => 'Talmatic',
                'link' => 'https://www.linkedin.com/in/george-fironov/',
                'nickname' => 'george-fironov',
                'icon' => 'linkedin',
            ],
            [
                'fullName' => 'Octavian Brînzea',
                'position' => 'Co-Founder and Product Lead',
                'company' => 'Erom Agency',
                'link' => 'https://www.linkedin.com/in/octavianbrinzea/',
                'nickname' => 'octavianbrinzea',
                'icon' => 'linkedin',
            ],
            [
                'fullName' => 'Tim Wendel',
                'position' => 'Lead PHP Developer',
                'company' => 'Kapten&Son',
                'link' => 'https://www.linkedin.com/in/tim-wendel-2269401a3/',
                'nickname' => 'tim-wendel-2269401a3',
                'icon' => 'linkedin',
            ],
          [
              'fullName' => 'Dmytro Skotar',
              'position' => 'QA Engineer',
              'company' => 'SciPlay',
              'link' => 'https://www.linkedin.com/in/dmytro-skotar/',
              'nickname' => 'dmytro-skotar',
              'icon' => 'linkedin',
          ],
          [
              'fullName' => 'Yehor Chernyshov',
              'position' => 'Backend Developer',
              'company' => 'Kapten&Son',
              'link' => 'https://www.linkedin.com/in/yehorchernyshov/',
              'nickname' => 'yehorchernyshov',
              'icon' => 'linkedin',
          ],
          [
              'fullName' => 'Nina Pache',
              'position' => 'Product Owner',
              'company' => 'Kapten&Son',
              'link' => 'https://www.linkedin.com/in/nina-pache-657016106/',
              'nickname' => 'nina-pache-657016106',
              'icon' => 'linkedin',
          ],
        ];
    }


    protected function getJobPosition(): string
    {
        return 'Senior Software Engineer';
    }

    protected function getResumeOwnerFullName(): string
    {
        return 'Vladyslav Drybas';
    }

    protected function getEmployeeAvatarUrl(): string
    {
        return 'avatar/me.jpeg';
    }

    protected function getProfessionalSummary(): string
    {
        return 'Experienced Senior Software Engineer with a Computer Science background and over a decade of industry expertise. Skilled in JavaScript and TypeScript with a strong background in PHP. Knowledgeable in CI/CD, testing, and software development methodologies. Proven track record of stable continuous development and a commitment to ongoing learning through pet projects on GitHub. I love to work with people who are open to constructive discussion and have a flexible mindset. Committed to continuous learning and sharing knowledge with teammates, ready to contribute to high-performing teams and take on new challenges in the ever-evolving field of software engineering.';
    }

    protected function getSkills(): array
    {
        return [
            'PHP',
            'Javascript',
            'TypeScript',
            'Solidity',
            'Lead and deliver complex software systems',
            'Design and implement database structures',
            'Documentation management',
            'Version Control',
            'MySQL',
            'PostgreSql',
            'Symfony',
            'Node.js',
            'React',
            'Web applications',
            'Docker',
            'CI/CD',
            'Unit Testing',
            'Integration Testing',
            'Deployment',
            'NoSql',
            'Redis',
            'RabbitMQ',
            'Linux',
            'Nginx',
            'Apache',
            'Traefik',
            'REST API',
            'Chat bots',
            'Midjourney',
            'ChatGPT',
            'OpenAI API',
            'Object-oriented design',
        ];
    }

    protected function getContacts(): array
    {
        return [
            [
                'label' => 'Email',
                'value' => 'vlad.drybas@gmail.com',
                'link' => 'mailto: vlad.drybas@gmail.com',
                'icon' => 'email',
            ],
            [
                'label' => 'Location',
                'value' => 'Germany',
                'link' => null,
                'icon' => 'location',
            ],
//            [
//                'label' => 'Phone',
//                'value' => '+49064598765412',
//                'link' => null,
//                'icon' => 'phone',
//            ],
            [
                'label' => 'Website',
                'value' => 'vladyslavdrybas.com',
//                'link' => $this->urlGenerator->generate(
//                    'app_homepage',
//                    [],
//                    UrlGeneratorInterface::ABSOLUTE_URL
//                ),
                'icon' => 'globe',
                'link' => 'https://vladyslavdrybas.com',
            ],
        ];
    }

    protected function getSocialLinks(): array
    {
        return [
            [
                'label' => 'LinkedIn',
                'link' => 'https://linkedin.com/in/vladyslavdrybas',
                'nickname' => 'vladyslavdrybas',
                'icon' => 'linkedin',
            ],
            [
                'label' => 'Github',
                'link' => 'https://github.com/vladyslavdrybas',
                'nickname' => 'vladyslavdrybas',
                'icon' => 'github',
            ],
        ];
    }

    protected function getProjects(): array
    {
        return [
            [],
        ];
    }

    protected function getWorkExperience(): array
    {
        return [
            [
                'name' => 'Hot Shot',
                'company' => [
                    'name' => 'SciPlay',
                ],
                'position' => 'Senior Software Engineer',
                'location' => 'Ukraine, Israel, USA',
                'startAt' => 'Nov 2022',
                'endAt' => 'Jun 2024',
                'summary' => 'At SciPlay, My role involved designing developing, and optimizing backend services, including ensuring code quality through rigorous unit testing. My expertise in PHP, Unit Testing, and Docker allowed me to play a key role in multiple successful project upgrades and feature implementations.',
                'achievements' => [
                    'Spearheaded the integration of Docker for development and deployment, reducing environment setup time by 50%.',
                    'Led the implementation of comprehensive code refactoring, that increased code compatibility with the company\'s inner libraries and PHP8.',
                    'Updates, fixes, and refactoring reduced amount of servers by twice for the support of millions of game users.',
                    'Huge code refactoring made by me reduced amount of existing bugs by 90%. Thanks to excellent cooperation with the QA department and new development approaches no new bugs were produced in production in 2 years.',
                ],
                'contact' => $this->getContactPersons()['sciplay'],
            ],
            [
                'name' => 'Kapten&Son',
                'company' => [
                    'name' => 'Kapten&Son/Artkai',
                ],
                'position' => 'Senior Software Engineer',
                'location' => 'Germany, Ukraine',
                'startAt' => 'Jul 2019',
                'endAt' => 'Oct 2022',
                'summary' => 'As a contractor, I am a full-time out-staff worker in Kapten & Son - a fashion online store with almost 200 employees and 20 million unique visitors per year. I develop features for the store and many different inner web services. On a daily basis, we are using the Spryker & Symfony frameworks.',
                'achievements' => [
                    'Faster delivery to the customer/ Building of web services from scratch for processing orders from checkout through warehouses to ﬁnal buyers.',
                    'New products launching/ Building of features to provide absolutely new multi-conﬁgurable products. I updated existing services so warehouses can process them.',
                    'Optimized work processes/ Connecting a bunch of our services and store\' parts to the ERP system.',
                    'Marketing improvements/ Connecting the store to Facebook and Google marketing tools. Developing: a data collector for orders; a promotions sender that is based on abandoned checkouts; a product availability informer; data transfer modules. I am using A/B testing to prove my work.',
                ],
                'contact' => $this->getContactPersons()['kaptenson'],
            ],
            [
                'name' => 'Avail',
                'company' => [
                    'name' => 'Talmatic',
                ],
                'position' => 'Lead Software Engineer',
                'location' => 'Ireland, Ukraine',
                'startAt' => 'Nov 2018',
                'endAt' => 'Jul 2019',
                'summary' => 'This is a mobile program reinforcing personalized support to help individuals with cognitive disabilities to achieve independence across all of life’s domains.',
                'achievements' => [
                    'Got investments for startup/ Refactor not working prototype to stable MVP.',
                    'Optimized existing code and infrastructure/ I made it possible to have local development. I created a single point of proof for the code base from 9 different code bases, 9 different databases, and 6 different mobile applications in 5 timezones.',
                    'Reduced maintenance time from 8 hours to 15 min/ I removed redundant and added new AWS services. I moved the project to docker and implemented CI/CD pipelines.',
                    'New features/ I developed: a user cabinet to create tasks with audio and video in the browser; notiﬁcator for daily reminders; functionality to build progress reports in pdf.',
                ],
                'contact' => $this->getContactPersons()['talmatic'],
            ],
            [
                'name' => 'The Everlearner',
                'company' => [
                    'name' => 'Erom Agency',
                ],
                'position' => 'Software Engineer',
                'location' => 'Romania, Ukraine, UK',
                'startAt' => 'Aug 2017',
                'endAt' => 'Sep 2018',
                'summary' => 'E-Learning platform for British schools. Provides functionality to fully maintain the study processes. You can create classes where teachers control students\' progress through online courses and exams.',
                'achievements' => [
                    'Subscriptions and payments/ I implemented user subscriptions functionality with the information about payments. The feature can do automatic monthly or annual payments.',
                    'Course watching/ I developed functionality to watch online courses by students and review their progress via beautiful UI.',
                    'Exams system/ I implemented functionality for students to pass exams without cheating.',
                ],
                'contact' => $this->getContactPersons()['erom'],
            ],
            [
                'name' => 'E-Commerce',
                'company' => [
                    'name' => 'BuyWeb',
                ],
                'position' => 'Software Engineer',
                'location' => 'Ukraine',
                'startAt' => 'Mar 2017',
                'endAt' => 'Jun 2017',
                'summary' => 'Remote contractor. Solo development of the three projects: small construction materials store on Prestashop; transferring store from Joomla into Opencart for auto parts seller with more than 5000 clients and logistic system; system that grabs and analyzes bet data that does recommendations.',
                'achievements' => [],
                'contact' => null,
            ],
            [
                'name' => 'Web Studio',
                'company' => [
                    'name' => 'FICCUS',
                ],
                'position' => 'Software Engineer and Co-Founder',
                'location' => 'Ukraine',
                'startAt' => 'Jul 2016',
                'endAt' => 'Jun 2017',
                'summary' => 'Co-founder of the small web studio. From that time, I got experience of work directly with clients, team management, and maintaining many projects at the same time. Also, I started a few small price aggregators and data mining services.',
                'achievements' => [],
                'contact' => null,
            ],
            [
                'name' => 'Phd',
                'company' => [
                    'name' => 'National Technical University of Ukraine \'KPI\'',
                ],
                'position' => 'Postgraduate',
                'location' => 'Ukraine',
                'startAt' => 'Sep 2014',
                'endAt' => 'Jun 2017',
                'summary' => 'Did research on the usage of Multi-Agent Artificial Intelligence in the chemical industry. Participated in scientific conferences. Taught students computer science.',
                'achievements' => [],
                'contact' => null,
            ],
            [
                'name' => 'Freelance',
                'company' => [
                    'name' => 'Uni-Bit Studio Inc.',
                ],
                'position' => 'Software Engineer',
                'location' => 'Ukraine',
                'startAt' => 'Jun 2013',
                'endAt' => 'Jun 2017',
                'summary' => 'Remote contractor. developing and maintaining the plugins and themes for wordpress, joomla, APIs. In that period, I used a lot of scratch PHP and start working with symfony 3 and laravel 5. Developed near 20 projects such as e-commerce, corporate sites, trip sharing, taxi, web games. I had experience working solo and in a team. Worked extensively with JavaScript, HTML, SQL in building Web Applications.',
                'achievements' => [],
                'contact' => null,
            ],
            [
                'name' => 'Support',
                'company' => [
                    'name' => 'Ukrtelecom',
                ],
                'position' => 'Web Developer',
                'location' => 'Ukraine',
                'startAt' => 'Sep 2010',
                'endAt' => 'Jan 2012',
                'summary' => 'Part-time work as a site maintainer for the branch of the biggest communication company in Ukraine.',
                'achievements' => [],
                'contact' => null,
            ],
        ];
    }

    protected function getTestimonials(): array
    {
        return [
            [
                'reviewer' => $this->getContactPersons()['sciplay'],
                'review' => [
                    'project' => [
                        'title' => 'Hot Shot',
                        'company' => [
                            'name' => 'SciPlay',
                        ],
                    ],
                    'text' => 'To Whom It May Concern,

I am writing to highly recommend Vladyslav Drybas for any backend development position he may be seeking. As the Team Leader at Sciplay, I have had the pleasure of working closely with Vladyslav over the past two years, during which time he has proven himself to be an invaluable member of our team.

Vladyslav joined our team as a PHP Backend Developer and quickly demonstrated his expertise in several key areas, including SQL databases, Couchbase, PHP, and Docker. His technical skills are complemented by his strong experience with unit testing, which has been instrumental in maintaining the high quality and reliability of our codebase.

Throughout his tenure, Vladyslav has consistently shown a keen ability to tackle complex problems and deliver efficient, scalable solutions. His deep understanding of backend development, combined with his ability to work effectively within a team, has contributed significantly to the success of our projects. Vladyslav’s dedication to continuous improvement and learning is evident in the way he stays updated with the latest industry trends and technologies, ensuring that our team remains at the forefront of innovation.

In addition to his technical prowess, Vladyslav is a consummate professional. He is dependable, hardworking, and always willing to go the extra mile to ensure that project deadlines are met. His collaborative spirit and excellent communication skills have made him a pleasure to work with, and he has earned the respect and admiration of his colleagues.

I am confident that Vladyslav Drybas will be a tremendous asset to any organization he chooses to join. His technical expertise, problem-solving abilities, and unwavering commitment to excellence make him an ideal candidate for any backend development role. I wholeheartedly recommend him without reservation.

Please feel free to contact me if you require any further information.

Sincerely,

Dolev Sabbah
Team Leader
Sciplay',
                    'date' => 'Jun 2024'
                ],
            ],
        ];
    }

    protected function getEducationHistory(): array
    {
        return [
            [
                'institution' => 'National Technical University of Ukraine \'Kyiv Polytechnic Institute\'',
                'degree' => 'Master',
                'areaSpecific' => 'Computer-integrated technological processes and production',
                'area' => 'Computer Science',
                'startAt' => '2012',
                'endAt' => '2014',
            ],
            [
                'institution' => 'National Technical University of Ukraine \'Kyiv Polytechnic Institute\'',
                'degree' => 'Bachelor',
                'areaSpecific' => 'Automation and Computer-Integrated Technologies',
                'area' => 'Computer Science',
                'startAt' => '2008',
                'endAt' => '2012',
            ],
        ];
    }

    protected function getLanguages(): array
    {
        return [
            [
                'label' => 'Ukrainian',
                'value' => 'Native',
            ],
            [
                'label' => 'English',
                'value' => 'B2',
            ],
            [
                'label' => 'Russian',
                'value' => 'Fluent',
            ],
        ];
    }

    protected function getContactPersons(): array
    {
        return [
            'sciplay' => [
                'project' => [
                    'name' => 'Hot Shot',
                    'company' => [
                        'name' => 'SciPlay'
                    ],
                ],
                'fullName' => 'Dolev Sabbah',
                'avatar' => 'https://media.licdn.com/dms/image/v2/C5603AQF2ljQp6CzUWQ/profile-displayphoto-shrink_100_100/profile-displayphoto-shrink_100_100/0/1546882495482?e=1731542400&v=beta&t=yO3cB7ZeMivgSB4zRAAkg1oz87Tvd2g7CKn47tOvya4',
                'position' => 'Development Team Lead at SciPlay',
                'contacts' => [
                    'email' => 'dolev.sabbah@gmail.com',
                    'socials' => [
                        [
                            'label' => 'LinkedIn',
                            'link' => 'https://www.linkedin.com/in/dolev-sabbah-52aaa832/',
                            'nickname' => 'dolev-sabbah-52aaa832',
                            'icon' => 'linkedin',
                        ],
                    ],
                ],
            ],
            'kaptenson' => [
                'project' => [
                    'name' => 'Kapten&Son',
                    'company' => [
                        'name' => 'Kapten&Son'
                    ],
                ],
                'fullName' => 'Stephan Backenköhler',
                'avatar' => 'https://media.licdn.com/dms/image/v2/C4D03AQELWTUzsdI3lw/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1524498689858?e=1731542400&v=beta&t=EmVMvNuLvAQ8UqWgdk6R5bOJF43fYy-sS0zBI3tqaK0',
                'position' => 'CTO',
                'contacts' => [
                    'email' => null,
                    'socials' => [
                        [
                            'label' => 'LinkedIn',
                            'link' => 'https://www.linkedin.com/in/sbackenkoehler/',
                            'nickname' => 'sbackenkoehler',
                            'icon' => 'linkedin',
                        ],
                    ],
                ],
            ],
            'talmatic' => [
                'project' => [
                    'name' => 'Avail',
                    'company' => [
                        'name' => 'Talmatic'
                    ],
                ],
                'fullName' => 'George Fironov',
                'avatar' => 'https://media.licdn.com/dms/image/v2/D4E35AQEYYZvLfLvhZg/profile-framedphoto-shrink_200_200/profile-framedphoto-shrink_200_200/0/1725634435872?e=1726772400&v=beta&t=LU5dHAdORh3CicnEm9o869CFiB8bgChXsNJzqHeGeFo',
                'position' => 'Co-Founder and CEO',
                'contacts' => [
                    'email' => null,
                    'socials' => [
                        [
                            'label' => 'LinkedIn',
                            'link' => 'https://www.linkedin.com/in/george-fironov/',
                            'nickname' => 'george-fironov',
                            'icon' => 'linkedin',
                        ],
                    ],
                ],
            ],
            'erom' => [
                'project' => [
                    'name' => 'The Everlearner',
                    'company' => [
                        'name' => 'Erom Agency'
                    ],
                ],
                'fullName' => 'Octavian Brînzea',
                'avatar' => 'https://media.licdn.com/dms/image/v2/C5603AQGirGzzOJg5wg/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1516309294293?e=1731542400&v=beta&t=OtfpJLGQ6VO6NTFGN7Pvy5OZBHX69BRci6Gi-Yd7z2A',
                'position' => 'Co-Founder and Product Lead',
                'contacts' => [
                    'email' => null,
                    'socials' => [
                        [
                            'label' => 'LinkedIn',
                            'link' => 'https://www.linkedin.com/in/octavianbrinzea/',
                            'nickname' => 'octavianbrinzea',
                            'icon' => 'linkedin',
                        ],
                    ],
                ],
            ]
        ];
    }
}
