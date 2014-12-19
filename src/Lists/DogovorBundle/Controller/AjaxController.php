<?php

namespace Lists\DogovorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AjaxController
 */
class AjaxController extends Controller
{
    /**
     * Returns json ownership list depending search query
     * 
     * @param Request $request
     * 
     * @return string
     */
    public function dogovorGetFieldAction(Request $request)
    {
        $translator = $this->get('translator');
        $id = $request->query->get('id');
        $field = $request->query->get('field');

        $object = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($id);
        $methodGet = 'get' . ucfirst($field);
        $text = 'Method '.$methodGet.' not found';
        if (method_exists($object, $methodGet)) {
            if ($methodGet == 'getWebPath') {
                $text =
                    '<a target="_blank" href="'.$object->$methodGet().'">'
                    .$translator->trans('See document')
                    .'</a>';
            } else {
                $text = $object->$methodGet();
            }
        }

        return new Response(json_encode($text));
    }
    /**
     * Returns json ownership list depending search query
     * 
     * @return string
     */
    public function delayTypeAction()
    {
        $objects = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:DelayType')
            ->findAll();

        $result = array();

        foreach ($objects as $object) {
            $text = $object->getShortName();
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
    public function dogovorSearchAction(Request $request)
    {
        $searchText = $request->query->get('query');

        $objects = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->getSearchQuery($searchText);

        $result = array();

        foreach ($objects as $object) {
            $text = $object->getNumber();
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
    public function dogovorSearchDependentAction(Request $request)
    {
        $searchText = $request->query->get('query');
        $organizationId = $request->query->get('dependent');

        $objects = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->getSearchQuery($searchText, $organizationId);

        $result = array();

        foreach ($objects as $object) {
            $text = $object->getNumber();
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
     * dogovorByIdAction
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function dogovorByIdAction(Request $request)
    {
        $id = $request->query->get('id');

        /** @var \Lists\DogovorBundle\Entity\DogovorRepository $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')->find($id);

        $result = array();

        if ($object) {
            $result = $this->serializeObject($object);
        }

        return new Response(json_encode($result));
    }
    /**
     * dogovorByIdsAction
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function dogovorByIdsAction(Request $request)
    {
        $ids = explode(',', $request->query->get('id'));

        /** @var \Lists\DogovorBundle\Entity\DogovorRepository $dogovorRepository */
        $dogovorRepository = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor');

        /** @var Dogovors[] $dogovors */
        $dogovors = $dogovorRepository
            ->createQueryBuilder('d')
            ->where('d.id in (:ids)')
            ->setParameter(':ids', $ids)
            ->getQuery()->getResult();

        $result = array();

        foreach ($dogovors as $dogovor) {
            $result[] = $this->serializeObject($dogovor);
        }

        return new Response(json_encode($result));
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
