<?php
namespace ITDoors\OperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ITDoors\AjaxBundle\Services\BaseFilterService;
use Symfony\Component\HttpFoundation\Request;
use Lists\OrganizationBundle\Controller\SalesController;
use Lists\OrganizationBundle\Entity\OrganizationUser;

/**
 * Class ContractorController
 */
class OperContractorController extends Controller
{

    protected $filterNamespace = 'organization.contractor.filters';
    protected $baseRoutePrefix = 'contractor';
    protected $baseTemplate = 'Contractor';

    public function indexAction() {

        return $this->render('ITDoorsOperBundle:Contractor:index.html.twig', array(


        ));

    }


}
