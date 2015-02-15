<?php

/**
 * Command class for parser
 */

namespace Lists\CompanystructureBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ConstructTreeCommand
 */
class ConstructTreeCommand extends ContainerAwareCommand
{

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('lists:companystructure:construct-tree')
            ->setDescription('To construct a tree');
    }

    /**
     * execute
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $service = $this->getContainer()->get('lists_companystructure.service');

        $res = $service->constructTree();

        $output->writeln($res);
    }
}
