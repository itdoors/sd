<?php

/**
 * Command class for parser
 */

namespace ITDoors\ControllingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ParserInvoiceCommand
 */
class ParserInvoiceCommand extends ContainerAwareCommand
{

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('id:doors:invoice-parser')
            ->setDescription('parser invoices');
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

        $res = $parser->parserFile();

        $output->writeln($res);
    }
}
