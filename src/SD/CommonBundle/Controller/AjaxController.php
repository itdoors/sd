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
            ->setParameter(':q', $searchText . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object)
        {
            $result[] = $this->serializeObject($object);
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
            $result = $this->serializeObject($organization);
        }

        return new Response(json_encode($result));
    }

    /**
     * Serialize object to json. temporary solution
     *
     * @param object $object
     *
     * @return mixed[]
     */
    public function serializeObject($object)
    {
        return array(
            'id' => $object->getId(),
            'value' => $object->getId(),
            'name' => (string) $object,
            'text' => (string) $object
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

            $this->$method($form);

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

    public function organizationUserFormSave($form)
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
}