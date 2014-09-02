<?php

/**
 * Command class for adding history info about calls
 */
namespace ITDoors\SipBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * HistoryCommand
 */
class HistoryCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('itdoors:sip:history')
            ->setDescription('Insert history info')
            ->setDefinition(array(
                new InputArgument('params', InputArgument::REQUIRED, 'Params in json'),
            ));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = $input->getArgument('params');

        $output->writeln("Params = {$params}");
    }
}
