<?php

namespace SD\CommonBundle\Controller;

use Lists\DepartmentBundle\Entity\DepartmentsRepository;
use Lists\DogovorBundle\Entity\Dogovor;
use Lists\DogovorBundle\Entity\DogovorDepartment;
use Lists\DogovorBundle\Entity\DogovorDepartmentRepository;
use Lists\DogovorBundle\Entity\DogovorHistory;
use Lists\DogovorBundle\Entity\DopDogovor;
use Lists\DogovorBundle\Entity\DopDogovorRepository;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Entity\HandlingMessage;
use Lists\HandlingBundle\Entity\HandlingRepository;
use Lists\LookupBundle\Entity\LookupRepository;
use Lists\ContactBundle\Entity\ModelContact;
use Lists\ContactBundle\Entity\ModelContactRepository;
use Lists\OrganizationBundle\Entity\Organization;
use Lists\HandlingBundle\Entity\HandlingMoreInfo;
use SD\UserBundle\Entity\UserRepository;
use SD\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Doctrine\DBAL\Connection;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Lists\CompanystructureBundle\Entity\Companystructure;
use SD\UserBundle\Entity\Usercontactinfo;
use SD\CalendarBundle\Entity\Task;
use Lists\HandlingBundle\Entity\HandlingUser;
use Lists\OrganizationBundle\Entity\OrganizationUser;

/**
 * AjaxController class.
 *
 * This class for different ajax procedures (render/valid forms, get lists of objects, etc.)
 *
 * @package    SD2
 * @subpackage Ajax
 * @author     Pavel Pecheny <ppecheny@gmail.com>
 * @version    "Release: 1.0"
 */
class AjaxController extends BaseFilterController
{

    protected $modelRepositoryDependence = array(
        'ModelContact' => 'ListsContactBundle:ModelContact',
        'User' => 'SDUserBundle:User',
        'Dogovor' => 'ListsDogovorBundle:Dogovor',
        'DopDogovor' => 'ListsDogovorBundle:DopDogovor',
        'Email' => 'ITDoorsEmailBundle:Email',
        'Task' => 'SDCalendarBundle:Task'
    );

    /**
     * Returns list of competitors in json
     *
     * @return string
     */
    public function competitorAction()
    {
        $searchTextQ = $this->get('request')->query->get('q');
        $searchTextQuery = $this->get('request')->query->get('query');

        $searchText = $searchTextQ ? $searchTextQ : $searchTextQuery;

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var LookupRepository $lr */
        $lr = $this->get('lists_lookup.repository');

        $organizationCompetitorId = $lr->getFirstIdByLukey($lr::KEY__ORGANIZATION_SIGN_COMPETITOR);

        $organizations = $organizationsRepository->getSearchQuery($searchText, $organizationCompetitorId);

        $result = array();

        foreach ($organizations as $organization) {
            $result[] = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns list of organizations in json
     *
     * @return string
     */
    public function organizationAction()
    {
        $searchTextQ = $this->get('request')->query->get('q');
        $searchTextQuery = $this->get('request')->query->get('query');

        $searchText = $searchTextQ ? $searchTextQ : $searchTextQuery;

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $organizations = $organizationsRepository->getSearchQuery($searchText);

        $result = array();

        foreach ($organizations as $organization) {
            $result[] = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns list of organizations in json
     *
     * @return string
     */
    public function organizationEdrpouAction()
    {
        $searchTextQ = $this->get('request')->query->get('q');
        $searchTextQuery = $this->get('request')->query->get('query');

        $searchText = $searchTextQ ? $searchTextQ : $searchTextQuery;

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $organizations = $organizationsRepository->findEdrpou($searchText);

        $result = array();

        foreach ($organizations as $organization) {
            $result[] = $this->serializeObject($organization, 'getEdrpou', 'getEdrpou');
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns list of organizations in json
     *
     * @return string
     */
    public function handlingAction()
    {
        $searchTextQ = $this->get('request')->query->get('q');
        $searchTextQuery = $this->get('request')->query->get('query');

        $searchText = $searchTextQ ? $searchTextQ : $searchTextQuery;

        /** @var HandlingRepository $handlingRepository */
        $handlingRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling');

        $handlings = $handlingRepository->getSearchQuery($searchText);

        $result = array();

        foreach ($handlings as $handling) {
            $result[] = $this->serializeObject($handling);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json organization list for contacts query
     *
     * @return string
     */
    public function organizationForContactsAction()
    {
        $searchTextQ = $this->get('request')->query->get('q');
        $searchTextQuery = $this->get('request')->query->get('query');

        $searchText = $searchTextQ ? $searchTextQ : $searchTextQuery;

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $organizations = $organizationsRepository->getSearchContactsQuery($searchText);

        $result = array();

        foreach ($organizations as $organization) {
            $this->processOrganizationForJson($organization);

            $result[] = $this->serializeArray($organization, 'organizationId');
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json organization list for wizard
     *
     * @return string
     */
    public function organizationForWizardAction()
    {
        $searchText = $this->get('request')->query->get('q');

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $organizations = $organizationsRepository->getSearchContactsQuery($searchText);

        $result = array();

        $result[] = array(
            'id' => '',
            'value' => $searchText,
            'name' => $searchText,
            'text' => $searchText
        );

        foreach ($organizations as $organization) {
            $this->processOrganizationForJson($organization);

            $result[] = $this->serializeArray($organization, 'organizationId');
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json organization list for creation page
     *
     * @return string
     */
    public function organizationForCreationAction()
    {
        $searchText = $this->get('request')->query->get('q');

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $organizations = $organizationsRepository->getSearchContactsQuery($searchText);

        $result = array();

        $result[] = array(
            'id' => $searchText,
            'value' => $searchText,
            'name' => $searchText,
            'text' => $searchText
        );

        foreach ($organizations as $organization) {
            $this->processOrganizationForJson($organization);

            $organization['id'] = '';

            $result[] = $this->serializeArray($organization);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json handling service list
     *
     * @return string
     */
    public function handlingServiceAction()
    {
        $searchText = $this->get('request')->query->get('term');

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $objects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->createQueryBuilder('hs')
            ->where('hs.name like :term')
            ->setParameter(':term', '%' . $searchText . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json city list depending search query
     *
     * @return string
     */
    public function cityAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsCityBundle:City');

        $objects = $repository->getSearchQuery($searchText);

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json city object by id
     *
     * @return string
     */
    public function cityByIdAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $cityList = $this->getDoctrine()
            ->getRepository('ListsCityBundle:City')
            ->findBy(array('id'=>$ids));

        $result = array();

        foreach ($cityList as $city) {
            $result[] = $this->serializeObject($city);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json city object by id
     *
     * @return string
     */
    public function scopeByIdAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $cityList = $this->getDoctrine()
            ->getRepository('lists_lookup.repository')
            ->findBy(array('id'=>$ids));

        $result = array();

        foreach ($cityList as $city) {
            $result[] = $this->serializeObject($city);
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json city object by id
     *
     * @return string
     */
    public function scopeByIdSAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $cityList = $this->getDoctrine()
            ->getRepository('ListsLookupBundle:Lookup')
            ->createQueryBuilder('l')
            ->where('l.id in (:ids)')
            ->setParameter(':ids', $ids)
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($cityList as $city) {
            $result[] = $this->serializeObject($city);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json companystructure list
     *
     * @return string
     */
    public function companystructureAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsCompanystructureBundle:Companystructure');

        $objects = $repository->getSearchQuery($searchText);

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json organization type list
     *
     * @return string
     */
    public function organizationTypeAction()
    {
        $organizationTypes = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationType')
            ->findAll();

        $result = array();

        foreach ($organizationTypes as $organization) {
            $result[] = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json dogovor type list
     *
     * @return string
     */
    public function dogovorTypeAction()
    {
        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $lr = $this->container->get('lists_lookup.repository');

        $dogovorTypes = $lr->getOnlyDogovorTypeQuery()
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($dogovorTypes as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json dogovor type list
     *
     * @return string
     */
//    public function dogovorSubjectAction()
//    {
//         $searchText = $this->get('request')->query->get('query');
//         
//         $dogovor = $this->getDoctrine()
//            ->getRepository('ListsDogovorBundle:Dogovor')
//            ->createQueryBuilder('d')
//            ->select('d.subject')
//            ->addSelect('d.id')
//            ->where('d.subject LIKE :subject')
//            ->setParameter(':subject', "{$searchText}%")
//            ->getQuery()
//            ->getResult();
//
//        $result = array();
//
//        foreach ($dogovor as $object) {
//            $result[] = array(
//                    'id' => $object['subject'],
//                    'value' => $object['subject'],
//                    'name' => $object['subject'],
//                    'text' => $object['subject']
//                );
//        }
//
//        return new Response(json_encode($result));
//    }

    /**
     * Returns json dogovor type list
     *
     * @return string
     */
    public function dogovorStartdatetimeAction()
    {
        $dogovor = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->createQueryBuilder('d')
            ->select('d.startdatetime')
            ->where('d.startdatetime is not NULL')
            ->groupBy('d.startdatetime')
            ->orderBy('d.startdatetime', 'DESC')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($dogovor as $object) {
            $result[] = array(
                    'id' => $object['startdatetime']->format('Y-m-d H:i:s'),
                    'value' => $object['startdatetime']->format('Y-m-d H:i:s'),
                    'name' => $object['startdatetime']->format('Y-m-d'),
                    'text' => $object['startdatetime']->format('Y-m-d')
                );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json dogovor type list
     *
     * @return string
     */
    public function dogovorStartdatetimeIdSAction()
    {
        $searchText = $this->get('request')->query->get('id');
        $dogovor = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->createQueryBuilder('d')
            ->select('d.startdatetime')
            ->where('d.startdatetime is not NULL')
            ->andWhere('d.startdatetime in (:q)')
            ->setParameter(':q', explode(',', $searchText))
            ->groupBy('d.startdatetime')
            ->orderBy('d.startdatetime', 'DESC')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($dogovor as $object) {
            $result[] = array(
                    'id' => $object['startdatetime']->format('Y-m-d H:i:s'),
                    'value' => $object['startdatetime']->format('Y-m-d H:i:s'),
                    'name' => $object['startdatetime']->format('Y-m-d'),
                    'text' => $object['startdatetime']->format('Y-m-d')
                );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json dogovor type list
     *
     * @return string
     */
    public function dogovorStopdatetimeAction()
    {
        $dogovor = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->createQueryBuilder('d')
            ->select('d.stopdatetime')
            ->where('d.stopdatetime is not NULL')
            ->groupBy('d.stopdatetime')
            ->orderBy('d.stopdatetime', 'DESC')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($dogovor as $object) {
            $result[] = array(
                    'id' => $object['stopdatetime']->format('Y-m-d H:i:s'),
                    'value' => $object['stopdatetime']->format('Y-m-d H:i:s'),
                    'name' => $object['stopdatetime']->format('Y-m-d'),
                    'text' => $object['stopdatetime']->format('Y-m-d')
                );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json dogovor type list
     *
     * @return string
     */
    public function dogovorStopdatetimeIdSAction()
    {
        $searchText = $this->get('request')->query->get('id');
        $dateArr = explode(',', $searchText);
        /** @var \Lists\DogovorBundle\Entity\DogovorRepository $dogovor */
        $dogovor = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->createQueryBuilder('d')
            ->select('d.stopdatetime')
            ->where('d.stopdatetime is not NULL')
            ->andWhere('d.stopdatetime in (:q)')
            ->setParameter(':q', $dateArr)
            ->groupBy('d.stopdatetime')
            ->orderBy('d.stopdatetime', 'DESC')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($dogovor as $object) {
            $result[] = array(
                    'id' => $object['stopdatetime']->format('Y-m-d H:i:s'),
                    'value' => $object['stopdatetime']->format('Y-m-d H:i:s'),
                    'name' => $object['stopdatetime']->format('Y-m-d'),
                    'text' => $object['stopdatetime']->format('Y-m-d')
                );
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json organization group list
     *
     * @return string
     */
    public function organizationGroupAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $organizationTypes = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationGroup')
            ->createQueryBuilder('og')
            ->where('lower(og.name) LIKE :q')
            ->setParameter(':q', '%' . mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($organizationTypes as $organization) {
            $result[] = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json organization group list
     *
     * @return string
     */
    public function organizationLookupAction()
    {
        $lookup = $this->getDoctrine()
            ->getRepository('ListsLookupBundle:Lookup')
                ->getLookupsGroup('organization_sign')
                ->getQuery()
                ->getResult();

        $result = array();

        foreach ($lookup as $organization) {
            $result[] = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json organization list for contacts query
     *
     * @return string
     */
    public function handlingStatusAction()
    {
        $objects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingStatus')
            ->createQueryBuilder('s')
            ->orderBy('s.sortorder')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json handling result list
     *
     * @return string
     */
    public function handlingResultAction()
    {
        $objects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingResult')
            ->createQueryBuilder('s')
            ->orderBy('s.sortorder')
            ->getQuery()
            ->getResult();

        $result = array();

        $result[] = $this->getEmptyResult();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns empty result array
     *
     * @return mixed
     */
    protected function getEmptyResult()
    {
        return array(
            'id' => '',
            'value' => '',
            'name' => '',
            'text' => ''
        );
    }

    /**
     * Returns json handling type list
     *
     * @return string
     */
    public function handlingTypeAction()
    {
        $objects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingType')
            ->createQueryBuilder('s')
            ->orderBy('s.sortorder')
            ->getQuery()
            ->getResult();

        $result = array();

        $result[] = $this->getEmptyResult();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json model contact type list
     *
     * @return string
     */
    public function modelContactTypeAction()
    {
        $objects = $this->getDoctrine()
            ->getRepository('ListsContactBundle:ModelContactType')
            ->createQueryBuilder('mct')
            ->getQuery()
            ->getResult();

        $result = array();

        $result[] = array(
            'id' => '',
            'value' => '',
            'name' => '',
            'text' => ''
        );

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json scope list (from lookup table)
     *
     * @return string
     */
    public function scopeAction()
    {
        $searchText = $this->get('request')->query->get('query');

        /** @var \Lists\LookupBundle\Entity\LookupRepository $repository */
        $repository = $this->container->get('lists_lookup.repository');

        $objects = $repository->getOnlyScopeQuery()
            ->andWhere('lower(l.name) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json users list
     *
     * @return string
     */
    public function userAction()
    {
        $searchText = $this->get('request')->query->get('query');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->container->get('sd_user.repository');

        $objects = $repository->getOnlyStuff()
            ->andWhere('lower(u.firstName) LIKE :q OR lower(u.lastName) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json users list
     *
     * @return string
     */
    public function userAllStuffAction()
    {
        $searchText = $this->get('request')->query->get('query');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->container->get('sd_user.repository');

        $objects = $repository->getAllUsersStuff()
            ->andWhere('lower(u.firstName) LIKE :q OR lower(u.lastName) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json users list
     *
     * @return string
     */
    public function userFIOAction()
    {
        $searchText = $this->get('request')->query->get('query');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->container->get('sd_user.repository');

        $objects = $repository->getOnlyStuff()
            ->andWhere('lower(u.firstName) LIKE :q OR lower(u.lastName) LIKE :q OR lower(u.middleName) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $string = $object->getLastName().' '.$object->getFirstName().' '.$object->getMiddleName();

            $result[] =  array(
                    'id' => $object->getId(),
                    'value' => $object->getId(),
                    'name' => $string,
                    'text' => $string
                );
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json users list
     *
     * @return string
     */
    public function userFIONotMyAction()
    {
        $searchText = $this->get('request')->query->get('query');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->container->get('sd_user.repository');

        $objects = $repository->getOnlyStuff()
            ->andWhere('lower(u.firstName) LIKE :q OR lower(u.lastName) LIKE :q OR lower(u.middleName) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%');
        if (!$this->getUser()->hasRole('ROLE_ARTICLEADMIN')) {
             $objects->andWhere('u.id != :id')
                 ->setParameter(':id', $this->getUser()->getId());
        }
        $objects = $objects->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $string = $object->getLastName().' '.$object->getFirstName().' '.$object->getMiddleName();

            $result[] =  array(
                    'id' => $object->getId(),
                    'value' => $object->getId(),
                    'name' => $string,
                    'text' => $string
                );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json users list
     *
     * @return string
     */
    public function userPositionAction()
    {
        $searchText = $this->get('request')->query->get('query');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->container->get('sd_user.repository');

        $objects = $repository->getOnlyStuff()
            ->andWhere('lower(u.position) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object, false, 'getPosition');
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json users list
     *
     * @return string
     */
    public function userEmailAction()
    {
        $searchText = $this->get('request')->query->get('query');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->container->get('sd_user.repository');

        $objects = $repository->getOnlyStuff()
            ->andWhere('lower(u.email) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object, false, 'getEmail');
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json users list
     *
     * @return string
     */
    public function userPhoneAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->container->get('sd_user.repository');

        $objects = $repository->getOnlyStuff()
            ->andWhere('lower(stuff.mobilephone) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $result[] = array(
                'id' => $object->getId(),
                'value' => $object->getId(),
                'name' => $object->getStuff()->getMobilephone(),
                'text' => $object->getStuff()->getMobilephone()
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json users list
     *
     * @return string
     */
    public function userStuffCompanyAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $dm = $this->getDoctrine()->getManager();

        /** @var Companystructure $companystructure */
        $companystructure = $dm->getRepository('ListsCompanystructureBundle:Companystructure');

        $objects = $companystructure->createQueryBuilder('c')
            ->andWhere('lower(c.name) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object, false, 'getName');
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json contact phones list already user in other contacts
     *
     * @return string
     */
    public function contactPhoneAction()
    {
        $searchText = $this->get('request')->query->get('query');
        $organizationId = $this->get('request')->query->get('organizationId');
        //$phoneType = $this->get('request')->query->get('phoneType');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact');

        $objects = $repository->getSearchPhoneQuery(trim($searchText), $organizationId)
            ->getQuery()
            ->getResult();

        $result = array();

        $result[] = array(
            'id' => $searchText,
            'value' => $searchText,
            'name' => $searchText,
            'text' => $searchText
        );

        foreach ($objects as $object) {
            $this->processModelContactForJson($object);

            $result[] = $this->serializeArray($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json contact emails list already user in other contacts
     *
     * @return string
     */
    public function contactEmailAction()
    {
        $searchText = $this->get('request')->query->get('query');
        $organizationId = $this->get('request')->query->get('organizationId');
        //$phoneType = $this->get('request')->query->get('phoneType');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact');

        $objects = $repository->getSearchEmailQuery(trim($searchText), $organizationId)
            ->getQuery()
            ->getResult();

        $result = array();

        $result[] = array(
            'id' => $searchText,
            'value' => $searchText,
            'name' => $searchText,
            'text' => $searchText
        );

        foreach ($objects as $object) {
            $this->processModelContactForJson($object, array('email'));

            $result[] = $this->serializeArray($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Processes model contact item form json output
     *
     * @param mixed[] &$item
     * @param mixed[] $keys
     *
     * @return mixed[] $item
     */
    public function processModelContactForJson(&$item, $keys = array('phone1', 'phone2'))
    {
        $value = '';

        $item['id'] = '';

        foreach ($keys as $key) {
            if ($item[$key]) {
                $value .= ' ' . $item[$key];
            }
        }

        if ($item['ownerFullName']) {
            $value .= ' ' . $item['ownerFullName'];
        }

        if ($item['creatorFullName'] && !$item['ownerFullName']) {
            $value .= ' ' . $item['creatorFullName'];
        }

        if ($item['organizationName']) {
            $value .= ' ' . $item['organizationName'];
        }

        $item['value'] = $value;
    }

    /**
     * Processes model contact item form json output
     *
     * @param mixed[] &$item
     *
     * @return mixed[] $item
     */
    public function processOrganizationForJson(&$item)
    {
        $value = '';

        if ($item['organizationEdrpou']) {
            $value .= $item['organizationEdrpou'] . ' | ';
        }

        if ($item['organizationName']) {
            $value .= $item['organizationName'];
        }

        if ($item['organizationShortName']) {
            $value .= ' | ' . $item['organizationShortName'];
        }

        if ($item['fullNames']) {
            $value .= ' | ' . $item['fullNames'];
        }

        $item['value'] = $value;
    }

    /**
     * Returns json organization object by requested id
     *
     * @return string
     */
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

        if ($organization) {
            $result = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json organization object by requested id
     *
     * @return string
     */
    public function organizationByIdSAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var Organization[] $organizations */
        $organizations = $organizationsRepository
            ->createQueryBuilder('o')
            ->where('o.id in (:ids)')
            ->setParameter(':ids', $ids)
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($organizations as $organization) {
            $result[] = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json organization object by requested id
     *
     * @return string
     */
    public function organizationByEdrpousAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var Organization[] $organizations */
        $organizations = $organizationsRepository
            ->createQueryBuilder('o')
            ->where('o.edrpou in (:edrpou)')
            ->setParameter(':edrpou', $ids)
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($organizations as $organization) {
            $result[] = $this->serializeObject($organization, 'getEdrpou', 'getEdrpou');
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json user object by requested id
     *
     * @return string
     */
    public function userByIdAction()
    {
        $id = $this->get('request')->query->get('id');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('SDUserBundle:User');

        /** @var \SD\UserBundle\Entity\User $object */
        $object = $repository
            ->find($id);

        $result = array();

        if ($object) {
            $result = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json user object by requested id
     *
     * @return string
     */
    public function userByIdsAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('SDUserBundle:User');

        /** @var \SD\UserBundle\Entity\User $object */
        $objects = $repository
            ->createQueryBuilder('u')
            ->where('u.id in (:ids)')
            ->setParameter(':ids', $ids)
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Serialize object to json. temporary solution
     *
     * @param object $object
     * @param string $idMethod
     * @param string $method
     *
     * @return mixed[]
     */
    public function serializeObject($object, $idMethod = '', $method = '')
    {
        $id = $idMethod ? $object->$idMethod() : $object->getId();
        $string = $method ? $object->$method() : (string) $object;

        return array(
            'id' => $id,
            'value' => $id,
            'name' => $string,
            'text' => $string
        );
    }

    /**
     * Serialize array to json. temporary solution
     *
     * @param mixed[] $item
     * @param string  $id
     * @param string  $value
     *
     * @return mixed[]
     */
    public function serializeArray($item, $id = '', $value = '')
    {
        $id = $id ? $item[$id] : $item['id'];
        $string = $value ? $item[$value] : $item['value'];

        return array(
            'id' => $id,
            'value' => $id,
            'name' => (string) $string,
            'text' => (string) $string
        );
    }

    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function organizationSaveAction()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($pk);

        if (!$value) {
            $methodGet = 'get' . ucfirst($name);
            $type = gettype($organization->$methodGet());

            if (in_array($type, array('integer'))) {
                $value = null;
            }
        }

        $organization->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($organization, array('edit'));

        if (sizeof($errors)) {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($organization);

        try {
            $em->flush();
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array('success' => 1);

        return new Response(json_encode($return));
    }

    /**
     * Get first error message
     *
     * @param \Symfony\Component\Validator\ConstraintViolationList $errors
     *
     * @return string
     */
    public function getFirstError(\Symfony\Component\Validator\ConstraintViolationList $errors)
    {
        $message = '';

        foreach ($errors as $error) {
            $message = $error->getMessage();
        }

        return $message;
    }

    /**
     * Get first error message
     *
     * @param \Symfony\Component\Validator\ConstraintViolationList $errors
     * @param string                                               $field
     *
     * @return string
     */
    public function getErrorByField(\Symfony\Component\Validator\ConstraintViolationList $errors, $field)
    {
        $message = '';

        /** @var ConstraintViolation[] $errors */
        foreach ($errors as $error) {
            if ($error->getPropertyPath() == $field) {
                $message = $error->getMessage();
            }
        }

        return $message;
    }

    /**
     * Renders/validates ajax form
     *
     * @param Request $request
     *
     * @return string (json)
     */
    public function formAction(Request $request)
    {
        $formName = $request->get('formName');

        $defaultData = $request->get('defaultData');
        $postFunction = $request->get('postFunction');
        $postTargetId = $request->get('postTargetId');
        $targetId = $request->get('targetId');
        $model = $request->get('model');
        $modelId = $request->get('modelId');

        if ($model && $modelId) {
            if (isset($this->modelRepositoryDependence[$model])) {
                $repository = $this->modelRepositoryDependence[$model];

                $object = $this->getDoctrine()->getRepository($repository)
                    ->find($modelId);

                $form = $this->createForm($formName, $object);
            }
        } else {
            $form = $this->createForm($formName);
        }

        if ($defaultData && !is_array($defaultData)) {
            $defaultData = json_decode(stripslashes($defaultData), true);
        }

        if (sizeof($defaultData)) {

            foreach ($defaultData as $key => $default) {
                $form->add($key, 'hidden', array(
                    'data' => $default
                ));
            }

            $processMethod = $formName . 'ProcessDefaults';

            if (method_exists($this, $processMethod)) {
                $this->$processMethod($form, $defaultData);
            }
        }

        $form->handleRequest($request);

        $result = array(
            'error' => 1,
            'html' => '',
            'postFunction' => $postFunction,
            'postTargetId' => $postTargetId,
            'targetId' => $targetId,
            'model' => $model,
            'modelId' => $modelId,
            'defaultData' => $defaultData
        );

        if ($form->isValid()) {

            $method = $formName . 'Save';

            $user = $this->getUser();

            $this->$method($form, $user, $request);

            unset($result['error']);

            $result['success'] = true;
        }

        $result['html'] = $this->renderView('SDCommonBundle:AjaxForm:' . $formName . '.html.twig', array(
            'form' => $form->createView(),
            'formName' => $formName,
            'postFunction' => $postFunction,
            'postTargetId' => $postTargetId,
            'targetId' => $targetId,
            'defaultData' => $defaultData,
            'model' => $model,
            'modelId' => $modelId,
        ));

        return new Response(json_encode($result));
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function organizationUserFormSave($form, $user, $request)
    {
        $data = $form->getData();

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($data['organizationId']);

        $user = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')
            ->find($data['user']);

        $organization->addUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($organization);
        $em->flush();

        return true;
    }
    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function organizationUserSalesAdminFormSave($form, $user, $request)
    {
        $data = $form->getData();

        if ($this->getUser()->hasRole('ROLE_SALESADMIN')) {
            $serviceOrganizationUser = $this->container->get('lists_organization.user.service');
            $serviceOrganizationUser->changeManagerOrganizationProject($data['organizationId'], $data['user']);

            return true;
        }
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function dogovorHistoryFormSave($form, $user, $request)
    {
        /** @var Dogovor $dogovor */
        $dogovor = $form->getData();

        $requestParams = $request->request->get($form->getName());

        $user = $this->getUser();

        $dogovorHistory = new DogovorHistory();

        $dogovorHistory->setDogovor($dogovor);
        $dogovorHistory->setUser($user);
        $dogovorHistory->setCreatedatetime(new \DateTime());

        $prolongationDateFrom = new \DateTime();

        $prolongationDateTo = new \DateTime($requestParams['prolongationDateTo']);

        if ($dogovor->getProlongation()) {
            // set stop date to prolongation date
            // set prolongation date to $request['prolongationDateTo']
            // Set prolongation date to
            if ($dogovor->getProlongationDate()) {
                $prolongationDateFrom = $dogovor->getProlongationDate();
            } elseif ($dogovor->getStopdatetime()) {
                $prolongationDateFrom = $dogovor->getStopdatetime();
            } elseif ($dogovor->getStartdatetime()) {
                $prolongationDateFrom = $dogovor->getStartdatetime();
            }
        } else {
            /** @var DopDogovorRepository $ddr */
            $ddr = $this->get('lists_dogovor.dopdogovor.repository');

            $dopDogovor = $ddr->find($requestParams['dopDogovor']);

            $dogovorHistory->setDopDogovor($dopDogovor);
        }

        $dogovor->setProlongationDate($prolongationDateTo);

        $dogovorHistory->setProlongationDateFrom($prolongationDateFrom);
        $dogovorHistory->setProlongationDateTo($prolongationDateTo);

        $em = $this->getDoctrine()->getManager();
        $em->persist($dogovor);
        $em->persist($dogovorHistory);
        $em->flush();

        return true;
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function handlingMessageFormSave(Form $form, $user, $request)
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingMessage $data */
        $data = $form->getData();

        $formData = $request->request->get($form->getName());

        $handlingId = $data->getHandlingId();

        /** @var \Lists\HandlingBundle\Entity\Handling $handling */
        $handling = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->find($handlingId);

        $data->setCreatedatetime(new \DateTime());

        $user = $this->getUser();

        if (!$data->getUser()) {
            $data->setUser($user);
        }

        $data->setHandling($handling);

        $file = $form['file']->getData();

        if ($file) {
            $data->upload();
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        // $em->flush();
        // Insert future
        $type = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingMessageType')
            ->find($formData['nexttype']);

        $nextDatetime = new \DateTime($formData['nextcreatedate']);
        $contactNext = $formData['contactnext'];
        $descriptionNext = $formData['descriptionnext'];
        $statusId = $formData['status'];
        //$nextUser

        $handlingMessage = new HandlingMessage();
        $handlingMessage->setCreatedate($nextDatetime);
        $handlingMessage->setCreatedatetime(new \DateTime());

        if (isset($formData['userNext']) && $formData['userNext']) {
            /** @var UserRepository $ur */
            $ur = $this->get('sd_user.repository');

            $userNext = $ur->find($formData['userNext']);

            if ($userNext) {
                $handlingMessage->setUser($userNext);
            }
        } else {
            $handlingMessage->setUser($user);
        }

        $handlingMessage->setHandling($handling);
        $handlingMessage->setType($type);
        $handlingMessage->setIsBusinessTrip(isset($formData['next_is_business_trip']) ? true : false);
        $handlingMessage->setAdditionalType(HandlingMessage::ADDITIONAL_TYPE_FUTURE_MESSAGE);

        $handlingMessage->setDescription($descriptionNext);

        if ((int) $contactNext) {
            $contact = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
                ->find((int) $contactNext);

            if ($contact) {
                $handlingMessage->setContact($contact);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($handlingMessage);
        // $em->flush();

        $handling->setLastHandlingDate($data->getCreatedate());
        $handling->setNextHandlingDate($nextDatetime);

        $handling->setStatusId($statusId);

        $em->persist($handling);

        $em->flush();

        return true;
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function invoiceMessageFormSave(Form $form, $user, $request)
    {
        /** @var InvoiceMessage $data */
        $data = $form->getData();

        $formData = $request->request->get($form->getName());

        $invoiceId = $data->getInvoiceId();

        /** @var Invoice $invoice */
        $invoice = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->find($invoiceId);
        $data->setInvoice($invoice);

        $contactid = $formData['contactid'];

        if (is_numeric($contactid)) {
            /** @var ModelContact $contact */
            $contact = $this->getDoctrine()
                ->getRepository('ListsContactBundle:ModelContact')
                ->find($contactid);
            $data->setContact($contact);
        }

        $data->setUser($this->getUser());

        $data->setNote($formData['note']);

        $data->setCreatedate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return true;
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function taskFormSave(Form $form, $user, $request)
    {
        /** @var Task $data */
        $data = $form->getData();

        if (!$data->getId()) {
           $data->setCreateDateTime(new \DateTime());
           $data->setUser($user);
        }

        $formData = $request->request->get($form->getName());

        $data->setTaskType('personal');
        $data->setStartDateTime(new \DateTime($formData['startDateTime']));
        $data->setStopDateTime(new \DateTime($formData['stopDateTime']));

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return true;
    }
    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function userContactinfoFormSave(Form $form, $user, $request)
    {
        /** @var Usercontactinfo $data */
        $data = $form->getData();

        $formData = $request->request->get($form->getName());

        $userContact = new Usercontactinfo();
        $userContact->setValue($data['value']);
        $userContact->setUser($user);
        $contactinfoId = $formData['contactinfo'];

        /** @var Contactinfo $contact */
        $contact = $this->getDoctrine()
            ->getRepository('SDUserBundle:Contactinfo')
            ->find($contactinfoId);

        $userContact->setContactinfo($contact);

        $em = $this->getDoctrine()->getManager();
        $em->persist($userContact);
        $em->flush();

        return true;
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function emailFormSave(Form $form, User $user, Request $request)
    {
        /** @var Email $data */
        $data = $form->getData();

         /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return true;
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function invoiceCompanystructureFormSave(Form $form, User $user, Request $request)
    {

        /** @var InvoiceCompanystructure $data */
        $data = $form->getData();

        $formData = $request->request->get($form->getName());

        /** @var ModelContact $contact */
        $company = $this->getDoctrine()
            ->getRepository('ListsCompanystructureBundle:Companystructure')
            ->find($formData['companystructure']);
        $data->setCompanystructure($company);

        /** @var Invoice $invoice */
        $invoice = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->find($data->getInvoiceID());
        $data->setInvoice($invoice);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return true;
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function handlingUserSalesAdminFormSave($form, $user, $request)
    {
        $data = $form->getData();

        if ($this->getUser()->hasRole('ROLE_SALESADMIN')) {
            $serviceHandlingUser = $this->container->get('lists_handling.user.service');
            $serviceHandlingUser->changeManagerProjectOne($data['handlingId'], $data['user']);

            return true;
        }
    }
    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function handlingUserFormSave($form, $user, $request)
    {
        $data = $form->getData();

        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')
            ->find((int) $data['user']);
        $handling = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->find((int) $data['handlingId']);

        $lookupMPId = $this->getDoctrine()
            ->getRepository('ListsLookupBundle:Lookup')->getOnlyManagerProjectId();

        $mainManager = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingUser')
            ->findOneBy(array(
                'handlingId' => (int) $data['handlingId'],
                'lookupId' => $lookupMPId,
                ));
        $lookupId = $this->getDoctrine()
            ->getRepository('ListsLookupBundle:Lookup')->getOnlyManagerId();

        if (!$mainManager || $this->getUser()->getId() != $mainManager->getUser()->getId()) {
            return false;
        }
            $lookup = $this->getDoctrine()
                ->getRepository('ListsLookupBundle:Lookup')
                ->find($lookupId);

        $object = new HandlingUser();

        $part = $mainManager->getPart()-(int) $data['part'];
        $mainManager->setPart($part);

        $object->setUser($user);
        $object->setHandling($handling);
        $object->setPart((int) $data['part']);
        $object->setLookup($lookup);

        $email = $this->get('it_doors_email.service');
        $url = $this->generateUrl(
            'lists_sales_handling_show',
            array('id' => $handling->getId()),
            true
        );
        $email->send(
            null,
            'manager-add-in-project',
            array(
                'users' => array(
                    $user->getEmail()
                ),
                'variables' => array(
                    '${lastName}$' => $mainManager->getUser()->getLastName(),
                    '${firstName}$' => $mainManager->getUser()->getFirstName(),
                    '${middleName}$' => $mainManager->getUser()->getMiddleName(),
                    '${part}$' => (int) $data['part'],
                    '${id}$' => $handling->getId(),
                    '${url}$' => '<a href="' . $url . '">' . $url . '</a>',
                )
            )
        );
        $cron = $this->container->get('it_doors_cron.service');
        $cron->addSendEmails();

        $em->persist($object);
        $em->persist($mainManager);
        $em->flush();
        $em->refresh($mainManager);

        return true;
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function modelContactOrganizationFormSave($form, $user, $request)
    {
        $data = $form->getData();

        if (!$data->getId()) {
            $data->setUser($user);

            $owner = $data->getOwner();

            if (!$owner) {
                $data->setOwner($user);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);

        $organizationId = $data->getModelId();

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($organizationId);

        $users = $organization->getUsers();

        $userExist = false;

        if ($users) {
            foreach ($users as $orgUser) {
                if ($orgUser->getId() == $user->getId()) {
                    $userExist = true;
                }
            }
        }

        if (!$userExist) {
            $organization->addUser($user);
        }

        $em->persist($organization);

        $em->flush();

        return true;
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function modelContactOrganizationUserFormSave($form, $user, $request)
    {
        return $this->modelContactOrganizationFormSave($form, $user, $request);
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function modelContactOrganizationEditFormSave($form, $user, $request)
    {
        $data = $form->getData();

        if (!$data->getId()) {
            return $this->modelContactOrganizationFormSave($form, $user, $request);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($data);

        $em->flush();

        $em->refresh($data);

        return true;
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function modelContactOrganizationAdminFormSave($form, $user, $request)
    {
        return $this->modelContactOrganizationFormSave($form, $user, $request);
    }

    /**
     * Saves {formName}Save after valid ajax validation
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function modelContactHandlingFormSave($form, $user, $request)
    {
        $data = $form->getData();

        $data->setUser($user);
        $data->setCreatedatetime(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return true;
    }

    /**
     * Deletes instance depending on request params
     * Run custom function for specific class
     *
     * @return string
     */
    public function deleteAction()
    {
        $params = $this->get('request')->request->get('params');

        $method = lcfirst($params['model']) . 'Delete';

        $this->$method($params);

        return new Response('');
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function invoiceCompanystructureDelete($params)
    {
        $id = $params['id'];

        /** @var InvoiceCompanystructure $object */
        $object = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:InvoiceCompanystructure')
            ->find($id);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        $em->flush();
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function usercontactinfoDelete($params)
    {
        $id = $params['id'];

        /** @var InvoiceCompanystructure $object */
        $object = $this->getDoctrine()
            ->getRepository('SDUserBundle:Usercontactinfo')
            ->find($id);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        $em->flush();
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function emailDelete($params)
    {
        $id = $params['id'];

        /** @var Email $object */
        $object = $this->getDoctrine()
            ->getRepository('ITDoorsEmailBundle:Email')
            ->find($id);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        $em->flush();
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function automailerDelete($params)
    {
        $id = $params['id'];

        /** @var Automailer $object */
        $object = $this->getDoctrine()
            ->getRepository('TSSAutomailerBundle:Automailer')
            ->find($id);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        $em->flush();
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function organizationUserDelete($params)
    {
        $organizationId = $params['organizationUserId'];

        $organizationUser = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationUser')
            ->find($organizationId);

        /** @var Lookup $lookup */
        $lookup = $this->getDoctrine()->getRepository('ListsLookupBundle:Lookup')->findOneBy(array('lukey' => 'manager_organization'));

        if ((!$organizationUser->getLookup() || $organizationUser->getLookup()->getId() != $lookup->getId()) && $this->getUser()->hasRole('ROLE_SALESADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($organizationUser);
            $em->flush();
        }

        return false;
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function handlingUserDelete($params)
    {
        $handlingUserId = $params['handlingUserId'];

        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingUser')
            ->find($handlingUserId);

        $lookupId = $this->getDoctrine()
            ->getRepository('ListsLookupBundle:Lookup')->getOnlyManagerProjectId();

        $mainManager = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingUser')
            ->findOneBy(array(
                'handlingId' => $object->getHandlingId(),
                'lookupId' => $lookupId,
                ));
        if ($mainManager->getUser()->getId() != $this->getUser()->getId()) {
            return false;
        }
        $part = $mainManager->getPart()+$object->getPart();
        $mainManager->setPart($part);

        $email = $this->get('it_doors_email.service');
        $email->send(
            null,
            'manager-delete-of-project',
            array(
                'users' => array(
                    $object->getUser()->getEmail()
                ),
                'variables' => array(
                    '${lastName}$' => $mainManager->getUser()->getLastName(),
                    '${firstName}$' => $mainManager->getUser()->getFirstName(),
                    '${middleName}$' => $mainManager->getUser()->getMiddleName(),
                    '${part}$' => $object->getPart(),
                    '${id}$' => $object->getHandlingId()
                )
            )
        );
        $cron = $this->container->get('it_doors_cron.service');
        $cron->addSendEmails();
        $em = $this->getDoctrine()->getManager();
        $em->persist($mainManager);
        $em->remove($object);
        $em->flush();
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function handlingCompetitorDelete($params)
    {
        $id = $params['id'];

        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingCompetitor')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        $em->flush();
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function handlingDogovorDelete($params)
    {
        $id = $params['id'];

        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingDogovor')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        $em->flush();
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function dopDogovorDelete($params)
    {
        $id = $params['id'];

        /** @var DopDogovor $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:DopDogovor')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        $em->flush();
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function dogovorDepartmentDelete($params)
    {
        $id = $params['id'];

        /** @var DogovorDepartment $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:DogovorDepartment')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        $em->flush();
    }

    /**
     * Deletes {entityName}Delete instance
     *
     * @param mixed[] $params
     *
     * @return void
     */
    public function modelContactDelete($params)
    {
        $id = $params['id'];

        $object = $this->getDoctrine()
            ->getRepository('ListsContactBundle:ModelContact')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->flush();
    }

    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function handlingSaveAction()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        /** @var Handling $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->find($pk);

        if (!$value) {
            $methodGet = 'get' . ucfirst($name);
            $type = gettype($object->$methodGet());

            if (in_array($type, array('integer'))) {
                $value = null;
            }
        }

        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors)) {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        try {
            $em->flush();

            $em->refresh($object);
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array(
            'success' => 1,
            'handling' => array(
                'id' => $object->getId()
            )
        );

        $result = $object->getResult();
        $status = $object->getStatus();

        if ($result && $result->getProgress()) {
            $return['handling']['progress'] = $result->getProgress();
            $return['handling']['progressString'] = $result->getPercentageString();
        } elseif ($status && $status->getProgress()) {
            $return['handling']['progress'] = $status->getProgress();
            $return['handling']['progressString'] = $status->getPercentageString();
        } else {
            $return['handling']['progress'] = null;
            $return['handling']['progressString'] = null;
        }

        return new Response(json_encode($return));
    }
    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function handlingUserPartChangeAction()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = (int) $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        /** @var HandlingUser $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingUser')
            ->find($pk);

        if (!$value) {
            $methodGet = 'get' . ucfirst($name);
            $type = gettype($object->$methodGet());

            if (in_array($type, array('integer'))) {
                $value = null;
            }
        }
        $lookupMPId = $this->getDoctrine()
            ->getRepository('ListsLookupBundle:Lookup')->getOnlyManagerProjectId();

        $mainManager = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingUser')
            ->findOneBy(array(
                'handlingId' => $object->getHandlingId(),
                'lookupId' => $lookupMPId,
                ));
        if (!$mainManager || $this->getUser()->getId() != $mainManager->getUser()->getId()) {
            $return = array(
                'success' => 0,
                'handlingUser' => array(
                    'id' => $object->getId(),
                    'error' => 'no access',
                    'value' => $object->getPart()
                )
            );

            return new Response(json_encode($return));
        } elseif ($mainManager->getPart() < $value) {
            $return = array(
                'success' => 0,
                'handlingUser' => array(
                    'id' => $object->getId(),
                    'error' => 'max '.$mainManager->getPart(),
                    'value' => $object->getPart()
                )
            );

            return new Response(json_encode($return));
        }
        $part = $mainManager->getPart()+$object->getPart()-$value;
        $mainManager->setPart($part);

        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors)) {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->persist($mainManager);

        try {
            $em->flush();

            $em->refresh($object);
            $email = $this->get('it_doors_email.service');
            $url = $this->generateUrl(
                'lists_sales_handling_show',
                array('id' => $object->getHandlingId()),
                true
            );
            $email->send(
                null,
                'manager-change-part-in-project',
                array(
                    'users' => array(
                        $object->getUser()->getEmail()
                    ),
                    'variables' => array(
                        '${lastName}$' => $mainManager->getUser()->getLastName(),
                        '${firstName}$' => $mainManager->getUser()->getFirstName(),
                        '${middleName}$' => $mainManager->getUser()->getMiddleName(),
                        '${part}$' => $value,
                        '${id}$' => $object->getHandlingId(),
                        '${url}$' => '<a href="' . $url . '">' . $url . '</a>',
                    )
                )
            );
        $cron = $this->container->get('it_doors_cron.service');
        $cron->addSendEmails();
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array(
            'success' => 1,
            'handlingUser' => array(
                'id' => $object->getId(),
                'managerPart' => $part,
                'value' => $value
            )
        );

        return new Response(json_encode($return));
    }

    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function dogovorSaveAction()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        $object = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($pk);

        if (!$value) {
            $methodGet = 'get' . ucfirst($name);
            $type = gettype($object->$methodGet());

            if (in_array($type, array('integer'))) {
                $value = null;
            }
        }

        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors)) {
            $return = $this->getErrorByField($errors, $name);

            if ($return) {
                return new Response($return, 406);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        try {
            $em->flush();
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array('success' => 1);

        return new Response(json_encode($return));
    }

    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function handlingMoreInfoSaveAction()
    {
        $translator = $this->get('translator');

        $pks = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $handlingId = $pks['handlingId'];
        $typeId = $pks['typeId'];

        $query = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingMoreInfo')
            ->createQueryBuilder('hmi')
            ->where('hmi.handlingId = :handlingId')
            ->andWhere('hmi.handlingMoreInfoTypeId = :handlingMoreInfoTypeId')
            ->setParameter(':handlingId', $handlingId)
            ->setParameter(':handlingMoreInfoTypeId', $typeId)
            ->getQuery();

        try {
            $object = $query->getSingleResult();
        } catch (\Doctrine\Orm\NoResultException $e) {
            $object = null;
        }

        if ($object) {
            $object->setValue($value);
        } else {
            $object = new HandlingMoreInfo();

            $handling = $this->getDoctrine()
                ->getRepository('ListsHandlingBundle:Handling')
                ->find($handlingId);

            $type = $this->getDoctrine()
                ->getRepository('ListsHandlingBundle:HandlingMoreInfoType')
                ->find($typeId);

            $object->setHandling($handling);
            $object->setHandlingMoreInfoType($type);
        }

        $object->setValue($value);

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        try {
            $em->flush();
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array('success' => 1);

        return new Response(json_encode($return));
    }

    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function handlingServiceSaveAction()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value') ? $this->get('request')->request->get('value') : array();

        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->find($pk);

        $handlingServices = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->findAll();

        foreach ($handlingServices as $hs) {
            $object->removeHandlingService($hs);

            if (in_array($hs->getId(), $value)) {
                $object->addHandlingService($hs);
            }
        }

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors)) {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        try {
            $em->flush();
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array('success' => 1);

        return new Response(json_encode($return));
    }

    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function modelContactSaveAction()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        $object = $this->getDoctrine()
            ->getRepository('ListsContactBundle:ModelContact')
            ->find($pk);

        if (!$value) {
            $methodGet = 'get' . ucfirst($name);
            $type = gettype($object->$methodGet());

            if (in_array($type, array('integer'))) {
                $value = null;
            }
        }

        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors)) {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        try {
            $em->flush();
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array('success' => 1);

        return new Response(json_encode($return));
    }

    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function invoiceSaveAction()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');

        if ($name == 'DateEnd') {
            $value = new \DateTime($this->get('request')->request->get('value'));
        } elseif ($name == 'court') {
            $value = (boolean) $this->get('request')->request->get('value');
        } else {
            $value = $this->get('request')->request->get('value');
        }

        $methodSet = 'set' . ucfirst($name);

        /** @var Invoice $object */
        $object = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->find($pk);

        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors)) {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        try {
            $em->flush();
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array('success' => 1);

        return new Response(json_encode($return));
    }

    /**
     * Adds children to {formName}ProcessDefaults depending on defaults in request
     *
     * @param Form    $form
     * @param mixed[] $defaultData
     *
     * @return void
     */
    public function handlingMessageFormProcessDefaults($form, $defaultData)
    {
        $handlingId = $defaultData['handling_id'];

        /** @var \Lists\HandlingBundle\Entity\Handling $handling */
        $handling = $this->getDoctrine()->getRepository('ListsHandlingBundle:Handling')
            ->find($handlingId);

        $creator = $handling->getUser();

        $userIds = array();

        $userIds[$creator->getId()] = $creator->getId();

        /** @var HandlingUser[] $users */
        $users = $handling->getHandlingUsers();

        if ($users) {
            foreach ($users as $user) {
                $userIds[$user->getUserId()] = $user->getUserId();
            }
        }

        $organizationId = $handling->getOrganizationId();

        $form
            ->add('contact', 'entity', array(
                'class' => 'ListsContactBundle:ModelContact',
                'empty_value' => '',
                'required' => false,
                'query_builder' => function (ModelContactRepository $repository) use ($organizationId, $userIds) {
                    return $repository->createQueryBuilder('mc')
                        ->leftJoin('mc.owner', 'owner')
                        ->where('mc.modelName = :modelName')
                        ->andWhere('mc.modelId = :modelId')
                        ->andWhere('owner.id in (:ownerIds)')
                        ->setParameter(':modelName', ModelContactRepository::MODEL_ORGANIZATION)
                        ->setParameter(':modelId', $organizationId)
                        ->setParameter(':ownerIds', $userIds);
                }
            ));

         $form
            ->add('contactnext', 'entity', array(
                'class' => 'ListsContactBundle:ModelContact',
                'empty_value' => '',
                'required' => false,
                'mapped' => false,
                'query_builder' => function (ModelContactRepository $repository) use ($organizationId, $userIds) {
                        return $repository->createQueryBuilder('mc')
                            ->leftJoin('mc.owner', 'owner')
                            ->where('mc.modelName = :modelName')
                            ->andWhere('mc.modelId = :modelId')
                            ->andWhere('owner.id in (:ownerIds)')
                            ->setParameter(':modelName', ModelContactRepository::MODEL_ORGANIZATION)
                            ->setParameter(':modelId', $organizationId)
                            ->setParameter(':ownerIds', $userIds);
                }
            ));

        $form
            ->add('status', 'entity', array(
                'class' => 'ListsHandlingBundle:HandlingStatus',
                'data' => $handling->getStatus(),
                'empty_value' => '',
                'mapped' => false,
                'query_builder' => function (\Lists\HandlingBundle\Entity\HandlingStatusRepository $repository) {
                        return $repository->createQueryBuilder('s')
                            ->orderBy('s.sortorder', 'ASC');
                }
            ));

        $form
            ->add('mindate', 'hidden', array(
                'data' => $defaultData['mindate'],
                'mapped' => false
        ));
    }

    /**
     * Adds children to {formName}ProcessDefaults depending on defaults in request
     *
     * @param Form    $form
     * @param mixed[] $defaultData
     * 
     * @return Form
     */
    public function invoiceMessageFormProcessDefaults($form, $defaultData)
    {
        $invoiceId = $defaultData['invoice_id'];
        /** @var Invoice $invoiceObj */
        $invoiceObj = $this->getDoctrine()
            ->getRepository('ITDoorsControllingBundle:Invoice')
            ->find($invoiceId);
        if ($invoiceObj->getDogovor()) {
            /** @var Dogovor $dogovor */
            $dogovor = $this->getDoctrine()
                ->getRepository('ListsDogovorBundle:Dogovor')
                ->find($invoiceObj->getDogovor());

            $organizationId =
                $dogovor->getCustomerId() ?
                $dogovor->getCustomerId() :
                (
                    $dogovor->getOrganization() ?
                    $dogovor->getOrganization()->getId() :
                    $invoiceObj->getOrganization()->getId()
                );
            $form
                ->add('contactid', 'entity', array(
                    'class' => 'ListsContactBundle:ModelContact',
                    'empty_value' => '',
                    'required' => false,
                    'mapped' => false,
                    'query_builder' => function (ModelContactRepository $repository) use ($organizationId) {
                        return $repository->createQueryBuilder('mc')
                            ->leftJoin('mc.owner', 'owner')
                            ->where('mc.modelName = :modelName')
                            ->andWhere('mc.modelId = :modelId')
                            ->setParameter(':modelName', ModelContactRepository::MODEL_ORGANIZATION)
                            ->setParameter(':modelId', $organizationId);
                    }
                ));
        } else {
            $form
                ->add('contactid', 'entity', array(
                    'class' => 'ListsContactBundle:ModelContact',
                    'empty_value' => '',
                    'required' => false,
                    'mapped' => false,
                    'query_builder' => function (ModelContactRepository $repository) {
                    return $repository->createQueryBuilder('mc')
                        ->leftJoin('mc.owner', 'owner')
                        ->where('mc.modelName = :modelName')
                        ->andWhere('mc.modelId = :modelId')
                        ->setParameter(':modelName', ModelContactRepository::MODEL_ORGANIZATION)
                        ->setParameter(':modelId', 0);
                    }
                ));
        }
    }

    /**
     * Adds children to {formName}ProcessDefaults depending on defaults in request
     *
     * @param Form    $form
     * @param mixed[] $defaultData
     * 
     * @return QueryBuilder
     */
    public function invoiceCompanystructureFormProcessDefaults(Form $form, $defaultData)
    {
        $invoiceId = $defaultData['invoiceId'];

        $repository = $this->getDoctrine()->getRepository('ListsCompanystructureBundle:Companystructure');

        $form
            ->add('companystructure', 'entity', array(
                'class' => 'ListsCompanystructureBundle:Companystructure',
                'empty_value' => '',
                'required' => false,
                'mapped' => false,
                'query_builder' => function ($repository) use ($invoiceId, $repository) {

                return $repository->createQueryBuilder('c')
                    ->leftJoin('c.invoicecompanystructure', 'idc')
                    ->where('(idc.invoiceId is NULL OR idc.invoiceId <> :invoiceId)')
                    ->setParameter(':invoiceId', $invoiceId);
                }
            ));
    }

    /**
     * Renders handling more information
     *
     * @param Request $request
     *
     * @return string
     */
    public function handlingMoreInfoAction(Request $request)
    {
        $handlingId = $request->request->get('handlingId');
        $resultId = $request->request->get('resultId');

        $types = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingMoreInfoType')
            ->createQueryBuilder('hmit')
            ->where('hmit.handlingResultId = :resultId')
            ->setParameter(':resultId', $resultId)
            ->getQuery()
            ->getREsult();

        $moreInfoObjects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingMoreInfo')
            ->createQueryBuilder('hmi')
            ->where('hmi.handlingId = :handlingId')
            ->setParameter(':handlingId', $handlingId)
            ->getQuery()
            ->getREsult();

        $moreInfoArray = $this->processHandlingMoreInfo($moreInfoObjects);

        $result = array(
            'success' => 1,
        );

        $html = $this->renderView('SDCommonBundle:Ajax:moreInfo.html.twig', array(
            'types' => $types,
            'moreInfo' => $moreInfoArray,
            'handlingId' => $handlingId
        ));

        $result['html'] = $html;

        return new Response(json_encode($result));
    }

    /**
     * processHandlingMoreInfo
     *
     * @param mixed[] $moreInfoObjects
     *
     * @return mixed[]
     */
    public function processHandlingMoreInfo($moreInfoObjects)
    {
        $result = array();

        foreach ($moreInfoObjects as $object) {
            $typeId = $object->getHandlingMoreInfoTypeId();

            if ($typeId) {
                $result[$typeId] = $object;
            }
        }

        return $result;
    }

    /**
     * Saves organizationChildForm
     *
     * processes setting child organization
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return boolean
     */
    public function organizationChildFormSave(Form $form, User $user, Request $request)
    {
        $data = $form->getData();

        $organizationId = $data['organizationId'];

        $organizationChildId = $data['organizationChildId'];

        $em = $this->getDoctrine()->getManager();

        /** @var Connection $connection */
        $connection = $em->getConnection();

        $connection->beginTransaction();

        try {
            /** @var Organization $organizationChild */
            $organizationChild = $em->getRepository('ListsOrganizationBundle:Organization')
                ->find($organizationChildId);

            $organizationChild->setParentId($organizationId);

            $em->persist($organizationChild);
            $em->flush();

            // Client Update
            $sql = "UPDATE client set organization_id = :organizationId where organization_id = :organizationChildId";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Client Organization Update
            $sql = "
            UPDATE
                client_organization
            SET
                organization_id = :organizationId
            WHERE
                organization_id = :organizationChildId AND
            NOT EXISTS (
                SELECT
                  *
                FROM
                  client_organization temp_table
                WHERE
                  temp_table.organization_id = :organizationId AND
                  temp_table.client_id = client_organization.client_id
            )";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Contract Update
            $sql = "UPDATE contract set organization_id = :organizationId where organization_id = :organizationChildId";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Contract Importance Update
            $sql = "
            UPDATE
                contract_importance
            SET
                organization_id = :organizationId
            WHERE
                organization_id = :organizationChildId AND
            NOT EXISTS (
                SELECT
                  *
                FROM
                  contract_importance temp_table
                WHERE
                  temp_table.organization_id = :organizationId AND
                  temp_table.importance_id = contract_importance.importance_id
            )";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Departments Update
            $sql = "UPDATE
                departments
            SET
                organization_id = :organizationId
            WHERE
                organization_id = :organizationChildId";

            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Dogovor Update
            $sql = "UPDATE dogovor set organization_id = :organizationId where organization_id = :organizationChildId";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Dogovor Customer Update
            $sql = "UPDATE dogovor set customer_id = :organizationId where customer_id = :organizationChildId";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Dogovor Performer Update
            $sql = "UPDATE dogovor set performer_id = :organizationId where performer_id = :organizationChildId";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Handling Update
            $sql = "UPDATE handling set organization_id = :organizationId where organization_id = :organizationChildId";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Organization Importance Update
            $sql = "
            UPDATE
                organization_importance
            SET
                organization_id = :organizationId
            WHERE
                organization_id = :organizationChildId AND
            NOT EXISTS (
                SELECT
                  *
                FROM
                  organization_importance temp_table
                WHERE
                  temp_table.organization_id = :organizationId AND
                  temp_table.importance_id = organization_importance.importance_id
            )";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Organization User Update
            $sql = "
            UPDATE
                organization_user
            SET
                organization_id = :organizationId
            WHERE
                organization_id = :organizationChildId AND
            NOT EXISTS (
                SELECT
                  *
                FROM
                  organization_user ou
                WHERE
                  ou.organization_id = :organizationId AND
                  ou.user_id = organization_user.user_id
            )";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId
            ));

            // Model Contact Update
            $sql = "
            UPDATE
                model_contact
            SET
                model_id = :organizationId
            WHERE
                model_id = :organizationChildId AND
                model_name = :modelName";
            $statement = $connection->prepare($sql);
            $statement->execute(array(
                ':organizationId' => $organizationId,
                ':organizationChildId' => $organizationChildId,
                ':modelName' => 'organization'
            ));

            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            $em->close();
            throw $e;
        }

        return true;
    }

    /**
     * changePasswordFormSave
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function changePasswordFormSave($form, $user, $request)
    {
        $data = $form->getData();

        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($data);

        $this->get('session')->set(
            'noticePassword',
            'Password changed successfully!'
        );

        return true;
    }

    /**
     * Saves dop dogovor ajax form
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function dopDogovorFormSave($form, $user, $request)
    {
        $data = $form->getData();

        if (!$data->getId()) {
            $data->setUser($user);
        }

        $file = $form['file']->getData();

        if ($file) {
            $data->upload();
        }

        $dogovorId = $data->getDogovorId();

        $dogovor = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($dogovorId);

        $data->setDogovor($dogovor);
        $data->setCreateDateTime(new \DateTime(date('Y-m-d H:i:s')));

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return true;
    }

    /**
     * Saves dop dogovor ajax form
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return boolean
     */
    public function dopDogovorEditFormSave($form, $user, $request)
    {
        $data = $form->getData();
//        if (!$data->getId()) {
//            $data->setUser($user);
//        }

        $dogovorId = $data->getDogovorId();

        $dogovor = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($dogovorId);

        $data->setDogovor($dogovor);
//        $data->setCreateDateTime(new \DateTime(date('Y-m-d H:i:s')));

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return true;
    }

    /**
     * Saves dop dogovor ajax form
     *
     * @param Request $request
     *
     * @return boolean
     */
    public function dogovorUploadAction(Request $request)
    {
        $result = array();
        $dogovorId = $request->query->get('id');

        $em = $this->getDoctrine()->getManager();
        $dogovor = $em
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($dogovorId);

        $result['id'] = $dogovor->getId();
        if (!$dogovor) {
            $result['error'] = 'Dogovor not found';
        }
        $file = $request->files->get('dogovor');

        if ($file) {
            $directory = $this->container->getParameter('project.web.dir'). '/uploads/dogovor/';
            if (!is_dir($directory)) {
                mkdir($directory.'old', 0777);
            }
            if (is_file($directory.$dogovor->getFilepath()) && rename($directory.$dogovor->getFilepath(), $directory.'old/'.$dogovorId.'_'.$dogovor->getFilepath())) {

            } else {
                $result['error'] = 'File move error';
            }
            $dogovor->setFile($file);
            $dogovor->upload();
            $result['file'] = $dogovor->getFilepath();
        } else {
            $result['error'] = 'File not found';
        }

        $em->persist($dogovor);
        $em->flush();

        return new Response(json_encode($result));
    }

    /**
     * Saves dop dogovor ajax form
     *
     * @param Request $request
     *
     * @return boolean
     */
    public function dopDogovorUploadAction(Request $request)
    {
        $result = array();
        $dopDogovorId = $request->query->get('id');

        $em = $this->getDoctrine()->getManager();
        $dopDogovor = $em
            ->getRepository('ListsDogovorBundle:DopDogovor')
            ->find($dopDogovorId);

        if (!$dopDogovor) {
            $result['error'] = 'Dop dogovor not found';
        }
        $file = $request->files->get('dopdogovor');

        if ($file) {
            $directory = $this->container->getParameter('project.web.dir'). '/uploads/dogovor/';
            if (!is_dir($directory)) {
                mkdir($directory.'old', 0777);
            }
            if (is_file($directory.$dopDogovor->getFilepath()) && rename($directory.$dopDogovor->getFilepath(), $directory.'old/'.$dopDogovorId.'_'.$dopDogovor->getFilepath())) {

            } else {
                $result['error'] = 'File move error';
            }
            $dopDogovor->setFile($file);
            $dopDogovor->upload();
            $result['file'] = $dopDogovor->getFilepath();
        } else {
            $result['error'] = 'File not found';
        }

        $em->persist($dopDogovor);
        $em->flush();

        return new Response(json_encode($result));
    }

    /**
     * Process default to Dogovor Department form
     *
     * @param Form    $form
     * @param mixed[] $defaultData
     *
     * @return void
     */
    public function dogovorDepartmentFormProcessDefaults(Form $form, $defaultData)
    {
        $dogovorId = $defaultData['dogovorId'];

        $organizationIds = array();

        $dogovor = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($dogovorId);

        $form
            ->add('dopDogovor', 'entity', array(
                'class' => 'ListsDogovorBundle:DopDogovor',
                'empty_value' => '',
                'required' => false,
                'query_builder' => function (DopDogovorRepository $repository) use ($dogovorId) {
                    return $repository->createQueryBuilder('dd')
                        ->where('dd.dogovorId = :dogovorId')
                        ->setParameter(':dogovorId', $dogovorId);
                }
        ));

        /** @var DepartmentsRepository $dr */
        $dr = $this->get('lists_department.repository');

        $form
            ->add('departments', 'entity', array(
                'class' => 'ListsDepartmentBundle:Departments',
                'empty_value' => '',
                'required' => true,
                'mapped' => false,
                'multiple' => true,
                'query_builder' => $dr->getDepartmentsForDogovor($dogovorId)
        ));
    }

    /**
     * Saves dogovor department ajax form
     *
     * @param Form    $form
     * @param User    $user
     * @param Request $request
     *
     * @return bool
     */
    public function dogovorDepartmentFormSave($form, $user, $request)
    {
        /** @var DogovorDepartment $data */
        $data = $form->getData();

        $data->setUser($this->getUser());

        $requestData = $request->request->get($form->getName());

        $departmentIds = $requestData['departments'];

        $insert = false;

        $dogovorId = $data->getDogovorId();

        $dogovor = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($dogovorId);

        $data->setDogovor($dogovor);

        /** @var DogovorDepartmentRepository $ddr */
        $ddr = $this->get('lists_dogovor.department.repository');

        $dr = $this->get('lists_department.repository');

        $dopDogovorId = $data->getDopDogovor() ? $data->getDopDogovor()->getId() : null;

        $em = $this->getDoctrine()->getManager();

        foreach ($departmentIds as $departmentId) {
            if (!$ddr->isExists($data->getDogovorId(), $dopDogovorId, $departmentId)) {
                $newData = clone $data;

                $department = $dr->find($departmentId);

                $newData->setDepartment($department);

                $em->persist($newData);

                $insert = true;
            }
        }

        if ($insert) {
            $em->flush();
        }

        return true;
    }

    /**
     * ajax php function to get  mpk
     * return json of all mpk that was found
     *
     * @return string
     */
    public function mpkAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsMpkBundle:Mpk');

        $objects= $repository->getSearchQueryMpk($searchText);

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json mpk by id
     *
     * @return string
     */
    public function mpkByIdAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $mpkList = $this->getDoctrine()
            ->getRepository('ListsMpkBundle:Mpk')
            ->findBy(array('id'=>$ids));

        $result = array();

        foreach ($mpkList as $mpk) {
            $result[] = $this->serializeObject($mpk);
        }

        return new Response(json_encode($result));
    }

    /**
     * ajax php function to get department's type
     * return json of all department type that was found
     *
     * @return string
     */
    public function departmentTypeAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentsType');

        $objects= $repository->getSearchQueryType($searchText);

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json type of department by id
     *
     * @return string
     */
    public function departmentTypeByIdAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $departmentTypeList = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentsType')
            ->findBy(array('id'=>$ids));

        $result = array();

        foreach ($departmentTypeList as $departmentType) {
            $result[] = $this->serializeObject($departmentType);
        }

        return new Response(json_encode($result));
    }

    /**
     * ajax php function to get regions
     * return json of all regions that was found
     *
     * @return string
     */

    public function regionAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsRegionBundle:Region');

        $objects= $repository->getSearchQueryRegion($searchText);

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json region name by id
     *
     * @return string
     */
    public function regionByIdAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $regionList = $this->getDoctrine()
            ->getRepository('ListsRegionBundle:Region')
            ->findBy(array('id' => $ids));

        $result = array();

        foreach ($regionList as $region) {
            $result[] = $this->serializeObject($region);
        }

        return new Response(json_encode($result));
    }

    /**
     * ajax php function to get department status
     * return json of all regions that was found
     *
     * @return string
     */
    public function departmentStatusAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentsStatus');

        $objects= $repository->getSearchQueryStatus($searchText);

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json department status by id
     *
     * @return string
     */
    public function departmentStatusByIdAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $departmentStatusList = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentsStatus')
            ->findBy(array('id' => $ids));

        $result = array();

        foreach ($departmentStatusList as $departmentStatus) {
            $result[] = $this->serializeObject($departmentStatus);
        }

        return new Response(json_encode($result));
    }


    /**
     * Returns json company structure by id
     *
     * @return string
     */
    public function companyStructureByIdAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $regionList = $this->getDoctrine()
            ->getRepository('ListsCompanystructureBundle:Companystructure')
            ->findBy(array('id'=>$ids));

        $result = array();

        foreach ($regionList as $region) {
            $result[] = $this->serializeObject($region);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json of searchin self Organizations
     *
     * @return string
     */
    public function selfOrganizationAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $objects= $repository->searchSelfOrganization($searchText);

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json of searchin self Organizations
     *
     * @return string
     */
    public function organizationFirstAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $objects= $repository->searchOrganizationFirst($searchText);

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Function to handle the ajax queries from editable elements
     *
     * @return mixed[]
     */
    public function editableDepartmentAction()
    {

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);


        /** @var Handling $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments')
            ->find($pk);

        if ($name == 'status') {
            if (!$value) {
                $value = null;
            } else {
                $value = $this->getDoctrine()
                    ->getRepository('ListsDepartmentBundle:DepartmentsStatus')
                    ->find($value);
            }
        } elseif ($name == 'opermanager') {
            if (!$value) {
                $value = null;
            } else {
                $value = $this->getDoctrine()
                    ->getRepository('SDUserBundle:User')
                    ->find($value);
            }
        } elseif ($name == 'statusDate') {
            $value = new \DateTime($value);
        } elseif ($name == 'type') {
            if (!$value) {
                $value = null;
            } else {
                $value = $this->getDoctrine()
                    ->getRepository('ListsDepartmentBundle:DepartmentsType')
                    ->find($value);
            }
        }
        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors*/
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors)) {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        $return = array('error'=>false);

        $return['value'] = $value;
        $return['method'] = $methodSet;
        $return['object'] = $object;
        try {
            $em->flush();

            $em->refresh($object);
        } catch (\ErrorException $e) {
            $return['error'] = $e->getMessage();
        }

        return new Response(json_encode($return));

    }

    /**
     * ajax php function to get department people mpk
     * return json of all department people mpks that was found
     *
     * @return string
     */
    public function departmentPeopleMpkAction()
    {

        $id = $this->getSessionValueByKey('idDepartment', null, 'oper.bundle.department', 'param');

        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsMpkBundle:Mpk');

        //var_dump($id);
        $objects= $repository->getDepartmentPeopleQueryMpk($searchText, $id);

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * ajax php function to get department people
     * return json of all department people that was found
     *
     * @return string
     */
    public function departmentPeopleIndividualAction()
    {
        $id = $this->getSessionValueByKey('idDepartment', null, 'oper.bundle.department', 'param');

        $filters = $this->getFilters('oper.paginator.department.coworkers');
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        $objects= $repository->getSearchQueryPeople($searchText, $id, $filters);

        $result = array();

        foreach ($objects as $object) {
            $result[] = $this->serializeObject($object->getIndividual());
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json department individual by id
     *
     * @return string
     */
    public function departmentPeopleIndividualByIdAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $individualList = $this->getDoctrine()
            ->getRepository('ListsIndividualBundle:Individual')
            ->findBy(array('id' => $ids));

        $result = array();

        foreach ($individualList as $individual) {
            $result[] = $this->serializeObject($individual);
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json month from department by id
     *
     * @return string
     */
    public function monthsFromDepartmentAction()
    {
/*        //$idDepartment = $this->getSessionValueByKey('idDepartment', null, 'oper.bundle.department', 'param');

        $repository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');

        //$objects= $repository->getMonthsFromDepartment($idDepartment);
*/
        $result = array();

        $translator = $this->get('translator');
        for ($i=1; $i<=12; $i++) {
            $numberMonth = $i;//$object['month'];

            $text = $this->getMonthTranslation($numberMonth);
            $result[] = array(
                'id' => $numberMonth,
                'value' => $numberMonth,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json months by id
     *
     * @return string
     */
    public function monthsFromDepartmentByIdAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $result = array();
        $translator = $this->get('translator');
        foreach ($ids as $idMonth) {
            $text = $this->getMonthTranslation($idMonth);
            $result = array(
                'id' => $idMonth,
                'value' => $idMonth,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
    }

    /**
     * @param int $numberMonth
     *
     * @return mixed
     */
    private function getMonthTranslation($numberMonth)
    {
        $translator = $this->get('translator');
        switch ($numberMonth) {
            case '1':
                $text = $translator->trans('January', array(), 'ITDoorsOperBundle');
                break;
            case '2':
                $text = $translator->trans('February', array(), 'ITDoorsOperBundle');
                break;
            case '3':
                $text = $translator->trans('March', array(), 'ITDoorsOperBundle');
                break;
            case '4':
                $text = $translator->trans('April', array(), 'ITDoorsOperBundle');
                break;
            case '5':
                $text = $translator->trans('May', array(), 'ITDoorsOperBundle');
                break;
            case '6':
                $text = $translator->trans('June', array(), 'ITDoorsOperBundle');
                break;
            case '7':
                $text = $translator->trans('July', array(), 'ITDoorsOperBundle');
                break;
            case '8':
                $text = $translator->trans('August', array(), 'ITDoorsOperBundle');
                break;
            case '9':
                $text = $translator->trans('September', array(), 'ITDoorsOperBundle');
                break;
            case '10':
                $text = $translator->trans('October', array(), 'ITDoorsOperBundle');
                break;
            case '11':
                $text = $translator->trans('November', array(), 'ITDoorsOperBundle');
                break;
            case '12':
                $text = $translator->trans('December', array(), 'ITDoorsOperBundle');
                break;
        }

        return $text;
    }

    /**
     * Returns json years from department
     *
     * @return string
     */
    public function yearsFromDepartmentAction()
    {
/*        $idDepartment = $this->getSessionValueByKey('idDepartment', null, 'oper.bundle.department', 'param');

        $repository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');

        $objects= $repository->getYearsFromDepartment($idDepartment);*/

        $result = array();
        $currentYear = intval(date('Y'));
        for ($year = 2013; $year<=$currentYear; $year++) {
            //$year = $object['year'];

            $result[] = array(
                'id' => $year,
                'value' => $year,
                'name' => (string) $year,
                'text' => (string) $year
            );
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json years from department by id
     *
     * @return string
     */
    public function yearsFromDepartmentByIdAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));
        foreach ($ids as $idYear) {

            $result = array(
                'id' => $idYear,
                'value' => $idYear,
                'name' => $idYear,
                'text' => $idYear
            );
        }

        return new Response(json_encode($result));
    }

    /**
     * @return Response
     */
    public function departmentIndividualGrafikAction()
    {
        $idDepartment = $this->get('request')->request->get('pk');

        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        $departmentPeople = $repository->findBy(array(
            'department' => $idDepartment,
        ));

        $result = array();
        if (is_array($departmentPeople)) {
            foreach ($departmentPeople as $object) {
                $result[] = $this->serializeObject($object);
            }
        } else {
            $result[] = $this->serializeObject($departmentPeople);
        }

        return new Response(json_encode($result));
    }
}
