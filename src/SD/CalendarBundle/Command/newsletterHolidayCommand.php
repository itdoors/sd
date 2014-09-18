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
            ->setName('sd:newsletter:holiday')
            ->setDescription('Newsletter holidays for stuff')
            ->setDefinition(array(
                new InputArgument('period', InputArgument::REQUIRED, 'The period (day or week)')
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

        $period = strtoupper($input->getArgument('period'));
        
        if (!in_array($period, array('day', 'week'))) {
            $output->writeln('period mast be "dey" or "week"');

            return;
        }

        $holiday = $this->getContainer()->get('sd_calendar.holiday.service.class');

        $res = $holiday->newsletterHoliday($period);

        $output->writeln($res);
    }
}
