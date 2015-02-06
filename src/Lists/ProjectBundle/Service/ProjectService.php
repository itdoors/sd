<?php

namespace Lists\ProjectBundle\Service;

use Doctrine\ORM\EntityManager;
use Lists\ProjectBundle\Classes\ProjectAccessFactory;
use SD\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Lists\ProjectBundle\Entity\StateTender;

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
     * __construct()
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
     * @param User          $user
     * @param StateTender   $object
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user, $object = null)
    {
        $role = array();
        if ($object) {
            $managers = $object->getManagers();
            foreach ($managers as $manager) {
                if ($manager->getUser() == $user) {
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

        return ProjectAccessFactory::createAccess($role);
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
                $stateTender->setStatus($status);
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
    public function projectGosTenderParticipanFormDefaults(Form $form, $defaults)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $gosTenderId = (int) $defaults['gosTender'];
        $gosTender = $em->getRepository('ListsHandlingBundle:ProjectGosTender')->find($gosTenderId);
        $form
            ->add('gosTender', 'hidden_entity', array(
                'data_class' => null,
                'entity'=>'Lists\HandlingBundle\Entity\ProjectGosTender',
                'data' => $gosTender
            ));
    }
    /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function addDocumentFormDefaults(Form $form, $defaults)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $projectId = (int) $defaults['project'];
        $project = $em->getRepository('ListsHandlingBundle:Handling')->find($projectId);
        $form
            ->add('project', 'hidden_entity', array(
                'data_class' => null,
                'entity'=>'Lists\HandlingBundle\Entity\Handling',
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
    public function saveProjectGosTenderParticipanForm (Form $form, Request $request, $params)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $data = $form->getData();
        $isWinner = $data->getIsWinner();
        $em->persist($data);
        $em->flush();
        
        if ($isWinner === null) {
            // костыль для поля boolean set null (нужно будет удалить)
            $db = $em->getConnection();
            $stmt = $db->prepare('UPDATE "public".project_gos_tender_participan SET "is_winner" = NULL WHERE id = :id');
            $stmt->execute(array (':id' => $data->getId()));
        }
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
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $reasonClosed = $form->get('reasonClosed')->getData();
        $projectId = $form->get('projectId')->getData();

        $project = $em->getRepository('ListsHandlingBundle:Handling')->find($projectId);
        if ($project) {
            $project->setCloser($this->container->get('security.context')->getToken()->getUser());
            $project->setReasonClosed($reasonClosed);
            $em->persist($project);
            $em->flush();
        }
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
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $data = $form->getData();
        $data->setFile($data->getName());
        $data->upload();
        $data->setUser($this->container->get('security.context')->getToken()->getUser());
        $em->persist($data);
        $em->flush();
    }
}
