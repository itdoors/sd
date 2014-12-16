<?php

namespace ITDoors\PayMasterBundle\Controller;

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
    public function searchMpkNewAction(Request $request)
    {
        $searchText = $request->query->get('query');

        /** @var \ITDoors\PayMasterBundle\Entity\PayMasterMPKRepository $objects */
        $objects = $this->getDoctrine()
            ->getRepository('ITDoorsPayMasterBundle:PayMasterMPK')
            ->getSearchQuery($searchText);

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
     * dogovorByIdsAction
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function mpkByIdsAction(Request $request)
    {
        $ids = explode(',', $request->query->get('id'));

        /** @var \ITDoors\PayMasterBundle\Entity\PayMasterMPKRepository $mpkRepository */
        $mpkRepository = $this->getDoctrine()
            ->getRepository('ITDoorsPayMasterBundle:PayMasterMPK');

        /** @var PayMasterMPK[] $mpks */
        $mpks = $mpkRepository
            ->createQueryBuilder('m')
            ->where('m.id in (:ids)')
            ->setParameter(':ids', $ids)
            ->getQuery()->getResult();

        $result = array();

        foreach ($mpks as $mpk) {
            $result[] = $this->serializeObject($mpk);
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
