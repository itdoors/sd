<?php

/**
 * Command class
 */

namespace SD\ServiceDeskBundle\Command;

use Lists\IndividualBundle\Entity\Contact;
use Lists\IndividualBundle\Entity\ContactType;
use Lists\IndividualBundle\Entity\Individual;
use SD\BusinessRoleBundle\Entity\ClaimPerformer;
use SD\BusinessRoleBundle\Entity\ClaimResponsibility;
use SD\BusinessRoleBundle\Entity\CompanyClient;
use SD\BusinessRoleBundle\Entity\GriffinStaff;
use SD\BusinessRoleBundle\Entity\Responsibility;
use SD\ServiceDeskBundle\Entity\Claim;
use SD\ServiceDeskBundle\Entity\ClaimDepartment;
use SD\ServiceDeskBundle\Entity\ClaimMessage;
use SD\ServiceDeskBundle\Entity\ClaimPerformerRule;
use SD\ServiceDeskBundle\Entity\ClaimType;
use SD\ServiceDeskBundle\Entity\ImportanceType;
use SD\ServiceDeskBundle\Entity\OrganizationGrantedForOrder;
use SD\ServiceDeskBundle\Entity\StatusType;
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

        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $conn = $em->getConnection();

        //test

        //updating organizations for clients/
        $stmt = $conn->prepare('
        UPDATE client SET organization_id = (SELECT organization_id FROM client_organization WHERE client_id = client.id Limit 1)  WHERE organization_id IS NULL
            ');
        $stmt->execute();
        $stmt = $conn->prepare('
        UPDATE claim SET status_id =1  WHERE status_id IS NULL
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
            $res = memory_get_usage ().'--'.'new claim:-->'.$claim['id'];
            $output->writeln($res);

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
            $res = memory_get_usage ().'--'.'new claim';
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


            $sd_claim->setImportance(ImportanceType::UNPLANNED);

            $sd_claim->setCreatedAt(new \DateTime($claimCreateDatetime));
            $sd_claim->setClosedAt(new \DateTime($claimCloseDatetime));

            if ($claimDescription == null) {
                $claimDescription = '';
            }
            $sd_claim->setText($claimDescription);
            $sd_claim->setDisabled(false);
            $department = $doctrine
                ->getRepository('ListsDepartmentBundle:Departments')
                ->find($claimDepartmentId);
            $sd_claim->setTargetDepartment($department);
            //$sd_claim->setMessages();
            //$sd_claim->setCustomer();
            //$sd_claim->addClaimPerformerRules();
            $res = memory_get_usage ().'--'.$claimType.' - '.$claimStatus.' - '.$claimCreateDatetime.' - '.$claimCloseDatetime.' - '.$claimDescription.' - '.$claimDepartmentId;
            $output->writeln($res);
            $res = memory_get_usage ().'--'.'______________________________________';
            $output->writeln($res);

            $em->persist($sd_claim);
            $em->flush();
            $em->refresh($sd_claim);

            $this->insertCommentMessage($sd_claim, $claimId, $output, $em);
            /*
            * claim user
            */
            $stmtClaimUser = $conn->prepare('
              SELECT * FROM claimusers WHERE claim_id=' . $claimId . '
            ');
            $stmtClaimUser->execute();

            $claimUsers = $stmtClaimUser->fetchAll();

            $usersInside = array();
            foreach ($claimUsers as $claimUser) {

                $userId = $claimUser['user_id'];
                if ($userId == 445) {
                    $userId = 243;
                }

                $res = memory_get_usage ().'--'.'next user:-->'.$userId.'-'.$claimUser['userkey'].'-claim:'.$claimId;
                $output->writeln($res);

                $claimId = $claimUser['claim_id'];

                $userType = $claimUser['userkey'];
                $isRead = $claimUser['isread'];

                //set clients and performers here due to userkey
                // but firstly create them
                //if client
                if ($userType == 'client') {
                    $res = memory_get_usage ().'--'.'create client-->';
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

                    $res = memory_get_usage ().'--'.'create individual contacts for user -->'.$claimUser['user_id'];
                    $output->writeln($res);

                    $user = $doctrine->getRepository('SDUserBundle:User')
                        ->find($claimUser['user_id']);

                    // new individual for client here
                    $sd_individual = $user->getIndividual();



                    $res = memory_get_usage ().'--'.$userMiddleName.' - '.$userLastName.' - '.$userFirstName.' - '.$clientOrganizationId;
                    $output->writeln($res);

                    // new client here
                    $organization = $doctrine
                        ->getRepository('ListsOrganizationBundle:Organization')
                        ->find($clientOrganizationId);

                    $res = memory_get_usage ().'--'.'create Company Client-->';
                    $output->writeln($res);

                    $roles = $sd_individual->getBusinessRoles();
                    $needRole = true;

                    foreach($roles as $role) {
                        if ($role instanceof CompanyClient) {
                            $needRole = false;
                            $sd_client = $role;
                        }
                    }
                    if ($needRole) {
                        $sd_client = new CompanyClient();
                        $sd_client->setIndividual($sd_individual);

                        //contacts here
                        $contact_1 = new Contact();
                        $contact_1->setIndividual($sd_individual);
                        $contact_1->setType(ContactType::TEL);
                        $contact_1->setValue($clientPhone);
                        $em->persist($contact_1);

                        $contact_2 = new Contact();
                        $contact_2->setIndividual($sd_individual);
                        $contact_2->setType(ContactType::TEL);
                        $contact_2->setValue($clientMobilephone);
                        $em->persist($contact_2);

                        $contact_3 = new Contact();
                        $contact_3->setIndividual($sd_individual);
                        $contact_3->setType(ContactType::EMAIL);
                        $contact_3->setValue($userEmail);
                        $em->persist($contact_3);

                        //$sd_client->addGrantedOrganization();
                        $res = memory_get_usage ().'--'.'test1';
                        $output->writeln($res);
                        $checkClientOrganizations = $sd_client->getOriginOrganizations();
                        $res = memory_get_usage ().'--'.'test2';
                        $output->writeln($res);

                        if (!in_array($organization, $checkClientOrganizations->toArray())) {
                        $sd_client->addOriginOrganization($organization);
                        $res = memory_get_usage ().'--'.'add organization ';
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
                            $res = memory_get_usage ().'--'.'add department :'.$departmentAddClient;
                            $output->writeln($res);

                            $checkClientGrantedOrganizations = $sd_client->getGrantedOrganizations();
                            if (!in_array($departmentAddClient->getOrganization(), $checkClientGrantedOrganizations->toArray())) {
                                $res = memory_get_usage ().'--'.'add granted organization :';
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
                            $res = memory_get_usage ().'--'.'add client granted organization :'.$organizationId;
                            $output->writeln($res);

                            $organization = $doctrine
                                ->getRepository('ListsOrganizationBundle:Organization')
                                ->find($clientOrganizationId);

                            $checkClientGrantedOrganizations = $sd_client->getGrantedOrganizations();
                            if (!in_array($organization, $checkClientGrantedOrganizations->toArray())) {
                                $sd_client->addGrantedOrganization($organization);
                                $res = memory_get_usage ().'--'.'add client granted organization :'.$organization;
                                $output->writeln($res);

                            }
                        }
                        $sd_claim->setCustomer($sd_client);
                        $em->persist($sd_client);
                        $em->persist($sd_claim);


                    }

                    $sd_claim->setCustomer($sd_client);
                    $em->persist($sd_client);

                    //$output->writeln($echo);


                    //}
                } else {
                    if (in_array($claimUser['user_id'], $usersInside)) {
                        continue;
                    }
                    $usersInside[] = $claimUser['user_id'];
                    // performers here
                    $stmtUser = $conn->prepare('
                          SELECT * FROM fos_user WHERE id = ' . $claimUser['user_id'] . '
                        ');

                    $stmtUser->execute();

                    $user = $stmtUser->fetchAll()[0];

                    $user = $doctrine->getRepository('SDUserBundle:User')
                        ->find($claimUser['user_id']);

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
                        $res = memory_get_usage ().'--'.'create new CLAIM PERFORMER ROLE--> '.$claimUser['user_id'];
                        $output->writeln($res);

                        $claimPerformer = new ClaimPerformer();
                        $claimPerformer->setIndividual($sd_individual);
                        $em->persist($claimPerformer);
                        $em->flush();
                        $em->refresh($claimPerformer);
                    }
                    $res = memory_get_usage ().'--'.'create new CLAIM PERFORMER RULE--> '.$claimUser['user_id'];
                    $output->writeln($res);

                    $performerRule = new ClaimPerformerRule();
                    $performerRule->setClaimPerformer($claimPerformer);

                    $performerRule->setCanEditFinanceData(false);
                    $performerRule->setCanPostToClients(false);
                    $performerRule->setClaimUpdated($isRead);
                    $performerRule->setClaim($sd_claim);
                    $sd_claim->addClaimPerformerRule($performerRule);
                    $em->persist($performerRule);
                    //$em->persist($sd_claim);
                    $em->flush();
                    //$em->refresh($performerRule);
                    //$em->persist($sd_claim);

                    $res = memory_get_usage ().'--'.'next id:-->'.$claimUser['user_id'];
                    $output->writeln($res);
                    //em
                }

            }
            $res = memory_get_usage ().'--'.'done:-->';
            $output->writeln($res);

            $em->flush();
            //$em->getUnitOfWork()->clear();
            $em->clear();

        }


    }


    private function createIndividuals() {
        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getManager();

        $conn = $em->getConnection();

        $stmtUser = $conn->prepare('
          DELETE FROM sd_claim_message WHERE user_id IN (28);
        ');

        $stmtUser->execute();
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
            $stmtStaff = $conn->prepare('SELECT * FROM stuff WHERE user_id='.$user['id']);

            $stmtStaff->execute();

            $stuff = $stmtStaff->fetchAll();

            $individual = new Individual();
            $individual->setFirstName($user['first_name']);
            $individual->setBirthday(new \DateTime($user['birthday']));
            $individual->setLastName($user['last_name']);
            $individual->setMiddleName($user['middle_name']);
            $em->persist($individual);


            if ($stuff) {
                $stuff = $stuff[0];
                $contact = new Contact();
                $contact->setIndividual($individual);
                $contact->setValue($stuff['mobilephone']);
                $contact->setType(ContactType::TEL);
                $em->persist($contact);

                $staff = new GriffinStaff();
                $staff->setIndividual($individual);

                if ($stuff['companystructure_id']) {
                    $companyStructure = $doctrine->getRepository('ListsCompanystructureBundle:Companystructure')
                        ->find($stuff['companystructure_id']);

                    $staff->setCompanystructure($companyStructure);
                }

                $em->persist($staff);

                $stmtStaffResponsibilities = $conn->prepare('SELECT * FROM stuff_departments
                    LEFT JOIN claimtype ON stuff_departments.claimtype_id = claimtype.id
                  WHERE stuff_id='.$stuff['id'].' ORDER BY departments_id');

                $stmtStaffResponsibilities->execute();

                $stuffResponsibilities = $stmtStaffResponsibilities->fetchAll();

                $departmentStuff_id = null;
                $claimTypes = array();
                foreach ($stuffResponsibilities as $stuffResponsibility) {
                    $claimTypes[] = $stuffResponsibility['name'];
                    if ($stuffResponsibility['departments_id'] != $departmentStuff_id && $departmentStuff_id != null) {
                        $departmentStuff_id = $stuffResponsibility['departments_id'];
                        $departmentStuff = $doctrine->getRepository('ListsDepartmentBundle:Departments')->find($departmentStuff_id);

                        $responsibility = new ClaimResponsibility();
                        $responsibility->setStaff($staff);
                        $responsibility->setDepartment($departmentStuff);
                        $responsibility->setClaimTypes($claimTypes);
                        $em->persist($responsibility);


                    }
                }

            }

            //em
            if ($user['id'] == 445) {
                $user['id'] = 243;
            }

            $userDb = $doctrine->getRepository('SDUserBundle:User')->find($user['id']);
            $userDb->setIndividual($individual);

            $em->flush();
            //$em->getUnitOfWork()->clear();
            $em->clear();
        }

    }

    private function insertCommentMessage($claim, $oldClaimId, $output, $em) {
        $doctrine = $this->getContainer()->get('doctrine');

        //$em = $doctrine->getManager();

        $conn = $em->getConnection();

        $stmtComment = $conn->prepare('
          SELECT * FROM comments WHERE claim_id = '.$oldClaimId.'
        ');

        $stmtComment->execute();

        $comments = $stmtComment->fetchAll();
        $counter = 0;
        foreach ($comments as $comment) {
            $counter++;
            $user = $doctrine->getRepository('SDUserBundle:User')
                ->find($comment['user_id']);

            $message = new ClaimMessage();
            $message->setClaim($claim);
            $message->setCreatedAt(new \DateTime($comment['createdatetime']));
            $message->setText($comment['description']);
            $message->setVisible(true);
            $message->setUser($user);
            $res = memory_get_usage ().'--'.'new message--> '.$comment['description'];
            $em->persist($message);
            $output->writeln($res);

            if ($counter == 2000) {
                $counter = 0;
                $em->flush();
            }

            //em
        }

        $res = memory_get_usage ().'--'.'<-- messages inserted--> ';
        $output->writeln($res);

        $em->flush();

    }
}