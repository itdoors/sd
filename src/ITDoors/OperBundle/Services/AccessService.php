<?php

namespace ITDoors\OperBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use SD\UserBundle\Entity\User;
use \Doctrine\Common\Collections\ArrayCollection;
use Lists\CompanystructureBundle\Entity\Companystructure;

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
    public function __construct (Container $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @param null|User $user
     *
     * @return array|bool
     */
    public function getAllowedDepartmentsId ($user = null)
    {
        if (!$user) {
            $user = $this->container->get('security.context')->getToken()->getUser();
        }
        $idUser = $user->getId();
        //->getUser();
        $checkOper = $user->hasRole('ROLE_OPER');

        $checkSuperviser = $user->hasRole('ROLE_SUPERVISOR');

        if ($checkSuperviser) {

            return false;
        } elseif ($checkOper) {

            /** @var  $stuff \SD\UserBundle\Entity\Stuff */
            $stuff = $this->container->get('doctrine')
                ->getRepository('SDUserBundle:Stuff')
                ->findOneBy(array ('user' => $idUser));

            if (!$stuff) {
                return array ();
            }


            $stuffDepartments = $this->container->get('doctrine')
                ->getRepository('SDUserBundle:StuffDepartments')
                ->findBy(array ('stuff' => $stuff));

            if (count($stuffDepartments) == 0 || !$stuffDepartments) {
                return array ();
            }

            if (!is_array($stuffDepartments)) {
                $stuffDepartments = array ($stuffDepartments);
            }

            $idDepartmentsAllowed = array ();

            /** @var  $stuffDepartment \SD\UserBundle\Entity\StuffDepartments */
            foreach ($stuffDepartments as $stuffDepartment) {
                $departmentsAllowed = $stuffDepartment->getDepartments();

                if (count($departmentsAllowed) == 0) {
                    return array ();
                }
                if (!is_array($departmentsAllowed)) {
                    $departmentsAllowed = array ($departmentsAllowed);
                }

                foreach ($departmentsAllowed as $departmentAllowed) {
                    $idDepartmentsAllowed[] = $departmentAllowed->getId();
                }
            }
            $idDepartmentsAllowed = array_unique($idDepartmentsAllowed);

            return $idDepartmentsAllowed;
        } else {
            return array ();
        }
    }
    /**
     * @param integer $idDepartment
     *
     * @return bool
     */
    public function checkAccessToDepartment ($idDepartment)
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
    public function checkIfCanEdit ()
    {
        $canEdit = !$this->container->get('security.context')->getToken()->getUser()->hasRole('ROLE_SUPERVISOR');

        return $canEdit;
    }

    /**
     * Finds all departments allowed for $user
     * 
     * @param User $user
     * 
     * @return array
     */
    public function getAllowedDepartmentsForUser (User $user)
    {
        $this->em->g
        $user->getStuff();

        return $canEdit;
    }

    /**
     * Recursive Companystructure fetcher
     *
     * @param Companystructure  $parent
     * @param ArrayCollection   $parentChildren
     *
     * @return ArrayCollection
     */
    private function fetchAllChildren(Companystructure $parent, $parentChildren = null)
    {
        if ($parentChildren == null) {
            $parentChildren = new ArrayCollection();
        }
        if ($parent->getChildren()->count() > 0) {
            foreach ($parent->getChildren() as $child) {
                $this->fetchAllChildren($child, $parentChildren);
            }
            $parentChildren->add($parent);
        } else {
            $parentChildren->add($parent);
        }

        return $parentChildren;
    }
}
