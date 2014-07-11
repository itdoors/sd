<?php
namespace ITDoors\OperBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class AccessService
 */
class AccessService
{
    /** @var Container $container */
    protected $container;

    /** @var  EntityManager $em */
    protected $em;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }


    /**
     * @return array|bool
     */
    public function getAllowedDepartmentsId()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $idUser = $user->getId();
        //->getUser();
        $checkOper =  $user->hasRole('ROLE_OPER');

        $checkSuperviser =  $user->hasRole('ROLE_SUPERVISOR');

        if ($checkSuperviser) {

            return false;
        } elseif ($checkOper) {

            /** @var  $stuff \SD\UserBundle\Entity\Stuff */
            $stuff = $this->container->get('doctrine')
                ->getRepository('SDUserBundle:Stuff')
                ->findOneBy(array('user' => $idUser));

            if (!$stuff) {
                return array();
            }


            $stuffDepartments = $this->container->get('doctrine')
                ->getRepository('SDUserBundle:StuffDepartments')
                ->findBy(array('stuff' => $stuff));

            if (count($stuffDepartments) == 0 || !$stuffDepartments) {
                return array();
            }

            if (!is_array($stuffDepartments)) {
                $stuffDepartments = array($stuffDepartments);
            }

            $idDepartmentsAllowed = array();

            /** @var  $stuffDepartment \SD\UserBundle\Entity\StuffDepartments */
            foreach ($stuffDepartments as $stuffDepartment) {
                $departmentsAllowed = $stuffDepartment->getDepartments();

                if (count($departmentsAllowed) == 0) {
                    return array();
                }
                if (!is_array($departmentsAllowed)) {
                    $departmentsAllowed = array($departmentsAllowed);
                }

                foreach ($departmentsAllowed as $departmentAllowed) {
                    $idDepartmentsAllowed[] = $departmentAllowed->getId();
                }
            }

            return $idDepartmentsAllowed;
        } else {
            return array();
        }
    }

    /**
     * @param integer $idDepartment
     *
     * @return bool
     */
    public function checkAccessToDepartment($idDepartment)
    {
        $access = false;

        $allowedDepartments = $this->getAllowedDepartmentsId();

        if ($allowedDepartments === false) {
            return true;
        }
        if (in_array($idDepartment, $allowedDepartments)) {
            return true;
        }


        return false;
    }

    /**
     * @return bool
     */
    public function checkIfCanEdit()
    {
        $canEdit  =  !$this->container->get('security.context')->getToken()->getUser()->hasRole('ROLE_SUPERVISOR');

        return $canEdit;
    }

} 