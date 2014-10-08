<?php

/**
 * Command class for parser
 */

namespace SD\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * UpdateAvatarCommand
 */
class UpdateAvatarCommand extends ContainerAwareCommand
{

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('sd:user:change-avatar')
            ->setDescription('Change avatar new');
    }

    /**
     * execute
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $parser = $this->getContainer()->get('sd_user.service');

        $parser->changeAvatar($input, $output);
    }
}
