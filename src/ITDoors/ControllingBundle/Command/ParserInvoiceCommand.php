<?php

/**
 * Command class for adding user to group
 */
namespace ITDoors\ControllingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use SD\UserBundle\Entity\User as FOSUser;
use SD\UserBundle\Entity\Group as FOSGroup;

class ParserInvoiceCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('id:doors:invoice-parser')
      ->setDescription('parser invoices');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
      
    $parser = $this->getContainer()->get('it_doors_invoice.service');
    
    $res = $parser->parserFile();

    $output->writeln($res);

  }
}
