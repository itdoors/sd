<?php

/**
 * Command class for adding role to group
 */
namespace Sd\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use SD\UserBundle\Entity\Group as FOSGroup;

class GroupAddRoleCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('sd:group:role-add')
      ->setDescription('Add role to group')
      ->setDefinition(array(
        new InputArgument('group_name', InputArgument::REQUIRED, 'The group name'),
        new InputArgument('role', InputArgument::REQUIRED, 'The role'),
      ))
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $groupName = strtoupper($input->getArgument('group_name'));
    $role      = 'ROLE_' . strtoupper($input->getArgument('role'));

    /** @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
    $dialog = $this->getHelperSet()->get('dialog');

    /** @var \FOS\UserBundle\Model\GroupManager $gm */
    $gm = $this->getContainer()->get('fos_user.group_manager');

    /** @var FOSGroup $groupFOS */
    $groupFOS = $gm->findGroupByName($groupName);

    $isNew = false;

    if (!$groupFOS) {
      if (!$dialog->askConfirmation($output, "Group does not exist! Do you want to create it with name {$groupName}? (yes/no)", false )) {
        return;
      }

      $isNew = true;

      $groupFOS = $gm->createGroup($groupName);
    }

    if (!$dialog->askConfirmation($output, "Add {$role} to {$groupName}? (yes/no)", false )) {
      return;
    }

    $groupFOS->addRole($role);

    $gm->updateGroup($groupFOS);

    if ($isNew) {
      $output->writeln("Group {$groupName} created successfully");
    }

    $output->writeln("Role {$role} added to {$groupName} successfully");
  }

  /**
   * @see Command
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
