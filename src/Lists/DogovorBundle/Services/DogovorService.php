<?php

namespace Lists\DogovorBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Symfony\Component\BrowserKit\Response;
use Lists\DogovorBundle\Entity\Dogovor;
use Lists\DogovorBundle\Classes\DogovorAccessFactory;
use SD\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Invoice Service class
 */
class DogovorService
{

    /**
     * @var Container $container
     */
    protected $container;
    /**
     * @var Translator $translator
     */
    protected $translator;
    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->translator = $container->get('translator');
    }

    /**
     * get files and update create date in dogovor and dopdogovor
     * 
     * @return string
     */
    public function updateDate()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $directory = $this->container->getParameter('project.web.dir') . '/uploads/dogovor/';

        echo 'find in '.$directory."\t\n";
        /** @var DogovorRepository $dogovorR */
        $dogovorR = $em->getRepository('ListsDogovorBundle:Dogovor')->findAll();

        /** @var DopDogovorRepository $dopDogovorR */
        $dopDogovorR = $em->getRepository('ListsDogovorBundle:DopDogovor')->findAll();

        $countErr = 0;
        $countOk = 0;
        $i = 0;
        foreach ($dogovorR as $dogovor) {
            echo $i++."\n";
            if (is_file($directory . $dogovor->getFilepath())) {
                $countOk++;
                $date = date("d.m.Y H:i:s", filemtime($directory . $dogovor->getFilepath()));
                $dogovor->setCreateDateTime(new \DateTime($date));
                $em->persist($dogovor);
            } else {
                $countErr++;
            }
        }
        $em->flush();
        foreach ($dopDogovorR as $dogovor) {
            echo $i++."\n";
            if (is_file($directory . $dogovor->getFilepath())) {
                $countOk++;
                $date = date("d.m.Y H:i:s", filemtime($directory . $dogovor->getFilepath()));
                $dogovor->setCreateDateTime(new \DateTime($date));
                $em->persist($dogovor);
            } else {
                $countErr++;
            }
        }
        $em->flush();

        return 'ok: ' . $countOk . "\t\n" .' errors: ' . $countErr;
    }
    /**
     * checkAccess
     * 
     * @param User    $user
     * @param Dogovor $dogovor
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user, Dogovor $dogovor = null)
    {
        $role = array();
        $role[] = 'base';
        if ($dogovor) {
            $organization = $dogovor->getCustomer();
            if ($organization) {
                $managers = $organization->getOrganizationUsers();
                foreach ($managers as $manager) {
                    $rol = $manager->getRole();
                    if ($rol && $rol->getLukey() == 'manager_organization' && $manager->getUser() == $user) {
                        $role[] = 'manager_organization';
                    }
                }
            }
        }
        if ($user->hasRole('ROLE_CONTROLLING')) {
            $role[] = 'controlling';
        }
        if ($user->hasRole('ROLE_DOGOVORADMIN')) {
            $role[] = 'admin';
        }
        if ($user->hasRole('ROLE_DOGOVOR_VIEWER')) {
            $role[] = 'dogovor_viewer';
        }
        if ($user->hasRole('ROLE_SALES')) {
            $role[] = 'sales';
        }
        if ($user->hasRole('ROLE_OPER')) {
            $role[] = 'oper';
        }

        return DogovorAccessFactory::createAccess($role);
    }
    /**
     * Returns choices for is active
     *
     * @return mixed[]
     */
    public function getIsActiveChoices()
    {
        return array(
            'No' => $this->translator->trans("No", array(), 'messages'),
            'Yes' => $this->translator->trans("Yes", array(), 'messages')
        );
    }
    /**
     * Returns choices for prolongation
     *
     * @return mixed[]
     */
    public function getProlongationChoices()
    {
        return array(
            'No' => $this->translator->trans("No", array(), 'messages'),
            'Yes' => $this->translator->trans("Yes", array(), 'messages')
        );
    }
    /**
     * Returns choices for mashtab
     *
     * @return mixed[]
     */
    public function getMashtabChoices()
    {
        return array(
            'm_local' => $this->translator->trans("Local", array(), 'ListsDogovorBundle'),
            'm_global' => $this->translator->trans("Global", array(), 'ListsDogovorBundle')
        );
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveAddProjectForm (Form $form, Request $request, $params)
    {
        $project = $form->get('project')->getData();
        $dogovorId = $form->get('dogovorId')->getData();
        $em = $this->container->get('doctrine')->getManager();
        $dogovor = $em->getRepository('ListsDogovorBundle:Dogovor')->find($dogovorId);
        $project->setDogovor($dogovor);
        if (!$project->getIsClosed() && $dogovor) {
            if (!$project instanceof \Lists\ProjectBundle\Entity\ProjectStateTender) {
                $status = $project->getStatus();
                if (
                    !$status
                    ||
                    ($status && $status->getAlias() != 'comm_proposal')
                    ||
                    ($status && $status->getAlias() == 'comm_proposal' && $project->hasCommercialFile())
                ) {
                    $robot = $em->getRepository('SDUserBundle:User')->find(0);
                    $project->setUserClosed($robot);
                    $project->setReasonClosed('Договор подписан');
                }
            }
        }
        $em->persist($project);
        $em->flush();
    }
}
