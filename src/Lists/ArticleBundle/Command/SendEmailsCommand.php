<?php

/**
 * Command class for parser
 */

namespace Lists\ArticleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BCC\CronManagerBundle\Manager\CronManager;
use Symfony\Component\Console\Input\InputArgument;

/**
 * CronDeleteCommand
 */
class SendEmailsCommand extends ContainerAwareCommand
{

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('lists:article:send:for')
            ->setDescription('Send emails party')
             ->setDefinition(array(
                new InputArgument('id', InputArgument::REQUIRED, 'The id article')
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
        $id = $input->getArgument('id');

        $output->writeln($id);
        $service = $this->getContainer()->get('lists_article.service');

        $res = $service->sendEmails($id);

        $output->writeln($res);
    }
}
