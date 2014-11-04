<?php

/**
 * Command class for removing user from group
 */
namespace SD\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use SD\UserBundle\Entity\User as FOSUser;
use SD\UserBundle\Entity\Group as FOSGroup;

/**
 * RemoveGroupCommand class
 */
class RemoveGroupCommand extends ContainerAwareCommand
{
    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('sd:user:group-remove')
            ->setDescription('Remove group user')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('group_name', InputArgument::REQUIRED, 'The group name'),
            ));
    }

    /**
     * execute
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $groupName = strtoupper($input->getArgument('group_name'));

        /** @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
        $dialog = $this->getHelperSet()->get('dialog');

        /** @var \FOS\UserBundle\Model\UserManager $um */
        $um = $this->getContainer()->get('fos_user.user_manager');

        /** @var \FOS\UserBundle\Model\GroupManager $gm */
        $gm = $this->getContainer()->get('fos_user.group_manager');

        /** @var FOSUser $userFOS */
        $userFOS = $um->findUserByUsername($username);

        if (!$userFOS) {
            $output->writeln("User with username {$username} does not exist");

            return;
        }

        /** @var FOSGroup $groupFOS */
        $groupFOS = $gm->findGroupByName($groupName);

        if (!$groupFOS) {
            $output->writeln("Group {$groupName} does not exist");

            return;
        }

        /*if (!$userFOS->hasGroup($groupFOS)) {
          $output->writeln("User {$username} not in {$groupName}");

          return;
        }*/

        if (!$dialog->askConfirmation($output, "Remove user {$username} from {$groupName}? (yes/no)", false)) {
            return;
        }

        $userFOS->removeGroup($groupFOS);

        $um->updateUser($userFOS);

        $output->writeln("User {$username} removed from {$groupName} successfully");
    }

    /**
     * interact()
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('username')) {
            $username = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a username: ',
                function ($username) {
                    if (empty($username)) {
                        throw new \Exception('Username can not be empty ');
                    }

                    return $username;
                }
            );
            $input->setArgument('username', $username);
        }

        if (!$input->getArgument('group_name')) {
            $groupName = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a Group Name: ',
                function ($groupName) {
                    if (empty($groupName)) {
                        throw new \Exception('Group Name can not be empty ');
                    }

                    return $groupName;
                }
            );
            $input->setArgument('group_name', $groupName);
        }
    }
}
