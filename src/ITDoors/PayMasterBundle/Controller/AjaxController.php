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
     * Function to handle the ajax queries from editable elements
     *
     * @return mixed[]
     */
    public function editableSaveAction()
    {
        $return = array('error' => false);
        $service = $this->get('it_doors_pay_master.service');
        $access = $service->checkAccess($this->getUser());

        $pk = (int) $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);


        /** @var \ITDoors\PayMasterBundle\Entity\PayMaster $object */
        $object = $this->getDoctrine()
            ->getRepository('ITDoorsPayMasterBundle:PayMaster')
            ->find($pk);

        if ($name == 'status') {
            if ((!$access->canChangeStatus() && $this->getUser() != $object->getCreator() ) || $object->getIsAcceptance() === false || $object->getPaymentDate() !== null ) {
                $return['error'] = 403;
                $return['text'] = 'No access';
                return new Response(json_encode($return));
            }
            if (!$value) {
                $value = null;
            } else {
                $value = $this->getDoctrine()
                    ->getRepository('ITDoorsPayMasterBundle:PayMasterStatus')
                    ->find($value);
            }
        } elseif ($name == 'expectedDate') {
            $value = new \DateTime($value);
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
     * Returns json status list search query
     *
     * @param Request $request
     * 
     * @return string
     */
    public function statusListAction(Request $request)
    {
        $searchText = $request->query->get('query');

        $objects = $this->getDoctrine()
            ->getRepository('ITDoorsPayMasterBundle:PayMasterStatus')
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
     * Returns json ownership list depending search query
     *
     * @param integer $id
     * 
     * @return string
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var \ITDoors\PayMasterBundle\Entity\PayMaster $object */
        $object = $em
            ->getRepository('ITDoorsPayMasterBundle:PayMaster')
            ->find($id);
        $service = $this->get('it_doors_pay_master.service');
        $access = $service->checkAccess($this->getUser());
        $result = array();
        if ($access->canRemove() && $object->getIsAcceptance() === null) {
            $em->remove($object);
            $em->flush();
            $result['access'] = true;
        } else {
            $result['error'] = 'No Access';
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
