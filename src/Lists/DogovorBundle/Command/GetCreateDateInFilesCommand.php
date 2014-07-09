<?php

/**
 * Command class for parser
 */

namespace Lists\DogovorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * GetCreateDateInFilesCommand
 */
class GetCreateDateInFilesCommand extends ContainerAwareCommand
{

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('lists:dogovor:update:createdate')
            ->setDescription('Get create date in files for dogovor and dop_dogovor');
    }

    /**
     * execute
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $service = $this->getContainer()->get('lists_dogovor.service');

        $res = $service->updateDate();

        $output->writeln($res);
    }
}
