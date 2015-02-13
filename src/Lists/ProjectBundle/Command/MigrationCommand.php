<?php

/**
 * Command class for deleting handling
 */
namespace Lists\ProjectBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;
use Lists\ProjectBundle\Entity\ServiceProjectStateTender;
use Lists\ProjectBundle\Entity\StatusProjectStateTender;

/**
 * Class MigrationCommand
 */
class MigrationCommand extends ContainerAwareCommand
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
          ->setName('lists:project:migration')
          ->setDescription('Mifration handling to project');
    }
    private function saveService($service)
    {
        $servicesNew = $this->em->getRepository('ListsProjectBundle:Service')->findOneBy(
            array(
                'name' => $service->getName()
            ));
        if (!$servicesNew && $service->getSlug() == 'project') {
            $servicesNew = new \Lists\ProjectBundle\Entity\ServiceProjectSimple();
            $servicesNew->setName($service->getName());
            $servicesNew->setReportNumber($service->getReportNumber());
            $servicesNew->setSlug($service->getSlug());
            $servicesNew->setSortorder($service->getSortorder());
            $this->em->persist($servicesNew);
        }
        if (!$servicesNew && $service->getSlug() == 'gos_tender') {
            $servicesNew = new ServiceProjectStateTender();
            $servicesNew->setName($service->getName());
            $servicesNew->setReportNumber($service->getReportNumber());
            $servicesNew->setSlug($service->getSlug());
            $servicesNew->setSortorder($service->getSortorder());
            $this->em->persist($servicesNew);
        }
        return $servicesNew;
    }
    private function saveStatus($val)
    {
        if (!$val) {
            return null;
        }
        $statusNew = $this->em->getRepository('ListsProjectBundle:Status')->findOneBy(
            array(
                'name' => $val->getName(),
                'alias' => $val->getAlias()
            ));
        if (!$statusNew) {
            $statusNew = $this->em->getRepository('ListsProjectBundle:Status')->findOneBy(
                array(
                    'name' => 'Проработка',
                    'alias' => 'study'
            ));
            
        }
        return $statusNew;
    }
    private function saveType($val)
    {
        $type = $this->em->getRepository('ListsProjectBundle:MessageType')->findOneBy(
            array(
                'name' => $val->getName()
            ));
        if (!$type) {
            $type = new \Lists\ProjectBundle\Entity\MessageType();
            $type->setName($val->getName());
            $type->setIsReport($val->getIsReport());
            $type->setReportName($val->getReportName());
            $type->setReportSortorder($val->getReportSortorder());
            $type->setSlug($val->getSlug());
            $type->setSortorder($val->getSortorder());
            $type->setStayActionTime($val->getStayActionTime());
            $this->em->persist($type);
        }
        return $type;
    }
    private function saveManager($handling, $project)
    {
       $managers = $handling->getHandlingUsers();
       $part = 0;
       foreach ($managers as $manager) {
           $user = $manager->getUser();
           $part = ($part+$manager->getPart()) > 100 ? $part :  ($part+$manager->getPart());
           if ($project->getId()) {
            $projectManager = $this->em->getRepository('ListsProjectBundle:Manager')->findOneBy(
             array(
                 'project' => $project,
                 'user' => $user,
             ));
           } else {
               $projectManager = null;
           }
           if (!$projectManager) {
                if ($manager->getLookupId() == 62) {
                     if ($project->getId()) {
                        $projectManager = $this->em->getRepository('ListsProjectBundle:ManagerProjectType')->findOneBy(
                            array(
                                'project' => $project
                            ));
                     } else {
                         $projectManager = null;
                     }
                    if ($projectManager) {
                        $projectManager = new \Lists\ProjectBundle\Entity\ManagerType();
                    } else {
                        $projectManager = new \Lists\ProjectBundle\Entity\ManagerProjectType();
                    }
                } else {
                    $projectManager = new \Lists\ProjectBundle\Entity\ManagerType();
                }
                $projectManager->setPart(($part+$manager->getPart()) > 100 ? (100-$part) : $manager->getPart());
                $projectManager->setProject($project);
                $projectManager->setUser($user);
                $this->em->persist($projectManager);
           }
       }
    }
    private function addFile($project)
    {
       $fileTypes = $this->em->getRepository('ListsProjectBundle:ProjectFileType')
            ->findBy(array ('group' => 'commercial_offer'));
        foreach ($fileTypes as $type) {
            $file = new \Lists\ProjectBundle\Entity\FileProject();
            $file->setProject($project);
            $file->setType($type);
            $file->setUser($project->getUser());
            $this->em->persist($file);
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('START');
        /** @var \Doctrine\ORM\EntityManager $em */
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // перенос сервисов
        $services = $this->em->getRepository('ListsHandlingBundle:HandlingService')->findAll();
        foreach ($services as $service) {
            $this->saveService($service);
        }
        $output->writeln('END SERVICE');

        // перенос статусов
        $status = $this->em->getRepository('ListsHandlingBundle:HandlingStatus')->findAll();
        foreach ($status as $val) {
            $this->saveStatus($val);
        }
        $output->writeln('END STATUS');
        
        // перенос типы обращений
        $types = $this->em->getRepository('ListsHandlingBundle:HandlingMessageType')->findBy(array(), array('id' => 'ASC'));
        foreach ($types as $val) {
            $this->saveType($val);
        }
        $output->writeln('END TYPE');
        
         // перенос проектов
        $handlings = $this->em->getRepository('ListsHandlingBundle:Handling')->findAll();
        foreach ($handlings as $val) {
            $project = $this->em->getRepository('ListsProjectBundle:Project')->findOneBy(
                array(
                    'createDatetime' => $val->getCreatedatetime(),
                    'organization' => $val->getOrganization(),
                    'createDate' => $val->getCreatedate(),
                    'userCreated' => $val->getUser(),
                ));
            if (!$project) {
                $type = $val->getType();
                if ($type) {
                    if($type->getId() == 2) {
                        $project = new \Lists\ProjectBundle\Entity\ProjectCommercialTender();
                        $services = $val->getHandlingServices();
                        foreach ($services as $service) {
                            $serviceProject = $this->saveService($service);
                            $project->addService($serviceProject);
                        }
                        
                    } elseif ($type->getId() == 8) {
                        $project = new \Lists\ProjectBundle\Entity\ProjectElectronicTrading();
                    } elseif ($type->getId() == 4) {
                        continue;
                    } else {
                        $project = new \Lists\ProjectBundle\Entity\ProjectSimple();
                    }
                } else {
                    $project = new \Lists\ProjectBundle\Entity\ProjectSimple();
                }
                $project->setCreateDate($val->getCreatedate());
                $project->setCreateDatetime($val->getCreatedatetime());
                $project->setDatetimeClosed($val->getDatetimeClosed()?$val->getDatetimeClosed():$val->getClosedatetime());
                $project->setDeletedDatetime($val->getDeletedAt());
                $project->setDescription($val->getDescription());
                $project->setIsClosed($val->getIsClosed());
                $project->setOrganization($val->getOrganization());
                $project->setReasonClosed($val->getReasonClosed());
                $project->setSquare($val->getSquare()); 
                $project->setStatusAccess(true);
                $project->setStatusChangeDate($val->getStatusChangeDate());
                $project->setUserClosed($val->getClosedUser()? $val->getClosedUser(): $val->getCloser());
                $project->setUserCreated($val->getUser());
                
                $output->writeln('ADD PROJECT');
                
                $this->addFile($project);
                
                $output->writeln('END FILE');
                $this->em->persist($project);
                
                $this->saveManager($val, $project);
                $output->writeln('ADD MANAGER');
            }
        }
        $output->writeln('END PROJECT');
        
         // перенос проектов гос тендеры
        $gosTenders = $this->em->getRepository('ListsHandlingBundle:ProjectGosTender')->findAll();
        foreach ($gosTenders as $val) {
            $project = $this->em->getRepository('ListsProjectBundle:ProjectStateTender')->findOneBy(
                array(
                    'advert' => $val->getAdvert()
                ));
            if (!$project) {
                $project = new \Lists\ProjectBundle\Entity\ProjectStateTender();
                $project->setCreateDate($val->getProject()->getCreatedate());
                $project->setCreateDatetime($val->getProject()->getCreatedatetime());
                $project->setBudget($val->getProject()->getBudget());
                $project->setDatetimeClosed($val->getProject()->getDatetimeClosed()?$val->getProject()->getDatetimeClosed():$val->getProject()->getClosedatetime());
                $project->setDatetimeDeadline($val->getDatetimeDeadline());
                $project->setDatetimeOpening($val->getDatetimeOpening());
                $project->setDeletedDatetime($val->getProject()->getDeletedAt());
                $project->setDelivery($val->getDelivery());
                $project->setDescription($val->getProject()->getDescription());
                $project->setIsClosed($val->getProject()->getIsClosed());
                $project->setIsParticipation($val->getIsParticipation());
                $project->setOrganization($val->getProject()->getOrganization());
                $project->setPf($val->getProject()->getPf1());
                $project->setPlace($val->getPlace());
                $project->setReason($val->getReason());
                $project->setReasonClosed($val->getProject()->getReasonClosed());
                $project->setSoftware($val->getSoftware());
                $project->setSquare($val->getProject()->getSquare());
                $project->setStatusAccess(true);
                $project->setStatusChangeDate($val->getProject()->getStatusChangeDate());
                $status = $this->saveStatus($val->getProject()->getStatus());
                if (!$status) {
                    $output->writeln('status not found' .' for '.$val->getId());
                }
                $project->setStatusProjectStateTender($status);
                $project->setTypeOfProcedure($val->getTypeOfProcedure());
                $project->setUserClosed($val->getProject()->getClosedUser()? $val->getProject()->getClosedUser(): $val->getProject()->getCloser());
                $project->setUserCreated($val->getProject()->getUser());
                $project->setVdz($val->getVdz());
                $project->setAdvert($val->getAdvert());
                
                $files = $val->getFiles();
                foreach ($files as $file) {
                    $addFile = new \Lists\HandlingBundle\Entity\ProjectFile();
                    $addFile->setCreateDatetime($file->getCreateDatetime());
                    $addFile->setDeletedDatetime($file->getDeletedDatetime());
                    $addFile->setFile($file->getFile());
                    $addFile->setName($file->getName());
                    $addFile->setProject($project);
                    $addFile->setShortText($file->getShortText());
                    $addFile->setType($file->getType());
                    $addFile->setUser($file->getUser());
                    $this->em->persist($addFile);
                }
                $this->em->persist($project);
                
                $this->saveManager($val->getProject(), $project);
            }
        }
        $output->writeln('END PROJECT GOS TENDER');

        $this->em->flush();
        $output->writeln('END');
    }
}
