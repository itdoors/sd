<?php

namespace ITDoors\HistoryBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use ITDoors\HistoryBundle\Entity\History;

/**
 * HistoryService class
 */
class HistoryService
{

    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
    * add
    * 
    * @param mixes $data
    * 
    * @return boolean
    */
    public function add($data)
    {
        $em= $this->container->get('doctrine')->getManager();
        $db = $em->getConnection();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $query = "
            INSERT INTO
                history
                    (model_name, model_id, field_name, old_value, value, createdatetime, more, action, params, user_id)
                VALUES 
                    (:modelName, :modelId, :fieldName, :oldValue, :value, '".(date('Y-m-d H:i:s'))."', :more, :action, :params, ".$user->getId().")";
        $stmt = $db->prepare($query);
        $params = array(
                ':modelId' => $data['modelId'],
                ':modelName' => $data['modelName'],
                ':action' => $data['action'],
                ':fieldName' => null,
                ':oldValue' => null,
                ':value' => null,
                ':params' => null,
                ':more' => null
            );
        if (key_exists('params', $data)) {
            $params[':params'] = $data['params'];
        }
        if (key_exists('more', $data)) {
            $params[':more'] = $data['more'];
        }
        if (key_exists('fieldName', $data)) {
            $params[':fieldName'] = $data['fieldName'];
        }
        if (key_exists('oldValue', $data)) {
            $params[':oldValue'] = $data['oldValue'];
        }
        if (key_exists('value', $data)) {
            $params[':value'] = $data['value'];
        }
        $stmt->execute($params);
    }
    /**
    * add
    * 
    * @param string  $modelName
    * @param integer $modelId
    * @param string  $filterNamespace
    * 
    * @return boolean
    */
    public function getHistory($modelName, $modelId, $filterNamespace)
    {
        $em = $this->container->get('doctrine')->getManager();
        $historyes = $em->getRepository('ITDoorsHistoryBundle:History')
            ->getHistories($modelName, $modelId, $filterNamespace);

        return $historyes->getResult();
    }
}