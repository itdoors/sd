<?php

/**
 * Command class for parser
 */

namespace ITDoors\CronBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BCC\CronManagerBundle\Manager\CronManager;
use Symfony\Component\Console\Input\InputArgument;

/**
 * CronDeleteCommand
 */
class CronDeleteCommand extends ContainerAwareCommand
{

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('it:doors:cron:delete')
            ->setDescription('Cron delete command')
             ->setDefinition(array(
                new InputArgument('comment', InputArgument::REQUIRED, 'The comment cron')
            ));
    }

    /**
     * execute
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $comment = $input->getArgument('comment');
        $res = '';
        $cm = new CronManager();
        foreach ($cm->get() as $key => $val){
            if($val->getComment() == $comment){
                $cm->remove($key);
                $res = 'Remove: '.$key;
            }
        }
        $output->writeln($res);
    }
}
