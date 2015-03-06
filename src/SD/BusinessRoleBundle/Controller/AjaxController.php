<?php

namespace SD\BusinessRoleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SD\BusinessRoleBundle\Entity\Client;
use SD\BusinessRoleBundle\Entity\PersonClient;
use SD\BusinessRoleBundle\Form\ClientType;

/**
 * AjaxController
 *
 */
class AjaxController extends Controller
{
    /**
     * @return Response
     */
    public function getClientsByDepartmentIdAjaxAction()
    {
        $depId = $this->get('request')->query->get('depId');
        $searchText = $this->get('request')->query->get('query');

        $result = [];
        $clients = $this
            ->getDoctrine()
            ->getRepository('SDBusinessRoleBundle:CompanyClient')
            ->getClientsForDepartmentQuery($searchText, $depId);

        foreach ($clients as $client) {
            $result[] = array(
                'id' => $client->getId(),
                'value' => $client->getId(),
                'name' => $client->__toString(),
                'text' => $client->__toString()
            );
        }

        return new JsonResponse($result);
    }

    /**
     * @return Response
     */
    public function getImportancesByOrganizationIdAjaxAction()
    {
        $orgId = $this->get('request')->query->get('orgId');

        $result = [];
        $importances = $this
            ->getDoctrine()
            ->getRepository('SDServiceDeskBundle:OrganizationImportance')
            ->findBy(array('organization' => $orgId));

        foreach ($importances as $importance) {
            $imp = $importance->getImportance();
            $result[] = array(
                'id' => $imp->getId(),
                'value' => $imp->getId(),
                'name' => $imp->__toString(),
                'text' => $imp->__toString()
            );
        }

        return new JsonResponse($result);
    }
}