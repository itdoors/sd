<?php

/**
 * Command class for parser
 */

namespace SD\CalendarBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BCC\CronManagerBundle\Manager\CronManager;
use Symfony\Component\Console\Input\InputArgument;

/**
 * sendEmailToStuffCommand
 */
class sendEmailToStuffCommand extends ContainerAwareCommand
{

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('sd:send:email:to:stuff')
            ->setDescription('Send email holidays for stuff');
    }

    /**
     * execute
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $holiday = $this->getContainer()->get('sd_calendar.holiday.service');

        $res = $holiday->sendEmailToStuff();

        $output->writeln($res);
    }
}
