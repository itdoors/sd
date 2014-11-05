<?php

/**
 * Command class for parser
 */

namespace SD\TaskBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * InformTaskCommand
 */
class InformTaskCommand extends ContainerAwareCommand
{
    /**
     * configure
     */
    protected function configure ()
    {
        $this
            ->setName('it:doors:inform_task')
            ->setDescription('Sender emails to performer overdue');
    }

    /**
     * execute
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string
     */
    protected function execute (InputInterface $input, OutputInterface $output)
    {

        $informer = $this->getContainer()->get('task.service');

        $res = $informer->informEndTask();
        $output->writeln($res);
    }
}
