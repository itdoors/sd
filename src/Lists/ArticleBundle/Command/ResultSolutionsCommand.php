<?php

/**
 * Command class for parser
 */

namespace Lists\ArticleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * CronDeleteCommand
 */
class ResultSolutionsCommand extends ContainerAwareCommand
{

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('lists:article:result:solution')
            ->setDescription('Result in solution')
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
        
        $service = $this->getContainer()->get('lists_article.service');
        $status = $service->resultSolutions($id);
        
        
        if ($status === 0) {
            $status = 'Declined';
        } elseif ($status === 1) {
            $status = 'Received';
        } elseif ($status === 2) {
            $status = '50/50';            
        }
        $res = 'Status for: ' . $id . ' = ' . $status;
        $output->writeln($res);
    }
}
