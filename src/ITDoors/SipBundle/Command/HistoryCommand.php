<?php

/**
 * Command class for adding history info about calls
 */
namespace ITDoors\SipBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
        // --uniqueId 1409660070.14750 --filename 14-09/1409660070.14750 --callerId 8005
        // --proxyId 3590556 --receiverId 0636821588 --destuniqueId 1409660070.14751
        // --modelName contact --modelId 2209 --dialStatus CANCEL
        $this
            ->setName('itdoors:sip:history')
            ->setDescription('Insert history info')
            ->addOption('callerId', null, InputOption::VALUE_REQUIRED)
            ->addOption('receiverId', null, InputOption::VALUE_REQUIRED)
            ->addOption('uniqueId', null, InputOption::VALUE_OPTIONAL)
            ->addOption('filename', null, InputOption::VALUE_OPTIONAL)
            ->addOption('proxyId', null, InputOption::VALUE_OPTIONAL)
            ->addOption('destuniqueId', null, InputOption::VALUE_OPTIONAL)
            ->addOption('modelName', null, InputOption::VALUE_OPTIONAL)
            ->addOption('modelId', null, InputOption::VALUE_OPTIONAL)
            ->addOption('dialStatus', null, InputOption::VALUE_OPTIONAL);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uniqueId = $input->getOption('uniqueId');
        $filename = $input->getOption('filename');
        $callerId = $input->getOption('callerId');
        $proxyId = $input->getOption('proxyId');
        $receiverId = $input->getOption('receiverId');
        $destuniqueId = $input->getOption('destuniqueId');
        $modelName = $input->getOption('modelName');
        $modelId = $input->getOption('modelId');
        $dialStatus = $input->getOption('dialStatus');

        $output->writeln("uniqueId = {$uniqueId}");
        $output->writeln("filename = {$filename}");
        $output->writeln("callerId = {$callerId}");
        $output->writeln("proxyId = {$proxyId}");
        $output->writeln("receiverId = {$receiverId}");
        $output->writeln("destuniqueId = {$destuniqueId}");
        $output->writeln("modelName = {$modelName}");
        $output->writeln("modelId = {$modelId}");
        $output->writeln("dialStatus = {$dialStatus}");
    }
}
