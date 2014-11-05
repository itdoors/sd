<?php

/**
 * Command class for parser
 */

namespace ITDoors\OperBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * FixGrafiksCommand
 */
class FixGrafiksCommand extends ContainerAwareCommand
{
    /**
     * configure
     */
    protected function configure ()
    {
        $this
            ->setName('it:doors:fix_grafik')
            ->setDescription('Fix grafik');
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

        $parser = $this->getContainer()->get('schedule_update.service');

        $res = $parser->scheduleFix(8, 2014);

        $output->writeln($res);

        $res = $parser->scheduleFix(7, 2014);

        $output->writeln($res);
    }
}
