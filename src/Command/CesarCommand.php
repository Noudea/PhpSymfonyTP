<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\Coffre;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class CesarCommand extends Command
{
    protected static $defaultName = 'Cesar';
    private $logger;
    private $cesar;

    public function __construct(LoggerInterface $logger, Coffre $coffre)
    {
        $this->logger = $logger;
        $this->coffre = $coffre;
        parent::__construct(self::$defaultName);
    }
    protected function configure()
    {
        $this
            ->setDescription('un coffre fort')
            ->addArgument('nb', InputArgument::OPTIONAL, 'nombre de questions auxquelles le joueur veut répondre')
            ->addOption('play', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nb = $input->getArgument('nb');
        
        if ($input->getOption('play')) {
            
            while (true) {
                $helper = $this->getHelper('question');
                $status = $this->coffre->getStatus();

                //creation des options
                $options = ['voir l\'etat de votre coffre fort'];
                if($status == 'opened')
                {
                    $options[] = 'fermer le coffre fort';
                    $options[] = 'stocker un objet';
                    $options[] = 'voir mes objets stockes';
                }
                if ($status == 'closed') 
                {
                    $options[] = 'ouvrir le coffre fort';
                    $options[] = 'verouiller le coffre fort';
                }
                if ($status == 'locked') {
                    $options[] = 'ouvrir le coffre fort';
                }

                $question = new ChoiceQuestion(
                    'Bonjour,bienvenue sur votre interface de coffre fort,veuillez sélectionner l\'action a effectuer',
                    $options,
                    '0,1'
                );
                $question->setMultiselect(false);
                $choice = $helper->ask($input, $output, $question);
                $output->writeln('You have just selected: ' . $choice);

                //gestion des choix
                if($choice == 'fermer le coffre fort')
                {
                    $this->coffre->setStatus('closed');
                    $output->writeln('vous avez ferme votre coffre fort');
                }
                if ($choice == 'ouvrir le coffre fort') {

                    if($status == 'locked')
                    {
                        $question = new Question('Veuillez entrer votre mot de passe', 'AcmeDemoBundle');
                        $mdp = $helper->ask($input, $output, $question);

                        if($this->coffre->checkMdp($mdp))
                        {
                            $this->coffre->setStatus('opened');
                            $output->writeln('vous avez ouvert votre coffre fort');
                        }
                        else {
                            $output->writeln('mauvais mot de passe');
                        }
                    }
                    else{
                        $this->coffre->setStatus('opened');
                        $output->writeln('vous avez ouvert votre coffre fort');
                    }
                }
                if ($choice == 'stocker un objet')
                {
                    $question = new Question('Quel objet voulez vous stocker ?', 'AcmeDemoBundle');
                    $item = $helper->ask($input, $output, $question);

                    $this->coffre->registerItem($item);
                }

                if ($choice == 'voir mes objets stockes')
                {
                    $items = $this->coffre->getAllItem();
                    foreach ($items as $key => $value) {
                        $output->writeln($value);
                    }
                }
                if ($choice == 'verouiller le coffre fort')
                {
                    if (!$this->coffre->getMdp())
                    {
                        $question = new Question('Veuillez creer votre mot de passe');
                        $mdp = $io->askHidden('Veuillez creer votre mot de passe');
                        $this->coffre->setMdp($mdp);
                        $this->coffre->setMdpStatus(true);
                        $this->coffre->setStatus('locked');
                        $output->writeln('le coffre fort est bien verrouille');
                    }
                    else {
                        $this->coffre->setStatus('locked');
                        $output->writeln('le coffre fort est bien verrouille');
                    }
                }
            }
            
        }
        return Command::SUCCESS;
    }
}
