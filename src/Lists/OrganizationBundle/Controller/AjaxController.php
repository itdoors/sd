<?php

namespace Lists\OrganizationBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lists\OrganizationBundle\Entity\OrganizationUser;
use Lists\OrganizationBundle\Form\OrganizationCreateForm;

/**
 * Class AjaxController
 */
class AjaxController extends BaseController
{
    /**
     * Renders/validates ajax form
     *
     * @param Request $request
     *
     * @return string (json)
     */
    public function createAction(Request $request)
    {
        $result = array(
            'error' => true,
            'html' => '',
            'success' => null,
            'organization' => null
        );
        
        $user = $this->getUser();

        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($user);

        if (!$access->canAddOrganization()) {
            throw new \Exception('No access');
        }

        $form = $this->createForm(new OrganizationCreateForm($this->container));

        $form->handleRequest($request);
        
        if ($form->isValid()) {
            /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
            $organization = $form->getData();

            $user = $this->getUser();

            $organization->setCreator($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($organization);

            $lookup = $this->getDoctrine()->getRepository('ListsLookupBundle:lookup')
                ->findOneBy(array ('lukey' => 'manager_organization'));
            $manager = new OrganizationUser();
            $manager->setOrganization($organization);
            $manager->setUser($user);
            $manager->setRole($lookup);
            $em->persist($manager);
            $em->flush();
            $em->refresh($organization);
            
            $contact = $form->get('contact')->getData();
            $contact->setModelName('organization');
            $contact->setModelId($organization->getId());
            $contact->setUser($user);
            $contact->setOwner($user);
            $em->persist($contact);
            $em->flush();

            $result['success'] = true;
            $result['error'] = false;
            $result['organization'] = array(
                'id' => $organization->getId(),
                'text' => $organization->getEdrpouName()
            );
            
        }
        $result['html'] = $this->renderView('ListsOrganizationBundle:Form:organizationCreateForm.html.twig', array(
            'form' => $form->createView()
        ));

        return new Response(json_encode($result));
    }
    /**
     * Returns json ownership list depending search query
     * 
     * @param Request $request
     * 
     * @return string
     */
    public function bankGetFieldAction(Request $request)
    {
        $id = $request->query->get('id');
        $field = $request->query->get('field');
        $object = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Bank')
            ->find($id);
        $methodGet = 'get' . ucfirst($field);
        $text = 'Method '.$methodGet.' not found';
        if (method_exists($object, $methodGet)) {
            $text = $object->$methodGet();
        }

        return new Response(json_encode($text));
    }
    /**
     * Returns json ownership list depending search query
     *
     * @param Request $request
     * 
     * @return string
     */
    public function bankSearchFieldDependentAction(Request $request)
    {
        $searchText = $request->query->get('query');
        $field = $request->query->get('field');
        $currentAccountId = $request->query->get('dependent');
        if (strpos($currentAccountId, 'isNew_') !== false) {
            $currentAccountId = '';
        }
        $methodGet = 'get' . ucfirst($field);
        $result = array();
        if (!empty($currentAccountId)) {
            $currentAccount = $this->getDoctrine()
                ->getRepository('ListsOrganizationBundle:OrganizationCurrentAccount')
                ->find($currentAccountId);
            $objects = array();
            $objects[] = $currentAccount->getBank();
        } else {
            
            if (!in_array($field, array('mfo', 'name'))) {
                $field = 'name';
            }
            $objects = $this->getDoctrine()
                    ->getRepository('ListsOrganizationBundle:Bank')
                    ->getSearchQuery($searchText, $field);
        }

        foreach ($objects as $object) {
            $text = method_exists($object, $methodGet) ? $object->$methodGet() : (string) $object;
            $id = $object->getId();
            $result[] =  array(
                'id' => $id,
                'value' => $id,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json ownership list depending search query
     *
     * @param Request $request
     * 
     * @return string
     */
    public function organizationCurrentAccountSearchDependentAction(Request $request)
    {
        $searchText = $request->query->get('query');
        $organizationId = $request->query->get('dependent');

        $objects = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationCurrentAccount')
            ->getSearchQuery($searchText, $organizationId);

        $result = array();

        if (!empty($searchText)) {
            $result[] = array(
                'id' => 'isNew_'.$searchText,
                'value' => $searchText,
                'name' => $searchText,
                'text' => $searchText,
                'isNew' => true
            );
        }

        foreach ($objects as $object) {
            $text = $object->getName();
            $id = $object->getId();
            $result[] =  array(
                'id' => $id,
                'value' => $id,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json ownership list depending search query
     *
     * @param Request $request
     * 
     * @return string
     */
    public function organizationCurrentAccountByIdAction(Request $request)
    {
        $id = $request->query->get('id');

        $object = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationCurrentAccount')
            ->find($id);

        $result = array();

        if ($object) {
            $result = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json ownership list depending search query
     *
     * @param Request $request
     * 
     * @return string
     */
    public function bankByIdAction(Request $request)
    {
        $id = $request->query->get('id');

        $object = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Bank')
            ->find($id);

        $result = array();

        if ($object) {
            $result = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json ownership list depending search query
     *
     * @param Request $request
     * 
     * @return string
     */
    public function bankNameAndMfoByIdAction(Request $request)
    {
        $id = $$request->query->get('id');

        $object = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Bank')
            ->find($id);

        $result = array();

        if ($object) {
            $text = $object->getMfo(). ' |'. $object->getName();
            $id = $object->getId();
            $result =  array(
                'id' => $id,
                'value' => $id,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json ownership list depending search query
     *
     * @param Request $request
     * 
     * @return string
     */
    public function bankSearchAction(Request $request)
    {
        $searchText = $request->query->get('query');

        $objects = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Bank')
            ->getSearchNameAndMfoQuery($searchText);

        $result = array();
        foreach ($objects as $object) {
            $text = $object->getMfo(). ' |'. $object->getName();
            $id = $object->getId();
            $result[] =  array(
                'id' => $id,
                'value' => $id,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json ownership list depending search query
     *
     * @param Request $request
     * 
     * @return string
     */
    public function bankByOneAction(Request $request)
    {
        $field = $request->query->get('field');
        $dependent = $request->query->get('dependent');
        $result = array();
        if (strpos($dependent, 'isNew_') !== false) {
            $dependent = '';
        } else {
            $object = $this->getDoctrine()
                ->getRepository('ListsOrganizationBundle:OrganizationCurrentAccount')
                ->find($dependent);
            $bank = $object->getBank();
            $methodGet = 'get' . ucfirst($field);

            if ($bank) {
                $result = $this->serializeObject($bank, null, $methodGet);
            }
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json ownership list depending search query
     *
     * @return string
     */
    public function organizationSearchAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $objects = $repository->getSearchQuery($searchText);

        $result = array();

        foreach ($objects as $object) {
            $text = $object->getEdrpou(). ' | ' .$object->getShortname().' ('.$object->getName(). ')';
            $id = $object->getId();
            $result[] =  array(
                'id' => $id,
                'value' => $id,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json ownership list depending search query
     *
     * @return string
     */
    public function organizationSearchSingAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $objects = $repository->getSearchPayerQuery($searchText);

        $result = array();

        foreach ($objects as $object) {
            $text = $object->getShortname().' ('.$object->getName(). ')';
            $id = $object->getId();
            $result[] =  array(
                'id' => $id,
                'value' => $id,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
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
    public function organizationByIdsAction()
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
    public function kvedByIdsAction()
    {
        $ids = explode(',', $this->get('request')->query->get('ids'));

        /** @var \Lists\OrganizationBundle\Entity\KvedRepository $kvedRepository */
        $kvedRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Kved');

        /** @var Kveds[] $kveds */
        $kveds = $kvedRepository->kvedBuIds($ids);

        $result = array();

        foreach ($kveds as $kved) {
            $text = $kved->getCode().' - '.$kved->getName();
            $id = $kved->getId();
            $result[] =  array(
                'id' => $id,
                'value' => $id,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
    }

    /**
     * Returns json ownership list depending search query
     *
     * @return string
     */
    public function ownershipSearchAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationOwnership');

        $objects = $repository->getSearchQuery($searchText);

        $result = array();

        foreach ($objects as $object) {
            $text = $object->getShortname().' ('.$object->getName(). ')';
            $id = $object->getId();
            $result[] =  array(
                'id' => $id,
                'value' => $id,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json ownership list depending search query
     *
     * @return string
     */
    public function organizationsignsSearchAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationSing');

        $objects = $repository
            ->createQueryBuilder('os')
            ->where('lower(os.name) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()->getResult();

        $result = array();

        foreach ($objects as $object) {
            $text = $object->getName();
            $id = $object->getId();
            $result[] =  array(
                'id' => $id,
                'value' => $id,
                'name' => $text,
                'text' => $text
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * Returns json list kved search query
     *
     * @return string
     */
    public function kvedSearchAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Kved');

        $objects = $repository->searchKved($searchText);
           
        $result = array();

        foreach ($objects as $object) {
            $text = $object->getCode() .' - '.$object->getName();
            $id = $object->getId();
            $result[] =  array(
                'id' => $id,
                'value' => $id,
                'name' => $text,
                'text' => $text
            );
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
     * Saves object to db
     *
     * @return mixed[]
     */
    public function ajaxSaveOrganizationAction()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($pk);

        if ($name == 'ownership') {
            if (!empty($value)) {
                $value = $this->getDoctrine()
                    ->getRepository('ListsOrganizationBundle:OrganizationOwnership')->find($value);
            } else {
                $value = null;
            }
        }
        if ($name == 'city') {
            if (!empty($value)) {
                $value = $this->getDoctrine()
                    ->getRepository('ITDoorsGeoBundle:City')->find($value);
            } else {
                $value = null;
            }
        }
        if ($name == 'organizationsign') {
            $methodSet = 'add' . ucfirst($name);
            $lookups = $organization->getOrganizationsigns();
            foreach ($lookups as $lookup) {
                $organization->removeOrganizationsign($lookup);
            }
            $organizationSigns = $this->getDoctrine()
                ->getRepository('ListsOrganizationBundle:OrganizationSing')->find((int) $value);
            $organization->$methodSet($organizationSigns);
        } else {

            if (!$value) {
                $methodGet = 'get' . ucfirst($name);
                $type = gettype($organization->$methodGet());

                if (in_array($type, array('integer'))) {
                    $value = null;
                }
            }

            $organization->$methodSet($value);
        }
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

        if ($item['creator']) {
            $value .= ' | ' . $item['creator'];
        }

        $item['value'] = $value;
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
}
