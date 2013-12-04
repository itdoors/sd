<?php

namespace SD\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
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

    public function handlingStatusAction()
    {
        $objects = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingStatus')
            ->findAll();

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
            ->findAll();

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
            ->findAll();

        $result = array();

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
        $formName = $request->request->get('formName');

        $form = $this->createForm($formName);

        $defaultData = $request->request->get('defaultData');
        $postFunction = $request->request->get('postFunction');
        $postTargetId = $request->request->get('postTargetId');
        $targetId = $request->request->get('targetId');

        if (sizeof($defaultData))
        {
            foreach ($defaultData as $key => $default)
            {
                $form->add($key, 'hidden', array(
                    'data' => $default
                ));
            }
        }

        $form->handleRequest($request);

        $result = array(
            'error' => 1,
            'html' => ''
        );

        if ($form->isValid())
        {
            $method = $formName . 'Save';

            $user = $this->getUser();

            $this->$method($form, $user);

            unset($result['error']);

            $result['success'] = true;
            $result['postFunction'] = $postFunction;
            $result['postTargetId'] = $postTargetId;
            $result['targetId'] = $targetId;
        }


        $result['html'] = $this->renderView('SDCommonBundle:AjaxForm:' . $formName . '.html.twig', array(
                'form' => $form->createView(),
                'formName' => $formName,
                'postFunction' => $postFunction,
                'postTargetId' => $postTargetId,
                'targetId' => $targetId
            ));

        return new Response(json_encode($result));
    }

    public function organizationUserFormSave($form, $user)
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

    public function handlingMessageFormSave($form, $user)
    {
        /** @var \Lists\HandlingBundle\Entity\HandlingMessage $data */
        $data = $form->getData();

        $handlingId = $data->getHandlingId();

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
        $em->flush();

        return true;
    }

    public function handlingUserFormSave($form, $user)
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

    public function modelContactOrganizationFormSave($form, $user)
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
        $em->flush();

        return true;
    }

    public function modelContactOrganizationAdminFormSave($form, $user)
    {
        return $this->modelContactOrganizationFormSave($form, $user);
    }

    public function modelContactHandlingFormSave($form, $user)
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
}