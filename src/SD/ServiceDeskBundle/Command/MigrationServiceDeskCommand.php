<?php

/**
 * Command class for deleting handling
 */

namespace SD\ServiceDeskBundle\Command;

use Lists\IndividualBundle\Entity\Individual;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;

/**
 * Class MigrationServiceDeskCommand
 */
class MigrationServiceDeskCommand extends ContainerAwareCommand
{

    /**
     * @var \Doctrine\ORM\EntityManager $em
     */

    protected $em;

    /**
     * @var Connection $connection
     */
    protected $connection;

    /**
     * {@inheritdoc}
     */
    protected function configure ()
    {
        $this
            ->setName('sd:migration:service-desk')
            ->setDescription('Db data migration to new structure');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getManager();

        $conn = $em->getConnection();


        /*
         * claim
         */
        $stmtClaim = $conn->prepare('
              SELECT * FROM claim
            ');
        $stmtClaim->execute();

        $claims = $stmtClaim->fetchAll();

        foreach ($claims as $claim) {
            $claimId = $claim['id'];
            $claimTypeId = $claim['claimtype_id'];
            $claimDepartmentId = $claim['departments_id'];
            $claimCreateDatetime = $claim['createdatetime'];
            $claimIsclosedclient = $claim['isclosedclient'];
            $claimStatusId = $claim['status_id'];
            $claimCloseDatetime = $claim['closedatetime'];
            $claimContractImportanceId = $claim['contract_importance_id'];
            $claimDescription = $claim['description'];
            $claimStuffDescription = $claim['stuffdescription'];
            $claimIsclosedstuff = $claim['isclosedstuff'];
            $claimOurcosts = $claim['ourcosts'];
            $claimBillNumber = $claim['bill_number'];
            $claimBillDescription = $claim['bill_description'];
            $claimSmetaStatusId = $claim['smeta_status_id'];
            $claimSmetaNumber = $claim['smeta_number'];
            $claimSmetaCosts = $claim['smeta_costs'];
            $claimMpk = $claim['mpk'];
            $claimBillDate = $claim['bill_date'];
            $claimOrganizationTypeId = $claim['organization_type_id'];
            $claimOrganizationImportanceId = $claim['organization_importance_id'];

        }


        /*
         * claim user
         */
        $stmtClaimUser = $conn->prepare('
              SELECT * FROM claimusers
            ');
        $stmtClaimUser->execute();

        $claimUsers = $stmtClaimUser->fetchAll();

        foreach ($claimUsers as $claimUser) {
            $userId = $claimUser['user_id'];
            $claimId = $claimUser['claim_id'];

            $userType = $claimUser['userkey'];
            $isRead = $claimUser['isread'];



        }

        /*
         * client_info
         */
        $stmtClient = $conn->prepare('
          SELECT * FROM client
            LEFT JOIN sf_guard_user ON client.user_id = sf_guard_user.id

        ');
        $stmtClient->execute();

        $clients = $stmtClient->fetchAll();
        //getting client info
        foreach($clients as $client) {
            $clientId = $client['client.id'];
            $clientPhone = $client['phone'];
            $clientMobilephone = $client['mobilephone'];

            $clientOrganizationId = $client['organization_id'];

            /*
             * user_info
             */

            $clFirstName = $client['first_name'];
            $userLastName = $client['last_name'];
            $userMiddleName = $client['middle_name'];
            $userPosition = $client['position'];
            $userEmail = $client['email_address'];
            $username = $client['username'];
            $userAlgorithm = $client['algorithm'];
            $userSalt = $client['salt'];
            $userPassword = $client['password'];
            $userIsActive = $client['is_active'];
            $userIsSuperAdmin = $client['is_super_admin'];
            $userLastLogin = $client['last_login'];
            $userSexID = $client['sex_id'];
            $userCreatedAt = $client['created_at'];

            /*
             * client departments
             */
            $stmtClientDepartment = $conn->prepare('
              SELECT * FROM client_departments
                WHERE client_id ='.$clientId.'

            ');
            $stmtClientDepartment->execute();

            $clientDepartments = $stmtClientDepartment->fetchAll();

            foreach ($clientDepartments as $clientDepartment) {
                $clientId = $clientDepartment['client_id'];
                $departmentId = $clientDepartment['departments_id'];
            }

            /*
             * client organization
             */
            $stmtClientOrganization = $conn->prepare('
              SELECT * FROM client_organization
                WHERE client_id ='.$clientId.'

            ');
            $stmtClientOrganization->execute();

            $clientOrganizations = $stmtClientOrganization->fetchAll();

            foreach ($clientOrganizations as $clientOrganization) {
                $clientId = $clientOrganization['client_id'];
                $organizationId = $clientOrganization['organization_id'];
            }

            //$output->writeln($echo);


        }



    }
}
