<?php

namespace Lists\OrganizationBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use ITDoors\CommonBundle\Services\BaseService;
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
        $em = $this->getDoctrine()->getEntityManager();
        $isEdit = false;
        /** @var OrganizationServiceCoverRepository $oscr */
        $oscr = $this->get('organization.service_cover.repository');

        $allServices = $em->getRepository('ListsProjectBundle:Service')->findAll();
        /** @var BaseService $baseService */
        $baseService = $this->get('itdoors_common.base.service');

        $serviceCovers = $oscr->getFormattedByOrganizationId($organizationId);

        $options = array();
        $options['id']['type'] = 'text';
        $options['organizationName']['type'] = 'text';

        foreach ($allServices as $service) {
            if (!isset($serviceCovers[$service->getId()])) {
                $emptyCover = $oscr->getEmptyServiceCover($service->getId(), $service->getName(), $organizationId);
                $serviceCovers[$service->getId()] = $emptyCover;
            }
        }

        $select2Competitor = array(
            'minimumInputLength' => 2
        );

        return $this->render('ListsOrganizationBundle:ServiceCover:list.html.twig', array(
            'organizationId' => $organizationId,
            'serviceCovers' => $serviceCovers,
            'options' => $options,
            'allServices' => $allServices,
            'boolChoices' => json_encode($baseService->getYesNoChoices()),
            'numberChoices' => json_encode($baseService->getNumberChoices(1, 10)),
            'select2Competitor' => json_encode($select2Competitor),
            'isEdit' => $isEdit
        ));
    }

    /**
     * @param integer $organizationId
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
