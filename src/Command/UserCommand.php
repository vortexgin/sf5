<?php

namespace App\Command;

use App\Document\User;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserCommand extends Command
{

    /**
     * @var DocumentManager
     */
    private $_dm;

    /**
     * @var UserRepository
     */
    private $_userRepo;

    protected static $defaultName = 'app:user:create';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addOption('role', null, InputOption::VALUE_OPTIONAL, 'User role')
        ;
    }

    public function __construct(DocumentManager $dm,  string $name = null)
    {
        parent::__construct($name);

        $this->_dm = $dm;
        $this->_userRepo = $dm->getRepository(User::class);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $input = [
            'email' => $input->getArgument('email'),
            'password' => $input->getArgument('password'),
            'role' => $input->hasOption('role') ? $input->getOption('role') : User::ROLE_USER,
        ];

        $user = $this->_userRepo->findByEmailOrMobilePhone($input['email']);
        if (!empty($user)) {
            $output->writeln(sprintf('<error>%s</error>', 'Email already exists'));

            return (int) false;
        }

        $user = new User();
        $user->setEmail($input['email'])
            ->setPlainPassword($input['password'], getenv('APP_SECRET'))
            ->setRole($input['role']);
        $this->_dm->persist($user);
        $this->_dm->flush();

        $output->writeln(sprintf('<info>%s</info>', 'User successfully created'));

        return (int) true;
    }
}