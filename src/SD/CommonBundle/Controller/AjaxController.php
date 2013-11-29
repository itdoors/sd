<?php

namespace SD\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{
    public function organizationAction()
    {
        $searchText = $this->get('request')->query->get('q');

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $organizations= $organizationsRepository->getSearchQuery($searchText);

        $result = array();

        foreach ($organizations as $organization)
        {
            $result[] = $this->serializeOrganization($organization);
        }

        return new Response(json_encode($result));
    }

    public function organizationByIdAction()
    {
        $id = $this->get('request')->query->get('id');

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
        $organization = $organizationsRepository
            ->find($id);

        $result = array();

        if ($organization)
        {
            $result = $this->serializeOrganization($organization);
        }

        return new Response(json_encode($result));
    }

    /**
     * Serialize object to json. temporary solution
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     *
     * @return mixed[]
     */
    public function serializeOrganization($organization)
    {
        return array(
            'id' => $organization->getId(),
            'name' => $organization->getName()
        );
    }

    public function organizationTypeAction()
    {
        $searchText = $this->get('request')->query->get('q');

        $organizationTypes = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationType')
            ->findAll();

        $result = array();

        foreach ($organizationTypes as $organization)
        {
            $result[] = $this->serializeOrganizationType($organization);
        }

        return new Response(json_encode($result));
    }

    /**
     * Serialize object to json. temporary solution
     *
     * @param \Lists\OrganizationBundle\Entity\OrganizationType $organizationType
     *
     * @return mixed[]
     */
    public function serializeOrganizationType($organizationType)
    {
        return array(
            'value' => $organizationType->getId(),
            'text' => (string) $organizationType
        );
    }

    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function organizationSaveAction()
    {
        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $organizationType = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationType')
            ->find($value);

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($pk);

        $organization->setOrganizationType($organizationType);

        /*$validator = $this->get('validator');
        $errors = $validator->validate($organization);

        if (sizeof($errors))
        {
            $return = array('msg' => (string) $errors);

            return new Response(json_encode($return));
        }*/

        $em = $this->getDoctrine()->getManager();
        $em->persist($organization);
        $em->flush();

        $return = array('success' => 1);

        return new Response(json_encode($return));
    }
}