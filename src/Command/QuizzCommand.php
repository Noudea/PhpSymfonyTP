<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\Quizz;
use Psr\Log\LoggerInterface;

class QuizzCommand extends Command
{
    protected static $defaultName = 'QuizzCommand';
    private $logger;
    private $quizz;

    public function __construct(LoggerInterface $logger, Quizz $quizz)
    {
        $this->logger = $logger;
        $this->quizz = $quizz;
        parent::__construct(self::$defaultName);
    }
    protected function configure()
    {
        $this
            ->setDescription('un quizz rigolo')
            ->addArgument('nb', InputArgument::OPTIONAL, 'nombre de questions auxquelles le joueur veut répondre')
            ->addOption('play', null, InputOption::VALUE_NONE, 'Option description')
            ->addOption('salut', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nb = $input->getArgument('nb');

        if($input->getOption('play') && $nb)
        {
            $io->note(sprintf('Vous avez demandé a répondre à %s', $nb . ' questions'));
            for ($i = 0; $i < $nb; $i++) {
                $return = $this->quizz->getRandomQuestion();
                $io->success($return['question']);
                sleep(5);
                $io->success($return['reponse']);
            }
        }
        if ($input->getOption('salut'))
        {
            $io->success('salut');
        }
        return Command::SUCCESS;
    }
}
