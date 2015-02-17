<?php

/**
 * Command class
 */

namespace SD\ServiceDeskBundle\Command;

use Lists\IndividualBundle\Entity\Contact;
use Lists\IndividualBundle\Entity\ContactType;
use Lists\IndividualBundle\Entity\Individual;
use SD\BusinessRoleBundle\Entity\ClaimPerformer;
use SD\BusinessRoleBundle\Entity\CompanyClient;
use SD\ServiceDeskBundle\Entity\Claim;
use SD\ServiceDeskBundle\Entity\ClaimDepartment;
use SD\ServiceDeskBundle\Entity\ClaimMessage;
use SD\ServiceDeskBundle\Entity\ClaimPerformerRule;
use SD\ServiceDeskBundle\Entity\ClaimType;
use SD\ServiceDeskBundle\Entity\OrganizationGrantedForOrder;
use SD\UserBundle\Entity\User;
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

        //updating organizations for clients/
        $stmt = $conn->prepare('
        UPDATE client SET organization_id = (SELECT organization_id FROM client_organization WHERE client_id = client.id Limit 1)  WHERE organization_id IS NULL
            ');
        $stmt->execute();

        $this->createIndividuals();

        /*
         * claim
         */
        $stmtClaim = $conn->prepare('SELECT * FROM claim');
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
              SELECT * FROM status WHERE id = '.$claimStatusId .'
            ');
            $stmtClaimStatus->execute();

            $claimStatus = $stmtClaimStatus->fetchAll()[0]['stakey'];

            // need to ask about mpk
            $claimBillDate = $claim['bill_date'];
            $claimOrganizationTypeId = $claim['organization_type_id'];
            $claimOrganizationImportanceId = $claim['organization_importance_id'];



            //new claim here
            $res = 'new claim';
            $output->writeln($res);

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
            $res = $claimType.' - '.$claimStatus.' - '.$claimCreateDatetime.' - '.$claimCloseDatetime.' - '.$claimDescription.' - '.$claimDepartmentId;
            $output->writeln($res);
            $res = '______________________________________';
            $output->writeln($res);

            $this->insertCommentMessage($sd_claim, $claimId, $output);
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
                    $res = 'create client-->';
                    $output->writeln($res);

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
/*                    $username = $user['username'];
                    $userAlgorithm = $user['algorithm'];
                    $userSalt = $user['salt'];
                    $userPassword = $user['password'];
                    $userIsActive = $user['is_active'];
                    $userIsSuperAdmin = $user['is_super_admin'];
                    $userLastLogin = $user['last_login'];
                    $userSexID = $user['sex_id'];
                    $userCreatedAt = $user['created_at'];*/

                    $res = 'create individual contacts for user -->'.$claimUser['user_id'];
                    $output->writeln($res);

                    $user = $doctrine->getRepository('SDUserBundle:User')
                        ->find($claimUser['user_id']);

                    // new individual for client here
                    $sd_individual = $user->getIndividual();


                    //contacts here
                    $contact_1 = new Contact();
                    $contact_1->setIndividual($sd_individual);
                    $contact_1->setType(ContactType::TEL);
                    $contact_1->setValue($clientPhone);

                    $contact_2 = new Contact();
                    $contact_2->setIndividual($sd_individual);
                    $contact_2->setType(ContactType::TEL);
                    $contact_2->setValue($clientMobilephone);

                    $contact_3 = new Contact();
                    $contact_3->setIndividual($sd_individual);
                    $contact_3->setType(ContactType::EMAIL);
                    $contact_3->setValue($userEmail);


                    $res = $userMiddleName.' - '.$userLastName.' - '.$userFirstName.' - '.$clientOrganizationId;
                    $output->writeln($res);

                    // new client here
                    $organization = $doctrine
                        ->getRepository('ListsOrganizationBundle:Organization')
                        ->find($clientOrganizationId);

                    $res = 'create Company Client-->';
                    $output->writeln($res);

                    $sd_client = new CompanyClient();
                    $sd_client->setIndividual($sd_individual);
                    //$sd_client->addGrantedOrganization();

                    $checkClientOrganizations = $sd_client->getOriginOrganizations();

                    if (!in_array($organization, $checkClientOrganizations->toArray())) {
                        $sd_client->addOriginOrganization($organization);
                        $res = 'add organization :'.$organization;
                        $output->writeln($res);
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
                        $res = 'add department :'.$departmentAddClient;
                        $output->writeln($res);

                        $checkClientGrantedOrganizations = $sd_client->getGrantedOrganizations();
                        if (!in_array($departmentAddClient->getOrganization(), $checkClientGrantedOrganizations->toArray())) {
                            $res = 'add granted organization :'.$departmentAddClient->getOrganization();
                            $output->writeln($res);
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
                        $res = 'add client granted organization :'.$organizationId;
                        $output->writeln($res);

                        $organization = $doctrine
                            ->getRepository('ListsOrganizationBundle:Organization')
                            ->find($clientOrganizationId);

                        $checkClientGrantedOrganizations = $sd_client->getGrantedOrganizations();
                        if (!in_array($organization, $checkClientGrantedOrganizations->toArray())) {
                            $sd_client->addGrantedOrganization($organization);
                            $res = 'add client granted organization :'.$organization;
                            $output->writeln($res);

                        }
                    }

                    //$output->writeln($echo);


                    //}
                } else {
                    // performers here
                    $stmtUser = $conn->prepare('
                          SELECT * FROM fos_user WHERE id = ' . $claimUser['user_id'] . '
                        ');

                    $stmtUser->execute();

                    $user = $stmtUser->fetchAll()[0];

                    $user = $doctrine->getRepository('SDUserBundle:User')
                        ->find($claimUser['user_id']);

                    $res = 'create individual contacts-->';
                    $output->writeln($res);

                    // new individual for client here
                    $sd_individual = $user->getIndividual();

                    $roles = $sd_individual->getBusinessRoles();
                    $needRole = true;
                    foreach($roles as $role) {
                        if ($role instanceof ClaimPerformer) {
                            $needRole = false;
                            $claimPerformer = $role;
                        }
                    }
                    if ($needRole) {
                        $claimPerformer = new ClaimPerformer();
                        $claimPerformer->setIndividual($sd_individual);
                    }

                    $performerRule = new ClaimPerformerRule();
                    $performerRule->setClaimPerformer($claimPerformer);
                    $performerRule->setClaim($sd_claim);
                    $performerRule->setCanEditFinanceData(false);
                    $performerRule->setCanPostToClients(false);
                    $performerRule->setClaimUpdated($isRead);

                    //em
                }


            }

        }


    }


    private function createIndividuals() {
        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getManager();

        $conn = $em->getConnection();

        $stmtUser = $conn->prepare('
          DELETE FROM fos_user WHERE id IN (28);
        ');

        $stmtUser->execute();

        $stmtUser = $conn->prepare("
          INSERT INTO fos_user (id,username,username_canonical,email,email_canonical,enabled,salt,password,last_login,locked,expired,
            expires_at,confirmation_token,password_requested_at,roles,credentials_expired,credentials_expire_at,first_name,last_name,middle_name,position,is_blocked,
            is_fired,birthday,photo,peer_id,peer_password,position_id,individual_id)
            SELECT id,username,username,email_address,email_address,true,salt,password,null,FALSE,false,
            null,null,null, 'a:0:{}',false,null, first_name,	last_name, middle_name,position,true,
            	null,	birthday,null,null,null,null,null FROM sf_guard_user WHERE id = 28;
        ");

        $stmtUser->execute();
        $stmtUser = $conn->prepare('
          DELETE FROM client WHERE user_id IN (101, 92, 257);
        ');

        $stmtUser->execute();
        $stmtUser = $conn->prepare('
          DELETE FROM sf_guard_user WHERE id IN (101, 92, 257);
        ');

        $stmtUser->execute();
        $stmtUser = $conn->prepare('
          SELECT * FROM fos_user
        ');

        $stmtUser->execute();

        $users = $stmtUser->fetchAll();

        foreach ($users as $user) {
            $individual = new Individual();
            $individual->setFirstName($user['first_name']);
            $individual->setBirthday($user['birthday']);
            $individual->setLastName($user['last_name']);
            $individual->setMiddleName($user['middle_name']);

            //em

            $userDb = $doctrine->getRepository('SDUserBundle:User')->find($user['id']);
            $userDb->setIndividual($individual);

            //em
        }

    }

    private function insertCommentMessage($claim, $oldClaimId, $output) {
        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getManager();

        $conn = $em->getConnection();

        $stmtComment = $conn->prepare('
          SELECT * FROM comments WHERE claim_id = '.$oldClaimId.'
        ');

        $stmtComment->execute();

        $comments = $stmtComment->fetchAll();

        foreach ($comments as $comment) {
            $user = $doctrine->getRepository('SDUserBundle:User')
                ->find($comment['user_id']);

            $message = new ClaimMessage();
            $message->setClaim($claim);
            $message->setCreatedAt($comment['createdatetime']);
            $message->setText($comment['description']);
            $message->setVisible(true);
            $message->setUser($user);
            $res = 'new message--> '.$comment['description'];
            $output->writeln($res);

            //em
        }
    }

    private function addStuff() {
        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getManager();

        $conn = $em->getConnection();

        $stmtStuff = $conn->prepare('
          SELECT * FROM stuff
        ');

        $stmtStuff->execute();

        $stuffs = $stmtStuff->fetchAll();

    }
}
