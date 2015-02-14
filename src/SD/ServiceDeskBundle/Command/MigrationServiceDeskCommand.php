<?php

/**
 * Command class for deleting handling
 */

namespace SD\ServiceDeskBundle\Command;

use Lists\IndividualBundle\Entity\Individual;
use SD\BusinessRoleBundle\Entity\CompanyClient;
use SD\ServiceDeskBundle\Entity\Claim;
use SD\ServiceDeskBundle\Entity\ClaimDepartment;
use SD\ServiceDeskBundle\Entity\ClaimType;
use SD\ServiceDeskBundle\Entity\OrganizationGrantedForOrder;
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
    protected function configure()
    {
        $this
            ->setName('sd:migration:service-desk')
            ->setDescription('Db data migration to new structure');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
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

            $stmtClaimStatus = $conn->prepare('
              SELECT * FROM status WHERE id = ' . $claimStatusId . '
            ');
            $stmtClaimStatus->execute();

            $claimStatus = $stmtClaimStatus->fetchAll()[0]['stakey'];

            // need to ask about mpk
            $claimBillDate = $claim['bill_date'];
            $claimOrganizationTypeId = $claim['organization_type_id'];
            $claimOrganizationImportanceId = $claim['organization_importance_id'];



            //new claim here

            $sd_claim = new ClaimDepartment();
            switch ($claimTypeId) {
                case 1:
                    $claimType = ClaimType::CLEANING;
                    break;
                case 2:
                    $claimType = ClaimType::TECH;
                    break;
                case 3:
                    $claimType = ClaimType::GREEN_COMFORT_IT;
                    break;
                case 4:
                    $claimType = ClaimType::CATERING;
                    break;
                case 5:
                    $claimType = ClaimType::COMPLAINT;
                    break;
                case 6:
                    $claimType = ClaimType::OPINION;
                    break;
                case 7:
                    $claimType = ClaimType::OTHER;
                    break;
                case 8:
                    $claimType = ClaimType::TRANSPORTATION;
                    break;
                case 9:
                    $claimType = ClaimType::PROPERTY;
                    break;
                case 10:
                    $claimType = ClaimType::PEST;
                    break;
                case 11:
                    $claimType = ClaimType::ACT_COLLECTING;
                    break;
            }
            $sd_claim->setType($claimType);
            $sd_claim->setStatus($claimStatus);
            //$sd_claim->setImportance();!!!
            $sd_claim->setCreatedAt($claimCreateDatetime);
            $sd_claim->setClosedAt($claimCloseDatetime);
            $sd_claim->setText($claimDescription);
            $sd_claim->setDisabled(false);
            $department = $doctrine
                ->getRepository('ListsDepartmentBundle:Departments')
                ->find($claimDepartmentId);
            $sd_claim->setTargetDepartment($department);
            //$sd_claim->setMessages();
            //$sd_claim->setCustomer();
            //$sd_claim->addClaimPerformerRules();

            /*
            * claim user
            */
            $stmtClaimUser = $conn->prepare('
              SELECT * FROM claimusers WHERE claim_id=' . $claimId . '
            ');
            $stmtClaimUser->execute();

            $claimUsers = $stmtClaimUser->fetchAll();

            foreach ($claimUsers as $claimUser) {
                $userId = $claimUser['user_id'];
                $claimId = $claimUser['claim_id'];

                $userType = $claimUser['userkey'];
                $isRead = $claimUser['isread'];

                //set clients and performers here due to userkey
                // but firstly create them
                //if client
                if ($userType == 'client') {
                    /*
                     * client_info
                     */
                    $stmtClient = $conn->prepare('
                      SELECT * FROM client WHERE user_id=' . $userId . '
                    ');
                    $stmtClient->execute();

                    $client = $stmtClient->fetchAll()[0];
                    //getting client info
                    //foreach($clients as $client) {
                    $clientId = $client['id'];
                    $clientPhone = $client['phone'];
                    $clientMobilephone = $client['mobilephone'];

                    $clientOrganizationId = $client['organization_id'];

                    /*
                     * user_info
                     */
                    $stmtUser = $conn->prepare('
                          SELECT * FROM sf_guard_user WHERE id = ' . $client['user_id'] . '
                        ');
                    $stmtUser->execute();

                    $user = $stmtUser->fetchAll()[0];

                    $userFirstName = $user['first_name'];
                    $userLastName = $user['last_name'];
                    $userMiddleName = $user['middle_name'];
                    $userPosition = $user['position'];
                    $userEmail = $user['email_address'];
                    $username = $user['username'];
                    $userAlgorithm = $user['algorithm'];
                    $userSalt = $user['salt'];
                    $userPassword = $user['password'];
                    $userIsActive = $user['is_active'];
                    $userIsSuperAdmin = $user['is_super_admin'];
                    $userLastLogin = $user['last_login'];
                    $userSexID = $user['sex_id'];
                    $userCreatedAt = $user['created_at'];

                    // new individual for client here
                    $sd_individual = new Individual();
                    $sd_individual->setMiddleName($userMiddleName);
                    $sd_individual->setLastName($userLastName);
                    $sd_individual->setFirstName($userFirstName);
                    //contacts here


                    // new client here
                    $organization = $doctrine
                        ->getRepository('ListsOrganizationBundle:Organization')
                        ->find($clientOrganizationId);

                    $sd_client = new CompanyClient();
                    $sd_client->setIndividual($sd_individual);
                    //$sd_client->addGrantedOrganization();

                    $checkClientOrganizations = $sd_client->getOriginOrganizations();

                    if (!in_array($organization, $checkClientOrganizations->toArray())) {
                        $sd_client->addOriginOrganization($organization);
                    }

                    /*
                     * client departments
                     */
                    $stmtClientDepartment = $conn->prepare('
                          SELECT * FROM client_departments
                            WHERE client_id =' . $clientId . '

                        ');
                    $stmtClientDepartment->execute();

                    $clientDepartments = $stmtClientDepartment->fetchAll();

                    foreach ($clientDepartments as $clientDepartment) {
                        $clientId = $clientDepartment['client_id'];
                        $departmentId = $clientDepartment['departments_id'];

                        $departmentAddClient = $doctrine
                            ->getRepository('ListsDepartmentBundle:Departments')
                            ->find($departmentId);

                        $sd_client->addDepartment($departmentAddClient);

                        $checkClientGrantedOrganizations = $sd_client->getGrantedOrganizations();
                        if (!in_array($departmentAddClient->getOrganization(), $checkClientGrantedOrganizations->toArray())) {
                            $sd_client->addGrantedOrganization($departmentAddClient->getOrganization());
                        }

                    }

                    /*
                     * client organization
                     */
                    $stmtClientOrganization = $conn->prepare('
                          SELECT * FROM client_organization
                            WHERE client_id =' . $clientId . '
                        ');
                    $stmtClientOrganization->execute();

                    $clientOrganizations = $stmtClientOrganization->fetchAll();

                    foreach ($clientOrganizations as $clientOrganization) {
                        $clientId = $clientOrganization['client_id'];
                        $organizationId = $clientOrganization['organization_id'];

                        $organization = $doctrine
                            ->getRepository('ListsOrganizationBundle:Organization')
                            ->find($clientOrganizationId);

                        $checkClientGrantedOrganizations = $sd_client->getGrantedOrganizations();
                        if (!in_array($organization, $checkClientGrantedOrganizations->toArray())) {
                            $sd_client->addGrantedOrganization($organization);
                        }
                    }

                    //$output->writeln($echo);


                    //}
                } else {
                    //add performers here
                }


            }

        }


    }
}
