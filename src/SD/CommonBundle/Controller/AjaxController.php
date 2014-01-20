<?php

namespace SD\CommonBundle\Controller;

use Lists\HandlingBundle\Entity\HandlingMessage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lists\ContactBundle\Entity\ModelContactRepository;
use Lists\ContactBundle\Entity\ModelContact;
use Lists\HandlingBundle\Entity\HandlingMoreInfo;
use Symfony\Component\Form\Form;
use SD\UserBundle\Entity\User;
use Lists\OrganizationBundle\Entity\Organization;
use Doctrine\DBAL\Connection;

class AjaxController extends Controller
{
    protected $modelRepositoryDependence = array(
        'ModelContact' => 'ListsContactBundle:ModelContact',
        'User' => 'SDUserBundle:User'
    );

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
            $result[] = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

    public function organizationForContactsAction()
    {
        $searchText = $this->get('request')->query->get('q');

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $organizations= $organizationsRepository->getSearchContactsQuery($searchText);

        $result = array();

        foreach ($organizations as $organization)
        {
            $this->processOrganizationForJson($organization);

            $result[] = $this->serializeArray($organization, 'organizationId');
        }

        return new Response(json_encode($result));
    }

    public function organizationForWizardAction()
    {
        $searchText = $this->get('request')->query->get('q');

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $organizations= $organizationsRepository->getSearchContactsQuery($searchText);

        $result = array();

        $result[] = array(
            'id' => '',
            'value' => $searchText,
            'name' => $searchText,
            'text' => $searchText
        );

        foreach ($organizations as $organization)
        {
            $this->processOrganizationForJson($organization);

            $result[] = $this->serializeArray($organization, 'organizationId');
        }

        return new Response(json_encode($result));
    }

    public function organizationForCreationAction()
    {
        $searchText = $this->get('request')->query->get('q');

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $organizations= $organizationsRepository->getSearchContactsQuery($searchText);

        $result = array();

        $result[] = array(
            'id' => $searchText,
            'value' => $searchText,
            'name' => $searchText,
            'text' => $searchText
        );

        foreach ($organizations as $organization)
        {
            $this->processOrganizationForJson($organization);

            $organization['id'] = '';

            $result[] = $this->serializeArray($organization);
        }

        return new Response(json_encode($result));
    }

    public function handlingServiceAction()
    {
        $searchText = $this->get('request')->query->get('term');

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $objects= $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->createQueryBuilder('hs')
            ->where('hs.name like :term')
            ->setParameter(':term', '%' . $searchText . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object)
        {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    public function cityAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $repository = $this->getDoctrine()
            ->getRepository('ListsCityBundle:City');

        $objects= $repository->getSearchQuery($searchText);

        $result = array();

        foreach ($objects as $object)
        {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    public function organizationTypeAction()
    {
        $organizationTypes = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationType')
            ->findAll();

        $result = array();

        foreach ($organizationTypes as $organization)
        {
            $result[] = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

    public function organizationGroupAction()
    {
		$searchText = $this->get('request')->query->get('query');

		$organizationTypes = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:OrganizationGroup')
			->createQueryBuilder('og')
			->where('lower(og.name) LIKE :q')
			->setParameter(':q', '%'. mb_strtolower($searchText, 'UTF-8') . '%')
			->getQuery()
            ->getResult();

        $result = array();

        foreach ($organizationTypes as $organization)
        {
            $result[] = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

    public function handlingStatusAction()
    {
        $objects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingStatus')
            ->createQueryBuilder('s')
            ->orderBy('s.sortorder')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object)
        {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    public function handlingResultAction()
    {
        $objects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingResult')
            ->createQueryBuilder('s')
            ->orderBy('s.sortorder')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object)
        {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    public function handlingTypeAction()
    {
        $objects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingType')
            ->createQueryBuilder('s')
            ->orderBy('s.sortorder')
            ->getQuery()
            ->getResult();

        $result = array();

        $result[] = array(
            'id' => '',
            'value' => '',
            'name' => '',
            'text' => ''
        );

        foreach ($objects as $object)
        {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

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

        foreach ($objects as $object)
        {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    public function scopeAction()
    {
        /** @var \Lists\LookupBundle\Entity\LookupRepository $repository */
        $repository = $this->container->get('lists_lookup.repository');

        $objects = $repository->getOnlyScopeQuery()
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object)
        {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

    public function userAction()
    {
        $searchText = $this->get('request')->query->get('query');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->container->get('sd_user.repository');

        $objects = $repository->getOnlyStaff()
            ->andWhere('lower(u.firstName) LIKE :q OR lower(u.lastName) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object)
        {
            $result[] = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }

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

        foreach ($objects as $object)
        {
            $this->processModelContactForJson($object);

            $result[] = $this->serializeArray($object);
        }

        return new Response(json_encode($result));
    }

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

        foreach ($objects as $object)
        {
            $this->processModelContactForJson($object, array('email'));

            $result[] = $this->serializeArray($object);
        }

        return new Response(json_encode($result));
    }

    /**
     * Processes model contact item form json output
     *
     * @param mixed[] $item
     * @param mixed[] $keys
     *
     * @return mixed[] $item
     */
    public function processModelContactForJson(&$item, $keys = array('phone1', 'phone2'))
    {
        $value = '';

        $item['id'] = '';

        foreach ($keys as $key)
        {
            if ($item[$key])
            {
                $value .= ' '. $item[$key];
            }
        }

        if ($item['ownerFullName'])
        {
            $value .= ' ' . $item['ownerFullName'];
        }

        if ($item['creatorFullName'] && !$item['ownerFullName'])
        {
            $value .= ' ' . $item['creatorFullName'];
        }

        if ($item['organizationName'])
        {
            $value .= ' ' . $item['organizationName'];
        }

        $item['value'] = $value;
    }

    /**
     * Processes model contact item form json output
     *
     * @param mixed[] $item
     *
     * @return mixed[] $item
     */
    public function processOrganizationForJson(&$item)
    {
        $value = '';

        if ($item['organizationName'])
        {
            $value .= $item['organizationName'];
        }

        if ($item['organizationShortName'])
        {
            $value .= ' | ' . $item['organizationShortName'];
        }

        if ($item['fullNames'])
        {
            $value .= ' | ' . $item['fullNames'];
        }

        $item['value'] = $value;

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
            $result = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

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

		if ($object)
		{
			$result = $this->serializeObject($object);
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
     * @param string $id
     * @param string $value
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
            'name' =>(string) $string,
            'text' =>(string) $string
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

        if (!$value)
        {
            $methodGet = 'get' . ucfirst($name);
            $type = gettype($organization->$methodGet());

            if (in_array($type, array('integer')))
            {
                $value = null;
            }
        }

        $organization->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors*/
        $errors = $validator->validate($organization, array('edit'));

        if (sizeof($errors))
        {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($organization);

        try
        {
            $em->flush();
        }
        catch (\ErrorException $e)
        {
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

        foreach ($errors as $error)
        {
            $message = $error->getMessage();
        }

        return $message;
    }

    /**
     * Renders form
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

        if ($model && $modelId)
        {
            if (isset($this->modelRepositoryDependence[$model]))
            {
                $repository = $this->modelRepositoryDependence[$model];

                $object = $this->getDoctrine()->getRepository($repository)
                    ->find($modelId);

				$form = $this->createForm($formName, $object);
            }
        }
		else
		{
			$form = $this->createForm($formName);
		}

        if ($defaultData && !is_array($defaultData))
        {
            $defaultData = json_decode($defaultData, true);
        }

        if (sizeof($defaultData))
        {
            foreach ($defaultData as $key => $default)
            {
                $form->add($key, 'hidden', array(
                    'data' => $default
                ));
            }

            $processMethod = $formName . 'ProcessDefaults';

            if (method_exists($this, $processMethod))
            {
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

        if ($form->isValid())
        {
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

    public function handlingMessageFormSave(\Symfony\Component\Form\Form $form, $user, $request)
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingMessage $data */
        $data = $form->getData();

        $formData = $request->request->get($form->getName());

        $handlingId = $data->getHandlingId();

        /** @var \Lists\HandlingBundle\Entity\Handling $handling */
        $handling = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->find($handlingId);

        $user = $this->getUser();
        $data->setCreatedatetime(new \DateTime());
        $data->setUser($user);
        $data->setHandling($handling);

        $file = $form['file']->getData();

        if ($file)
        {
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

        $handlingMessage = new HandlingMessage();
        $handlingMessage->setCreatedate($nextDatetime);
        $handlingMessage->setCreatedatetime(new \DateTime());
        $handlingMessage->setUser($user);
        $handlingMessage->setHandling($handling);
        $handlingMessage->setType($type);
        $handlingMessage->setIsBusinessTrip(isset($formData['next_is_business_trip']) ? true : false);
        $handlingMessage->setAdditionalType(HandlingMessage::ADDITIONAL_TYPE_FUTURE_MESSAGE);

        $handlingMessage->setDescription($descriptionNext);

        if ((int) $contactNext)
        {
            $contact = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
                ->find((int) $contactNext);

            if ($contact)
            {
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

    public function handlingUserFormSave($form, $user, $request)
    {
        $data = $form->getData();

        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->find($data['handlingId']);

        $user = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')
            ->find($data['user']);

        $object->addUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->flush();

        return true;
    }

    public function deleteAction()
    {
        $params = $this->get('request')->request->get('params');

        $method = $params['model'] . 'Delete';

        $this->$method($params);

        return new Response('');
    }

    public function OrganizationUserDelete($params)
    {
        $organizationId = $params['organizationId'];
        $userId = $params['userId'];

        $organization = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($organizationId);

        $user = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')
            ->find($userId);

        $organization->removeUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($organization);
        $em->flush();
    }

    public function HandlingUserDelete($params)
    {
        $handlingId = $params['handlingId'];
        $userId = $params['userId'];

        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->find($handlingId);

        $user = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')
            ->find($userId);

        $object->removeUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->flush();
    }

    public function modelContactOrganizationFormSave($form, $user, $request)
    {
        $data = $form->getData();

        if (!$data->getId())
        {
            $data->setUser($user);

            $owner = $data->getOwner();

            if (!$owner)
            {
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

		if ($users)
		{
			foreach ($users as $orgUser)
			{
				if ($orgUser->getId() == $user->getId())
				{
					$userExist = true;
				}
			}
		}

		if (!$userExist)
		{
			$organization->addUser($user);
		}

        $em->persist($organization);

        $em->flush();

        return true;
    }

	public function modelContactOrganizationEditFormSave($form, $user, $request)
	{
		$data = $form->getData();

		if (!$data->getId())
		{
			return $this->modelContactOrganizationFormSave($form, $user, $request);
		}

		$em = $this->getDoctrine()->getManager();

		$em->persist($data);

		$em->flush();

		$em->refresh($data);

		return true;
	}

    public function modelContactOrganizationAdminFormSave($form, $user, $request)
    {
        return $this->modelContactOrganizationFormSave($form, $user, $request);
    }

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

    public function ModelContactDelete($params)
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

        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:Handling')
            ->find($pk);

        if (!$value)
        {
            $methodGet = 'get' . ucfirst($name);
            $type = gettype($object->$methodGet());

            if (in_array($type, array('integer')))
            {
                $value = null;
            }
        }

        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors*/
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors))
        {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        try
        {
            $em->flush();
        }
        catch (\ErrorException $e)
        {
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

		try
		{
			$object = $query->getSingleResult();
		} catch (\Doctrine\Orm\NoResultException $e)
		{
			$object = null;
		}

		if ($object)
		{
			$object->setValue($value);
		}
		else
		{
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

		try
		{
			$em->flush();
		}
		catch (\ErrorException $e)
		{
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

        $handlingServices= $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService')
            ->findAll();

        foreach ($handlingServices as $hs)
        {
            $object->removeHandlingService($hs);

            if (in_array($hs->getId(), $value))
            {
                $object->addHandlingService($hs);
            }
        }

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors*/
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors))
        {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        try
        {
            $em->flush();
        }
        catch (\ErrorException $e)
        {
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

        if (!$value)
        {
            $methodGet = 'get' . ucfirst($name);
            $type = gettype($object->$methodGet());

            if (in_array($type, array('integer')))
            {
                $value = null;
            }
        }

        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors*/
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors))
        {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        try
        {
            $em->flush();
        }
        catch (\ErrorException $e)
        {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array('success' => 1);

        return new Response(json_encode($return));
    }

    public function handlingMessageFormProcessDefaults($form, $defaultData)
    {
        $handlingId = $defaultData['handling_id'];

        /** @var \Lists\HandlingBundle\Entity\Handling $handling */
        $handling = $this->getDoctrine()->getRepository('ListsHandlingBundle:Handling')
            ->find($handlingId);

        $creator = $handling->getUser();

        $userIds = array();

        $userIds[$creator->getId()] = $creator->getId();

        $users = $handling->getUsers();

        foreach ($users as $user)
        {
            $userIds[$user->getId()] = $user->getId();
        }

        $organizationId = $handling->getOrganizationId();

        $form
            ->add('contact', 'entity', array(
                'class' => 'ListsContactBundle:ModelContact',
                'empty_value' => '',
                'required' => false,
                'query_builder' => function (ModelContactRepository $repository) use ($organizationId, $userIds)
                    {
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
                'query_builder' => function (ModelContactRepository $repository) use ($organizationId, $userIds)
                    {
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
            'query_builder' => function (\Lists\HandlingBundle\Entity\HandlingStatusRepository $repository)
                {
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

	public function processHandlingMoreInfo($moreInfoObjects)
	{
		$result = array();

		foreach ($moreInfoObjects as $object)
		{
			$typeId = $object->getHandlingMoreInfoTypeId();

			if ($typeId)
			{
				$result[$typeId] = $object;
			}
		}

		return $result;
	}

    /**
     * Saves organizationChildForm
     *
     * processes setting child organization
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

        try
        {
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
            $sql = "UPDATE departments set organization_id = :organizationId where organization_id = :organizationChildId";
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
}