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
     /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('START');
        /** @var \Doctrine\ORM\EntityManager $em */
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        // перенос типы обращений
//        $types = $this->em->getRepository('ListsHandlingBundle:HandlingMessageType')->findBy(array(), array('id' => 'ASC'));
//        foreach ($types as $val) {
//            $this->saveType($val);
//        }
//        $output->writeln('END TYPE');
        
        // перенос услуг
        $services = $this->em->getRepository('ListsHandlingBundle:HandlingService')->findAll();
        foreach ($services as $val) {
            $this->saveService($val);
        }
        $output->writeln('END SERVICE');
        
        // перенос услуг
        $organizationServices = $this->em->getRepository('ListsOrganizationBundle:OrganizationServiceCover')->findAll();
        foreach ($organizationServices as $val) {
            $oldService = $val->getService();
            $newService = $oldService->getService();
            $val->setProjectService($newService);
            $this->em->persist($val);
        }
        $this->em->flush();
        $output->writeln('END SERVICE ORGANIZATION');
        
         // перенос проектов
        $handlings = $this->em->getRepository('ListsHandlingBundle:Handling')->findAll();
        foreach ($handlings as $val) {
            $this->handlingToProject($val, $output);
        }
        $this->em->flush();
        $output->writeln('flush project');
         // перенос сообщений
        foreach ($handlings as $val1) {
                $project = $this->em->getRepository('ListsProjectBundle:Project')->findOneBy(
                    array(
                        'createDatetime' => $val1->getCreatedatetime(),
                        'organization' => $val1->getOrganization(),
                        'createDate' => $val1->getCreatedate(),
                        'userCreated' => $val1->getUser()
                    ));
            if ($project) {
                $this->message($val1, $project, $output);
            }
        }
        $output->writeln('END MESSAGE');
        
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
                $project->setStatus($status);
                $services = $val->getProject()->getHandlingServices();
                foreach ($services as $service) {
                    $output->writeln($service);
                    $serviceProject = $this->saveService($service);
                    $project->addService($serviceProject);
                }
                
                $project->setTypeOfProcedure($val->getTypeOfProcedure());
                $project->setUserClosed($val->getProject()->getClosedUser()? $val->getProject()->getClosedUser(): $val->getProject()->getCloser());
                $project->setUserCreated($val->getProject()->getUser());
                $project->setVdz($val->getVdz());
                $project->setAdvert($val->getAdvert());
                
                $files = $val->getProject()->getFiles();
                $this->em->persist($project);
                $this->em->flush();
                foreach ($files as $file) {
                    $type = $this->em->getRepository('ListsProjectBundle:ProjectFileType')->find($file->getType()->getId());
                    $addFile = new \Lists\ProjectBundle\Entity\FileProject();
                    $addFile->setCreateDatetime($file->getCreateDatetime());
                    $addFile->setDeletedDatetime($file->getDeletedDatetime());
                    $addFile->setFile($file->getFile());
                    $addFile->setName($file->getName());
                    $addFile->setProject($project);
                    $addFile->setShortText($file->getShortText());
                    $addFile->setType($type);
                    $addFile->setUser($file->getUser());
                    $this->em->persist($addFile);
                    
                    $this->copyFile($file, $project, $file->getFile(), $output);
                    
                }
                
                
                $this->saveManager($val->getProject(), $project, $output);
            }
        }
        
        
        $output->writeln('END PROJECT GOS TENDER');

        $this->em->flush();
        $output->writeln('END');
    }
// Услуги
    private function saveService($service)
    {
        // поиск
        $servicesNew = $this->em->getRepository('ListsProjectBundle:Service')->findOneBy(
            array(
                'name' => $service->getName()
            ));
       // если не нашли добавить по типу
        if (!$servicesNew && $service->getSlug() == 'project') {
            $servicesNew = new \Lists\ProjectBundle\Entity\ServiceProjectSimple();
            $servicesNew->setName($service->getName());
            $servicesNew->setReportNumber($service->getReportNumber());
            $servicesNew->setSlug($service->getSlug());
            $servicesNew->setSortorder($service->getSortorder());
            $this->em->persist($servicesNew);
            $this->em->flush();
        }
        if (!$servicesNew && $service->getSlug() == 'gos_tender') {
            $servicesNew = new ServiceProjectStateTender();
            $servicesNew->setName($service->getName());
            $servicesNew->setReportNumber($service->getReportNumber());
            $servicesNew->setSlug($service->getSlug());
            $servicesNew->setSortorder($service->getSortorder());
            $this->em->persist($servicesNew);
            $this->em->flush();
        }
        // указать новый ид
        if ($service->getService() == null) {
            $service->setService($servicesNew);
            $this->em->persist($service);
            $this->em->flush();
        }
        return $servicesNew;
    }
// статусы
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

// менеджеры
    private function saveManager($handling, $project, $output)
    {
       $managers = $handling->getHandlingUsers();
       $part = 0;
       $isMax = false;
       foreach ($managers as $manager) {
           $user = $manager->getUser();
           if (($part+$manager->getPart()) > 100) {
               $isMax = true;
           } else {
               $part += $manager->getPart();
           }
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
                $projectManager->setProject($project);
                $projectManager->setUser($user);
            }
            $projectManager->setPart($isMax ? 0 : $manager->getPart());
            $this->em->persist($projectManager);
        }
        if ($part != 100) {
            $output->writeln('ERROR PART IN HANDLING ID: '.$handling->getId(), ' PROJECT ID: '.$project->getId());
        }
    }
// проект
    private function handlingToProject($handling, $output)
    {
        $project = $this->em->getRepository('ListsProjectBundle:Project')->findOneBy(
            array(
                'createDatetime' => $handling->getCreatedatetime(),
                'organization' => $handling->getOrganization(),
                'createDate' => $handling->getCreatedate(),
                'userCreated' => $handling->getUser()
            ));
        
        $isAddProject = false;
        if (!$project) {
            $type = $handling->getType();
            if ($type) {
                if($type->getId() == 2) {
                    $project = new \Lists\ProjectBundle\Entity\ProjectCommercialTender();
                    $services = $handling->getHandlingServices();
                    foreach ($services as $service) {
                        $serviceProject = $this->saveService($service);
                        $project->addService($serviceProject);
                    }
                } elseif ($type->getId() == 8) {
                    $project = new \Lists\ProjectBundle\Entity\ProjectElectronicTrading();
                } elseif ($type->getId() == 4) {
                    return null;
                } else {
                    $project = new \Lists\ProjectBundle\Entity\ProjectSimple();
                }
            } else {
                $project = new \Lists\ProjectBundle\Entity\ProjectSimple();
            }
            $isAddProject = true;
            $fileTypes = $this->em->getRepository('ListsProjectBundle:ProjectFileType')
            ->findBy(array ('group' => 'simple'));
            foreach ($fileTypes as $typeFile) {
                $file = new \Lists\ProjectBundle\Entity\FileProject();
                $file->setProject($project);
                $file->setType($typeFile);
                $this->em->persist($file);
            }
        } else {
            $fileTypes = $this->em->getRepository('ListsProjectBundle:ProjectFileType')
                ->findBy(array ('group' => 'simple'));
            foreach ($fileTypes as $typeFile) {
                $fileProject = $this->em->getRepository('ListsProjectBundle:FileProject')
                    ->findBy(array (
                        'project' => $project,
                        'type' => $typeFile
                    ));
                if (!$fileProject) {
                    $file = new \Lists\ProjectBundle\Entity\FileProject();
                    $file->setProject($project);
                    $file->setType($typeFile);
                    $this->em->persist($file);
                }
            }
        }
       
        
        $project->setCreateDate($handling->getCreatedate());
        $project->setCreateDatetime($handling->getCreatedatetime());
        $project->setDatetimeClosed($handling->getDatetimeClosed()?$handling->getDatetimeClosed():$handling->getClosedatetime());
        $project->setDeletedDatetime($handling->getDeletedAt());
        $project->setDescription($handling->getDescription());
        $project->setIsClosed($handling->getIsClosed());
        $project->setOrganization($handling->getOrganization());
        $project->setReasonClosed($handling->getReasonClosed());
        $project->setSquare($handling->getSquare()); 
        $project->setUserCreated($handling->getUser()); 
        $project->setStatusAccess(true);
        $project->setStatusChangeDate($handling->getStatusChangeDate());
        $project->setUserClosed($handling->getClosedUser()? $handling->getClosedUser(): $handling->getCloser());
        
//        $status = $this->saveStatus($handling->getStatus());
        $status = $this->em->getRepository('ListsProjectBundle:Status')->findOneBy(
                array(
                    'name' => 'Проработка',
                    'alias' => 'study'
            ));
        $project->setStatus($status);
        
        $this->em->persist($project);
        $output->writeln(($isAddProject?'ADD PROJECT':'UPDATE PROJECT ID:').' '. $project->getId());

        $this->saveManager($handling, $project, $output);
        
        $handling->setProject($project);
        $this->em->persist($handling);

        return $project;
    }
// типы обращений
    private function saveType($val)
    {
        if (!$val) {
            return null;
        } 
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
            $this->em->flush();
        }
        return $type;
    }
    
// обращения
    private function message($handling, $project, $output)
    {
        $messages = $handling->getHandlingMessages();
        foreach ($messages as $messageOld) {
            $message = $this->em->getRepository('ListsProjectBundle:Message')->findOneBy(
                array(
                    'createDatetime' => $messageOld->getCreatedatetime(),
                    'eventDatetime' => $messageOld->getCreatedate(),
                    'user' => $messageOld->getUser(),
                ));
            $type = $this->saveType($messageOld->getType());
            
            if(!$message) {
                if ($messageOld->getAdditionalType() == 'fm') {
                    $message = new \Lists\ProjectBundle\Entity\MessagePlanned();
                } else {
                    $message = new \Lists\ProjectBundle\Entity\MessageCurrent();
                }
            }
            
            $message->setContact($messageOld->getContact());
            $message->setCreateDatetime($messageOld->getCreatedatetime());
            $message->setDescription($messageOld->getDescription());
            $message->setEventDatetime($messageOld->getCreatedate());
            $message->setProject($project);
            $message->setUser($messageOld->getUser());
            $message->setType($type);

            $this->em->persist($message);

            $this->copyFileMessage($messageOld, $project, $message, $output);
        }
    }
     private function copyFileMessage($handlingMessage, $project, $message, $output){
        if (!$project) {
            $output->writeln('PROJECT is OLD GOS TENDER');
        }
        if (!$project->getId()) {
            $output->writeln('PROJECT ID NOT FOUND FOR directory');
        }
        $dir = __DIR__.'/../../../../web/uploads';
        $dirNew = $dir.'/project';
        if (!is_dir($dirNew)){
            mkdir($dirNew);
        }
        $dirNew .= '/'.$project->getId();
        if (!is_dir($dirNew)){
            mkdir($dirNew);
        }
        $filesMessage = $handlingMessage->getFiles();
        foreach ($filesMessage as $file) {
            $addFile = null;
            if ($message->getId() != '') {
            $addFile = $this->em->getRepository('ListsProjectBundle:FileMessage')->findOneBy(
                array(
                    'message' => $message,
                    'createDatetime' => $file->getCreatedate(),
                    'name' => $file->getFile(),
                ));
            }
            if (!$addFile) {
                $addFile = new \Lists\ProjectBundle\Entity\FileMessage();
                $addFile->setCreateDatetime($file->getCreatedate());
                $addFile->setMessage($message);
                $addFile->setName($file->getFile());
                $addFile->setUser($file->getHandlingMessage()->getUser());
                $this->em->persist($addFile);
                
                $fileOld = $file->getAbsolutePath();
                $fileNew = $dirNew.'/'.$file->getFile();
                if (!is_file($fileOld) && $file->getFile() != '') {
                    $output->writeln('File dont found: '.$fileOld . ' FOR ID: '.$file->getId());
                    return false;
                }
                if (!is_dir($dirNew)) {
                    $output->writeln('Directory dont found: '.$dirNew);
                    return false;
                }
                if (!is_file($fileOld)) {
                    $output->writeln('File dont found: '.$fileOld . ' FOR ID: '.$file->getId());
                    return false;
                }
                copy($fileOld, $fileNew);
            }
        }
        
    }
//    private function copyFileType($project)
//    {
//       $fileTypes = $this->em->getRepository('ListsProjectBundle:ProjectFileType')
//            ->findBy(array ('group' => 'commercial_offer'));
//        foreach ($fileTypes as $type) {
//            $file = new \Lists\ProjectBundle\Entity\FileProject();
//            $file->setProject($project);
//            $file->setType($type);
//            $file->setUser($project->getUser());
//            $this->em->persist($file);
//        }
//    }
   
    private function copyFile($handling, $project, $fileNAme, $output){
        if (!$project->getId()) {
            $output->writeln('PROJECT ID NOT FOUND FOR directory');
        }
        
        $dir = __DIR__.'/../../../../web/uploads';
        
        $dirOld = $dir.'/projects';
        $dirNew = $dir.'/project';
        if (!is_dir($dirNew)){
            mkdir($dirNew);
        }
        
        $dirNew .= '/'.$project->getId();
        if (!is_dir($dirNew)){
            mkdir($dirNew);
        }
        $fileOld = $dirOld.'/'.$handling->getId().'/'.$fileNAme;
        $fileNew = $dirNew.'/'.$fileNAme;
             
        if (!is_file($fileOld)) {
            $output->writeln('File dont found: '.$fileOld . ' FOR ID: '.$project->getId());
            return false;
        }
        copy($fileOld, $fileNew);
    }
   
}
