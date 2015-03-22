<?php

namespace Lists\ProjectBundle\Service;

use Doctrine\ORM\EntityManager;
use Lists\ProjectBundle\Classes\ProjectAccessFactory;
use SD\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Lists\ProjectBundle\Entity\ProjectStateTender;
use Lists\ProjectBundle\Entity\ProjectSimple;

/**
 * ProjectService class
 */
class ProjectService
{
    /** @var SecurityContext $context */
    protected $context;
    /** @var EntityManager $em */
    protected $em;
    /** @var User $user */
    protected $user;

    /**
     * __construct
     *
     * @param SecurityContext $context
     */
    public function __construct(SecurityContext $context, EntityManager $em)
    {
        $this->em = $em;
        $this->context = $context;
        $this->user = $this->context->getToken()->getUser();
    }
     /**
     * checkAccess
     * 
     * @param User                              $user
     * @param ProjectStateTender|ProjectSimple  $object
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user, $object = null)
    {
        $role = array();
        if ($object) {
            $managers = $object->getManagers();
            foreach ($managers as $manager) {
                if ($manager instanceof \Lists\ProjectBundle\Entity\ManagerProjectType && $manager->getUser() == $user) {
                    $role[] = 'ManagerProject';
                } elseif ($manager->getUser() == $user) {
                    $role[] = 'Manager';
                }
                
            }
            $organizationManagers = $object->getOrganization()->getOrganizationUsers();
  
            foreach ($organizationManagers as $manager) {
                if ($manager->getUser() == $user) {
                    $role[] = 'ManagerOrganization';
                }
            }
        }
        if ($user->hasRole('ROLE_PROJECT_STATE_TENDER')) {
            $role[] = 'ProjectStateTender';
        }
        if ($user->hasRole('ROLE_PROJECT_STATE_TENDER_ADMIN')) {
            $role[] = 'ProjectStateTenderAdmin';
        }
        if ($user->hasRole('ROLE_PROJECT_STATE_TENDER_DIRECTOR')) {
            $role[] = 'ProjectStateTenderDirector';
        }
        if ($user->hasRole('ROLE_PROJECT_SIMPLE')) {
            $role[] = 'ProjectSimple';
        }
        if ($user->hasRole('ROLE_PROJECT_SIMPLE_ADMIN')) {
            $role[] = 'ProjectSimpleAdmin';
        }
        if ($user->hasRole('ROLE_PROJECT_SIMPLE_DIRECTOR')) {
            $role[] = 'ProjectSimpleDirector';
        }

        return ProjectAccessFactory::createAccess($role, $object);
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveProjectStateTenderParticipationForm (Form $form, Request $request, $params)
    {
        $data = $form->getData();
        $isParticipation = $data['isParticipation'];
        $reason = $data['reason'];
        $projectStateTender = $this->em->getRepository('ListsProjectBundle:ProjectStateTender')->find((int)$data['projectStateTenderId']);
        if ($projectStateTender) {
            $projectStateTender->setIsParticipation($isParticipation);
            if (empty($isParticipation)) {
                $projectStateTender->setUserClosed($this->user);
                $projectStateTender->setReasonClosed('Не участвуем');
            } else {                
                $status = $this->em->getRepository('ListsProjectBundle:StatusProjectStateTender')
                    ->findOneBy(array('alias' => 'collecting_documents'));
                $projectStateTender->setStatus($status);
            }
            
            $projectStateTender->setReason($reason);
            $this->em->persist($projectStateTender);
            $this->em->flush();
            
        }
    }
    /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function addManagerFormDefaults(Form $form, $defaults)
    {
        $project = $this->em->getRepository('ListsProjectBundle:Project')->find((int) $defaults['project']);
        $form->add('project', 'hidden_entity', array(
                'data_class' => null,
                'entity'=>'Lists\ProjectBundle\Entity\Project',
                'data' => $project
            ));
    }
    /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function addMessageFormDefaults(Form $form, $defaults)
    {
        $project = $this->em->getRepository('ListsProjectBundle:Project')->find((int) $defaults['project']);
         if (!$project) {
            throw new \Exception('Not found', 404);
        }
//        $creator = $project->getUser();

//        $userIds = array();
//
//        $userIds[$creator->getId()] = $creator->getId();

        /** @var HandlingUser[] $users */
//        $users = $handling->getHandlingUsers();

//        if ($users) {
//            foreach ($users as $user) {
//                $userIds[$user->getUserId()] = $user->getUserId();
//            }
//        }

        $organizationId = $project->getOrganization()->getId();

        $form
            ->get('current')
                ->add('contact', 'entity', array(
                    'class' => 'ListsContactBundle:ModelContact',
                    'required' => true,
                    'query_builder' => function (\Lists\ContactBundle\Entity\ModelContactRepository $repository) use ($organizationId) {
                        return $repository->createQueryBuilder('mc')
                            ->leftJoin('mc.owner', 'owner')
                            ->where('mc.modelName = :modelName')
                            ->andWhere('mc.modelId = :modelId')
                            ->setParameter(':modelName', \Lists\ContactBundle\Entity\ModelContactRepository::MODEL_ORGANIZATION)
                            ->setParameter(':modelId', $organizationId);
                    }
                ))
                ->add('project', 'hidden_entity', array(
                    'data_class' => null,
                    'entity'=>'Lists\ProjectBundle\Entity\Project',
                    'data' => $project
                ));
        $form
            ->get('planned')
                ->add('contact', 'entity', array(
                    'class' => 'ListsContactBundle:ModelContact',
                    'required' => true,
                    'query_builder' => function (\Lists\ContactBundle\Entity\ModelContactRepository $repository) use ($organizationId) {
                        return $repository->createQueryBuilder('mc')
                            ->leftJoin('mc.owner', 'owner')
                            ->where('mc.modelName = :modelName')
                            ->andWhere('mc.modelId = :modelId')
                            ->setParameter(':modelName', \Lists\ContactBundle\Entity\ModelContactRepository::MODEL_ORGANIZATION)
                            ->setParameter(':modelId', $organizationId);
                    }
                ))
                ->add('project', 'hidden_entity', array(
                    'data_class' => null,
                    'entity'=>'Lists\ProjectBundle\Entity\Project',
                    'data' => $project
                ));
    }
    /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function confirmProjectFormDefaults(Form $form, $defaults)
    {
        $form->add('projectId', 'hidden', array(
                'mapped' => false,
                'data' => (int) $defaults['projectId']
            ));
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveAddManagerProjectForm (Form $form, Request $request, $params)
    {
        $data = $form->getData();
        $access = $this->checkAccess($this->user, $data->getProject());
        $part = 0;
        if ($access->canChangeManagerProject()) {
            $findManager = $this->em->getRepository('ListsProjectBundle:Manager')->findOneBy(array(
                'user' => $data->getUser(),
                'project' => $data->getProject()
            ));
            if ($findManager) {
                $part += $findManager->getPart();
                $this->em->remove($findManager);
                $this->em->flush();
            }
            $managers = $data->getProject()->getManagers();
            foreach ($managers as $manager) {
                if ($manager->isManagerProject()) {
                    $part += $manager->getPart();
                    $this->em->remove($manager);
                    $this->em->flush();
                }
            }
            $data->setPart($part);
            $this->em->persist($data);
            $this->em->flush();
        }
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveAddManagerForm (Form $form, Request $request, $params)
    {
        $data = $form->getData();
        $access = $this->checkAccess($this->user, $data->getProject());
        $canSave = null;
        if ($access->canChangeManager()) {
            $managers = $data->getProject()->getManagers();
            foreach ($managers as $manager) {
                if ($manager->isManagerProject() && $manager->getUser() == $this->user) {
                    $manager->setPart($manager->getPart()-$data->getPart());
                    $this->em->persist($manager);
                    $canSave = true;
                    continue;
                }
            }
            if ($canSave) {
                $this->em->persist($data);
                $this->em->flush();
            }
        }
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveAddMessageForm (Form $form, Request $request, $params)
    {
        $current = $form->get('current')->getData();
        $planned = $form->get('planned')->getData();
        $project = $current->getProject();
        $access = $this->checkAccess($this->user, $project);
        if (!$access->canAddMessage()) {
            throw new \Exception('No access', 403);
        }
        $currentFiles = $form->get('current')->get('files')->getData();
        foreach ($currentFiles as $currentFile) {
            $currentFile->setMessage($current);
            $currentFile->setUser($this->user);
            $currentFile->upload();
        }

        $plannedFiles = $form->get('planned')->get('files')->getData();
        foreach ($plannedFiles as $plannedFile) {
            $plannedFile->setMessage($planned);
            $plannedFile->setUser($this->user);
            $plannedFile->upload();
        }
        $current->setUser($this->user);
        $planned->setUser($this->user);

        $this->em->persist($current);
        $this->em->persist($planned);
        $this->em->flush();

        $project->setLastMessageCurrent($current);
        $project->setLastMessagePlanned($planned);
        $this->em->persist($project);
        $this->em->flush();
    }
    /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function projectStateTenderParticipantFormDefaults(Form $form, $defaults)
    {
        $project = $this->em->getRepository('ListsProjectBundle:ProjectStateTender')->find((int) $defaults['projectStateTender']);
        $form
            ->add('projectStateTender', 'hidden_entity', array(
                'data_class' => null,
                'entity'=>'Lists\ProjectBundle\Entity\ProjectStateTender',
                'data' => $project
            ));
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveProjectStateTenderParticipantForm (Form $form, Request $request, $params)
    {
        $data = $form->getData();
        $this->em->persist($data);
        $this->em->flush();
    }
     /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function addDocumentFormDefaults(Form $form, $defaults)
    {
        $project = $this->em->getRepository('ListsProjectBundle:Project')->find((int) $defaults['project']);
        $form
            ->add('project', 'hidden_entity', array(
                'data_class' => null,
                'entity'=>'Lists\ProjectBundle\Entity\Project',
                'data' => $project
            ));
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveAddDocumentForm (Form $form, Request $request, $params)
    {
        $data = $form->getData();
        $data->setFile($data->getName());
        $data->upload();
        $data->setUser($this->user);
        $this->em->persist($data);
        $this->em->flush();
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveCloseProjectForm (Form $form, Request $request, $params)
    {
        $reasonClosed = $form->get('reasonClosed')->getData();
        $projectId = $form->get('projectId')->getData();

        $project = $this->em->getRepository('ListsProjectBundle:Project')->find($projectId);
        $access = $this->checkAccess($this->user, $project);

        if (!$access->canCloseProject()) {
            throw new \Exception('No access', 403);
        }
        if ($project) {
            $project->setUserClosed($this->user);
            $project->setReasonClosed($reasonClosed);
            $this->em->persist($project);
            $this->em->flush();
        }
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveConfirmProjectForm (Form $form, Request $request, $params)
    {
        $statusAccess = $form->get('statusAccess')->getData();
        $projectId = $form->get('projectId')->getData();

        $project = $this->em->getRepository('ListsProjectBundle:Project')->find($projectId);
        $notification = $project->getNotification();
        if ($notification) {
            $newsFosUsers = $this->em->getRepository('ListsArticleBundle:NewsFosUser')->findBy(array(
                'news' => $notification
            ));
            foreach ($newsFosUsers as $newsFosUser) {
                $newsFosUser->setViewed(new \DateTime());
                $this->em->persist($newsFosUser);
            }
        }
        if ($project) {
            $project->setStatusAccess($statusAccess);
            $this->em->persist($project);
            $this->em->flush();
        }
    }
    /**
     * Save form
     *
     * @param integer $organizationId
     * @param integer $userId
     */
    public function changeManagerProject($organizationId, $userId)
    {
        $projects = $this->em->getRepository('ListsProjectBundle:Project')
                ->findBy(array('organization' => $organizationId));
        foreach ($projects as $project) {
            $this->changeManagerProjectOne($project, $userId);
        }
    }
    /**
     * Save form
     *
     * @param Project $project
     * @param integer $userId
     */
    public function changeManagerProjectOne($project, $userId)
    {
        if (!$project->isManagerProject()) {
            $part = $project->getMaxPart();
            $user = $this->em->getRepository('SDUserBundle:User')->find($userId);
            $managerProject = $project->getManagerProject();

            if ($project->isManager($user)) {
                $manager = $this->em->getRepository('ListsProjectBindle:ManagerType')
                        ->findOneBy(array(
                            'user' => $user,
                            'project' => $project
                        ));
                $part += $manager->getPart();
                $this->em->remove($manager);
                $this->em->flush();
            }
            if (!$managerProject) {
                $managerProject = new \Lists\ProjectBundle\Entity\ManagerProjectType();
                $managerProject->setUser($user);
                $managerProject->setProject($project);
                $managerProject->setPart($part);
            } else {
                $managerProject->setUser($user);
            }
            $this->em->persist($managerProject);
            $this->em->flush();
        }
    }
}
