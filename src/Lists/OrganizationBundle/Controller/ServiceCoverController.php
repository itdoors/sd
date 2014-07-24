<?php

namespace Lists\OrganizationBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Lists\OrganizationBundle\Entity\OrganizationServiceCoverRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ServiceCoverController
 */
class ServiceCoverController extends BaseFilterController
{

    /**
     * Renders template for serice cover list
     *
     * @param int $organizationId
     *
     * @return Response
     */
    public function indexAction($organizationId)
    {
        return $this->render('ListsOrganizationBundle:ServiceCover:index.html.twig', array(
            'organizationId' => $organizationId
        ));
    }

    /**
     * Renders outer template for serice cover list && form
     *
     * @param int $organizationId
     *
     * @return Response
     */
    public function listAction($organizationId)
    {
        /** @var OrganizationServiceCoverRepository $organizationRepository */
        $organizationRepository = $this->get('organization.service_cover.repository');

        $serviceCovers = $organizationRepository->getByOrganizationId($organizationId);

        $options = array();
        $options['id']['type'] = 'text';
        $options['organizationName']['type'] = 'text';

        return $this->render('ListsOrganizationBundle:ServiceCover:list.html.twig', array(
            'organizationId' => $organizationId,
            'serviceCovers' => $serviceCovers,
            'options' => $options
        ));
    }

    /**
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function formAction($organizationId)
    {
        return $this->render('ListsOrganizationBundle:ServiceCover:form.html.twig', array(
            'organizationId' => $organizationId
        ));
    }
}
