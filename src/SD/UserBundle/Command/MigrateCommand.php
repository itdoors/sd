<?php

/**
 * Command class for migration users from sf1.4 sfGuardUser to sf2
 */
namespace SD\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use SD\UserBundle\Entity\User as FOSUser;

/**
 * MigrateCommand
 */
class MigrateCommand extends ContainerAwareCommand
{
    /**
     * @var string[] $migrationsRoles
     */
    protected $migrationsGroups = array(
        'crm' => array(
            'group' => 'SALES',
            'role' => 'ROLE_SALES'
        ),
        'crmadmin' => array(
            'group' => 'SALESADMIN',
            'role' => 'ROLE_SALESADMIN'
        )
    );

    /**
     * @var string[] guardRoleInheritance
     */
    protected $guardRoleInheritance = array(
        'crmadmin' => array('crm'),
    );

    /**
     * @var \Doctrine\ORM\EntityManager $em
     */
    protected $em;

    /**
     * @var \FOS\UserBundle\Model\UserManager $um
     */
    protected $um;

    /**
     * @var \FOS\UserBundle\Model\GroupManager $gm
     */
    protected $gm;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('sd:user:migrate')
            ->setDescription('Migrate users from sf1.4 to sf2');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
        $dialog = $this->getHelperSet()->get('dialog');

        $msg = 'If it first run user with nickname robot must reset id to 0!!! (yes/no)';

        if (!$dialog->askConfirmation($output, $msg, false)) {
            return;
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var \FOS\UserBundle\Model\UserManager $um */
        $this->um = $this->getContainer()->get('fos_user.user_manager');

        /** @var \FOS\UserBundle\Model\GroupManager $gm */
        $this->gm = $this->getContainer()->get('fos_user.group_manager');

        /** @var \Doctrine\ORM\Query $query */
        $conn = $this->getContainer()->get('database_connection');
        $users = $conn->fetchAll(
            "SELECT
               u.*,
               array_to_string(
                 ARRAY(
                    SELECT
                      p.name
                    FROM
                      sf_guard_permission p
                    LEFT JOIN sf_guard_user_permission up ON up.permission_id = p.id
                    WHERE
                      up.user_id = u.id
                 ), ','
               ) as permission_name
            FROM
              sf_guard_user u
            ORDER BY
              u.id ASC"
        );

        foreach ($users as $user) {
            //$output->writeln(var_export($user));
            //continue;

            $userFOS = $this->processFOSUser($user, $output);

            $this->em->persist($userFOS);

            $metadata = $this->em->getClassMetaData(get_class($userFOS));
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }

        $this->em->flush();

        $output->writeln($this->getDescription() . ' END');
    }

    /**
     * Processes new fos user
     *
     * @param mixed[]         $user
     * @param OutputInterface $output
     *
     * @return FOSUser
     */
    protected function processFOSUser($user, OutputInterface $output)
    {
        $id = trim($user['id']);

        $firstName = trim($user['first_name']);
        $lastName = trim($user['last_name']);
        $middleName = trim($user['middle_name']);
        $position = trim($user['position']);
        $username = trim($user['username']);
        $email = trim($user['email_address']);
        $salt = trim($user['salt']);
        $password = trim($user['password']);
        $isActive = $user['is_active'] ? $user['is_active'] : false;
        $isBlocked = $user['is_blocked'] ? $user['is_blocked'] : false;
        $isFired = $user['is_fired'] ? $user['is_fired'] : null;
        $birthday = $user['birthday'] ? new \DateTime(trim($user['birthday'])) : null;

        /** @var FOSUser $userFOS */
        $userFOS = $this->um->findUserBy(array('id' => $id));

        if (!$userFOS) {
            $userFOS = $this->um->findUserByUsername($username);
        }

        $isNew = false;

        if (!$userFOS) {
            $isNew = true;
            $userFOS = $this->um->createUser();
        }

        $userFOS->setId($id);
        $userFOS->setFirstName($firstName);
        $userFOS->setLastName($lastName);
        $userFOS->setMiddleName($middleName);
        $userFOS->setUsername($username);
        $userFOS->setEmail($email);
        $userFOS->setPassword($password);
        $userFOS->setSalt($salt);
        $userFOS->setPosition($position);
        $userFOS->setIsBlocked($isBlocked);
        $userFOS->setIsFired($isFired);
        $userFOS->setEnabled($isActive);

        if ($birthday) {
            $userFOS->setBirthday($birthday);
        }

        // addRole
        $guardRoles = $this->processGuardRolesInheritance($user['permission_name']);

        foreach ($guardRoles as $role) {
            $this->addUserToGroup($userFOS, $role, $output);
        }

        $output->writeln(sprintf('Persisting %d: %s %s', $id, $lastName, $firstName));

        return $userFOS;
    }

    /**
     * Process guard roles with inheritance (crmadmin : [crm])
     *
     * @param string $rolesString
     *
     * @return mixed[]
     */
    protected function processGuardRolesInheritance($rolesString)
    {
        $rolesArray = explode(',', $rolesString);

        $result = $rolesArray;

        if (sizeof($rolesArray)) {
            $rolesArray = array_combine($rolesArray, $rolesArray);

            foreach ($rolesArray as $key => $role) {
                if (isset($this->guardRoleInheritance[$role])) {
                    $inheritedRoles = $this->guardRoleInheritance[$role];

                    foreach ($inheritedRoles as $inheritedRole) {
                        if (isset($rolesArray[$inheritedRole])) {
                            unset($rolesArray[$inheritedRole]);
                        }
                    }
                }

                $result = array_values($rolesArray);
            }
        }

        return $result;
    }

    /**
     * Adds fos user to group depending on guard permission
     *
     * @param FOSUser         $userFOS
     * @param string          $role
     * @param OutputInterface $output
     */
    protected function addUserToGroup(FOSUser $userFOS, $role, OutputInterface $output)
    {
        if (isset($this->migrationsGroups[$role])) {
            $groupName = $this->migrationsGroups[$role]['group'];
            $groupRole = $this->migrationsGroups[$role]['role'];

            $groupFOS = $this->gm->findGroupByName($groupName);

            if (!$groupFOS) {
                $groupFOS = $this->gm->createGroup($groupName);
            }

            if (!$groupFOS->hasRole($groupRole)) {
                $groupFOS->addRole($groupRole);

                $this->gm->updateGroup($groupFOS);
            }

            $userFOS->addGroup($groupFOS);

            $output->writeln(
                sprintf(
                    'Setting role for %d: %s %s ROLE : %s',
                    $userFOS->getId(),
                    $userFOS->getLastName(),
                    $userFOS->getFirstName(),
                    $groupRole
                )
            );
        }
    }
}
