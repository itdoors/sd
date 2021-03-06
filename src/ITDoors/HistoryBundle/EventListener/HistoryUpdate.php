<?php

namespace ITDoors\HistoryBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * HistoryUpdate
 */
class HistoryUpdate
{

    protected $container;

    /**
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct (Container $container)
    {
        $this->container = $container;
    }
    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function preUpdate (LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $tableName = $em->getClassMetadata(get_class($entity))->getTableName();
        $tables = $this->container->getParameter('listener.history.table');
        if (in_array($tableName, $tables)) {
            $uow = $em->getUnitOfWork();
            $serviceHistory = $this->container->get('it_doors_history.service');
            $changeset = $uow->getEntityChangeSet($entity);
            $keys = array_keys($changeset);
            $metaData = $em->getClassMetadata(get_class($entity));
            foreach ($keys as $key) {
                $oldValue = $args->getOldValue($key);
                $value = $args->getNewValue($key);

                $fieldName = $key;

                foreach ($metaData->getAssociationNames() as $originalFieldName) {
                    if (!$metaData->isAssociationWithSingleJoinColumn($originalFieldName)) {
                        continue;
                    }
                    $originalColumnName = $metaData->getSingleAssociationJoinColumnName($originalFieldName);

                    if ($originalColumnName == $fieldName) {
                        $repositoryClass = $metaData->getAssociationTargetClass($originalFieldName);
                        if ($value) {
                            $value = (string) $em->getRepository($repositoryClass)->find($value);
                        }
                        if ($oldValue) {
                            $oldValue = (string) $em->getRepository($repositoryClass)->find($oldValue);
                        }
                        break;
                    }
                    //$metaData->getAssociationTargetClass($originalFieldName);
                }

                if (gettype($oldValue) == 'object') {
                    if (get_class($oldValue) == 'DateTime') {
                        $oldValue = $oldValue->format('Y-m-d H:i:s');
                    } else {
                        $oldValue = (string) $oldValue;
                    }
                }
                if (gettype($value) == 'object') {
                    if (get_class($value) == 'DateTime') {
                        $value = $value->format('Y-m-d H:i:s');
                    } else {
                        $value = (string) $value;
                    }
                }
                if (!empty($oldValue) || !empty($value)) {
                    $serviceHistory->add(
                        array (
                            'modelId' => $entity->getId(),
                            'modelName' => $tableName,
                            'fieldName' => $key,
                            'oldValue' => $oldValue,
                            'value' => $value,
                            'action' => 'update'
                        )
                    );
                }
            }
        }
    }
}
