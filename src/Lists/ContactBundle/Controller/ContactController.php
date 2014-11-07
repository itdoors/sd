<?php

namespace Lists\ContactBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;
use Lists\ContactBundle\Entity\ModelContactRepository;
use Lists\DepartmentBundle\Entity\Departments;

/**
 * Class ContactController
 */
class ContactController extends BaseController
{
    protected $filterNamespace = 'contacts.sales.filters';

    /**
     * @param int $handlingId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handlingAction($handlingId)
    {
        $user = $this->getUser();

        $organizationId = $this->getDoctrine()->getManager()
            ->getRepository('ListsHandlingBundle:Handling')
            ->getOrganizationByHandlingId($handlingId);

        $handlingContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyHandlingContacts($user->getId(), $handlingId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsContactBundle:Contact:organization.html.twig', array(
            'pagination' => $handlingContacts,
            'handlingId' => $handlingId,
            'organizationId' => $organizationId
        ));
    }
    /**
     * @param int $id
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationElementAction($id, $organizationId)
    {
        $organizationContactQuery = $this->getDoctrine()
            ->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts(array(), null, $id);

        $organizationContact = $organizationContactQuery->getSingleResult();

        return $this->render('ListsContactBundle:Contact:organizationElement.html.twig', array(
                'item' => $organizationContact,
                'organizationId' => $organizationId
            ));
    }
     /**
     * @param int $id
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function departmentOrganizationElementAction($id, $organizationId)
    {
        $organizationContactQuery = $this->getDoctrine()
            ->getRepository('ListsContactBundle:ModelContact')
            ->getMyDepartmentByOrganizationContactsQuery(null, $id);

        $organizationContact = $organizationContactQuery->getSingleResult();

        return $this->render('ListsContactBundle:Contact:organizationElement.html.twig', array(
                'item' => $organizationContact,
                'organizationId' => $organizationId,
                'modelName' => ModelContactRepository::MODEL_DEPARTMENT
            ));
    }
}
