<?php

namespace Lists\ProjectBundle\Service;

use Doctrine\ORM\EntityManager;
use Lists\ProjectBundle\Classes\ProjectAccessFactory;
use SD\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Lists\ProjectBundle\Entity\StateTender;
use Lists\ProjectBundle\Entity\Project;

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
     * @param User                  $user
     * @param StateTender|Project   $object
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
        }
        if ($user->hasRole('ROLE_STATE_TENDER')) {
            $role[] = 'StateTender';
        }
        if ($user->hasRole('ROLE_STATE_TENDER_DIRECTOR')) {
            $role[] = 'StateTenderDirector';
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
    public function saveStateTenderParticipationForm (Form $form, Request $request, $params)
    {
        $data = $form->getData();
        $isParticipation = $data['isParticipation'];
        $reason = $data['reason'];
        $stateTender = $this->em->getRepository('ListsProjectBundle:StateTender')->find((int)$data['stateTenderId']);
        if ($stateTender) {
            $stateTender->setIsParticipation($isParticipation);
            if (empty($isParticipation)) {
                $stateTender->setUserClosed($this->user);
                $stateTender->setReasonClosed('Не участвуем');
            } else {
                $status = $this->em->getRepository('ListsProjectBundle:StatusStateTender')
                    ->findOneBy(array('alias' => 'collecting_documents'));
                $stateTender->setStatusStateTender($status);
            }
            $stateTender->setReason($reason);
            $this->em->persist($stateTender);
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
        if ($access->canChangeManager()) {
            $managers = $data->getProject()->getManagers();
            foreach ($managers as $manager) {
                if ($manager->isManagerProject()) {
                    $manager->setPart($manager->getPart()-$data->getPart());
                    $this->em->persist($manager);
                }
            }
            $this->em->persist($data);
            $this->em->flush();
        }
    }
    /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function stateTenderParticipantFormDefaults(Form $form, $defaults)
    {
        $project = $this->em->getRepository('ListsProjectBundle:StateTender')->find((int) $defaults['stateTender']);
        $form
            ->add('stateTender', 'hidden_entity', array(
                'data_class' => null,
                'entity'=>'Lists\ProjectBundle\Entity\StateTender',
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
    public function saveStateTenderParticipantForm (Form $form, Request $request, $params)
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
    public function saveCloseStateTenderForm (Form $form, Request $request, $params)
    {
        $reasonClosed = $form->get('reasonClosed')->getData();
        $projectId = $form->get('projectId')->getData();

        $project = $this->em->getRepository('ListsProjectBundle:Project')->find($projectId);
        if ($project) {
            $project->setUserClosed($this->user);
            $project->setReasonClosed($reasonClosed);
            $this->em->persist($project);
            $this->em->flush();
        }
    }
    
}
