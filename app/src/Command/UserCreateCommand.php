<?php

namespace App\Command;

use App\Builder\UserBuilder;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'user:create',
    description: 'Create user',
)]
class UserCreateCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserBuilder $userBuilder,
        protected string $projectEnvironment,
        protected ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void {
        $this->addArgument('email', InputArgument::OPTIONAL);
        $this->addArgument('password', InputArgument::OPTIONAL);
        $this->addArgument('username', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Project environment:' . $this->projectEnvironment);

        $io->success('Success');

        $faker = Factory::create();

        $email = $input->getArgument('email') ?? $faker->email();
        $password = $input->getArgument('password') ?? 123123;
        $username = $input->getArgument('username') ?? $faker->userName();

        $user = $this->userBuilder->base($email, $password, $username);

        dump($user);


        return Command::SUCCESS;
    }
}
