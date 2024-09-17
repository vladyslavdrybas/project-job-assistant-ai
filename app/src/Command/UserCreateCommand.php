<?php

namespace App\Command;

use App\Builder\UserBuilder;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $this->addArgument('amount', InputArgument::OPTIONAL);
        $this->addArgument('email', InputArgument::OPTIONAL);
        $this->addArgument('password', InputArgument::OPTIONAL);
        $this->addArgument('username', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->warning('ENVIRONMENT: ' . strtoupper($this->projectEnvironment));

        $faker = Factory::create();

        $amount = filter_var($input->getArgument('amount') ?? 1, FILTER_VALIDATE_INT);
        $progressBar = new ProgressBar($output, $amount);
        $progressBar->start();


        $counter = 0;
        $email = $input->getArgument('email') ?? $faker->email();
        $password = $input->getArgument('password') ?? 123123;
        $username = $input->getArgument('username') ?? $faker->userName();
        $outputRows = [];
        do {
            $user = $this->userBuilder->base($email, $password, $username);

            try {
                $userRepository = $this->em->getRepository(User::class);
                $userRepository->add($user);
                $userRepository->save();
            } catch (Exception $e) {
                $io->error($e->getMessage());
            }

            $counter++;

            $outputRows[] = [
                $counter,
                $user->getUsername(),
                $user->getEmail(),
                $password
            ];

            $email = $faker->email();
            $password = 123123;
            $username = $faker->userName();


            $progressBar->advance();
        } while ($counter < $amount);
        $progressBar->finish();

        $table = new Table($output);
        $table
            ->setHeaders(['#', 'Username', 'Email', 'Password'])
            ->setRows($outputRows)
        ;
        $table->render();

        $io->success($amount . ' users created!');

        return Command::SUCCESS;
    }
}
