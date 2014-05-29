<?php

/**
 * Command class for removing role from group
 */
namespace Sd\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use SD\UserBundle\Entity\Group as FOSGroup;

/**
 * GroupRemoveRoleCommand
 */
class GroupRemoveRoleCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('sd:group:role-remove')
            ->setDescription('Add role to group')
            ->setDefinition(array(
                new InputArgument('group_name', InputArgument::REQUIRED, 'The group name'),
                new InputArgument('role', InputArgument::REQUIRED, 'The role'),
            ));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $groupName = strtoupper($input->getArgument('group_name'));
        $role = 'ROLE_' . strtoupper($input->getArgument('role'));

        /** @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
        $dialog = $this->getHelperSet()->get('dialog');

        /** @var \FOS\UserBundle\Model\GroupManager $gm */
        $gm = $this->getContainer()->get('fos_user.group_manager');

        /** @var FOSGroup $groupFOS */
        $groupFOS = $gm->findGroupByName($groupName);

        $isNew = false;

        if (!$groupFOS) {
            $output->writeln("Group {$groupName} does not exist");

            return;
        }

        if (!$groupFOS->hasRole($role)) {
            $output->writeln("Group {$groupName} does not have role {$role}");

            return;
        }

        if (!$dialog->askConfirmation($output, "Remove {$role} from {$groupName}? (yes/no)", false)) {
            return;
        }

        $groupFOS->removeRole($role);

        $gm->updateGroup($groupFOS);

        $output->writeln("Role {$role} removed from {$groupName} successfully");
    }

    /**
     * {@inheritDoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
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

        if (!$input->getArgument('role')) {
            $groupName = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a Role: ',
                function ($groupName) {
                    if (empty($groupName)) {
                        throw new \Exception('Role can not be empty ');
                    }

                    return $groupName;
                }
            );
            $input->setArgument('role', $groupName);
        }
    }
}
