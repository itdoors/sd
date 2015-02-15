<?php

/**
 * Command class for parser
 */

namespace ITDoors\ControllingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * RemoveInvoiceCommand
 */
class RemoveInvoiceCommand extends ContainerAwareCommand
{

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('it:doors:invoice-remove')
            ->setDescription('Delete the account until 2014');
    }

    /**
     * execute
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $parser = $this->getContainer()->get('it_doors_invoice.service');

        $res = $parser->removeInvoice();

        $output->writeln($res);
    }
}
