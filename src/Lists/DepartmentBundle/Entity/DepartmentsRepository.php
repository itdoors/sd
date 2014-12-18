<?php

namespace Lists\DepartmentBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Lists\DogovorBundle\Entity\Dogovor;

/**
 * DepartmentsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DepartmentsRepository extends EntityRepository
{
    /**
     * Returns all departments for current dogovor
     *
     * @param int $dogovorId
     *
     * @return Query
     */
    public function getDepartmentsForDogovor($dogovorId)
    {
        /** @var Dogovor $dogovor */
        $dogovor = $this->getEntityManager()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($dogovorId);

        $organizationIds = array(
            $dogovor->getOrganizationId(),
            $dogovor->getCustomerId(),
            $dogovor->getPerformerId()
        );

        $query = $this->createQueryBuilder('d')
            ->where('d.organizationId in (:organiationIds)')
            ->leftJoin('d.city', 'city')
            ->orderBy('city.name', 'ASC')
            ->setParameter(':organiationIds', $organizationIds);

        return $query;
    }
    /**
     * Searches mpk by $q
     *
     * @param string $q
     *
     * @return mixed[]
     */
    public function getSearchQueryMpk($q)
    {
        $sql = $this->createQueryBuilder('c')
            ->where('lower(c.mpk) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%')
            ->getQuery();

        return $sql->getResult();
    }

    /**
     * creates builder query to find all departments
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getAllDepartmentsBuilder()
    {

        $query = $this->createQueryBuilder('d')
            ->select('d.id as id')
            ->addSelect('d.statusDate as statusDate')
            ->addSelect('d.coordinates')
            ->addSelect('d.name')
            ->addSelect('m.name as mpk')
            ->addSelect('m.active as mpkActive')
            ->addSelect('d.address as address')
            ->addSelect('o.name as organizationName')
            ->addSelect('o.rs')
            ->addSelect('o.edrpou')
            ->addSelect('o.inn')
            ->addSelect('o.certificate')
            ->addSelect('o.address as organizationAddress')
            ->addSelect('otype.title as organizationType')
            ->addSelect('r.name as regionName')
            ->addSelect('c.name as cityName')
            ->addSelect('s.name as statusName')
            ->addSelect('t.name as typeName')
            ->addSelect('d.statusDate')
            ->addSelect('d.description')
            ->addSelect("CONCAT(CONCAT(u.lastName, ' '), u.firstName) as opermanagerName")
            ->leftJoin('d.status', 's')
            ->leftJoin('d.organization', 'o')
            ->leftJoin('o.organizationType', 'otype')
            ->leftJoin('d.city', 'c')
            ->leftJoin('c.region', 'r')
            ->leftJoin('d.type', 't')
            ->leftJoin('d.opermanager', 'u')
            ->leftJoin('d.mpks', 'm')
            ->leftJoin('r.companystructure', 'companyStructure');
            //->andWhere('m.active = true');

        return $query;
    }

    /**
     * creates query to find all departments
     *
     * @return Query
     */
    public function getAllDepartmentsQuery()
    {
        $query = $this->getAllDepartmentsBuilder()->getQuery();

        return $query;
    }

    /**
     * creates count query to find the number of all departments
     *
     * @return integer
     */
    public function countAllDepartments()
    {
        $countQuery = $this->countAllDepartmentsBuilder()->getQuery();

        return $countQuery->getSingleScalarResult();
    }

    /**
     * creates QueryBuilder to find the number of all departments
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function countAllDepartmentsBuilder()
    {
        $countQuery = $this->createQueryBuilder('d')
            ->select('COUNT(d.id) as id')
            ->leftJoin('d.status', 's')
            ->leftJoin('d.organization', 'o')
            ->leftJoin('d.city', 'c')
            ->leftJoin('c.region', 'r')
            ->leftJoin('d.type', 't')
            ->leftJoin('d.opermanager', 'u')
            ->leftJoin('d.mpks', 'm')
            ->leftJoin('r.companystructure', 'companyStructure');
            //->andWhere('m.active = true');

        return $countQuery;
    }

    /**
     * Searches departments through filters
     *
     * @param array      $filters
     * @param array|bool $allowedDepartments
     * @param string     $type
     *
     * @return mixed[]
     */
    public function getFilteredDepartments($filters, $allowedDepartments, $type = 'data')
    {
        if ($type == 'data') {
            $sql = $this->getAllDepartmentsBuilder();
        } elseif ($type == 'count') {
            $sql = $this->countAllDepartmentsBuilder();
        }

        if (sizeof($filters)) {

            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'organization':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('o.id in (:idsOrganization)');
                        $sql->setParameter(':idsOrganization', explode(',', $value));
                        break;
                    case 'city':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('c.id in (:cityIds)');
                        $sql->setParameter(':cityIds', explode(',', $value));
                        break;
                    case 'mpk':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('m.id in (:idsMpk)');
                        $sql->setParameter(':idsMpk', explode(',', $value));
                        break;
                    case 'companyStructure':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('companyStructure.id in (:idsCompanyStructure)');
                        $sql->setParameter(':idsCompanyStructure', explode(',', $value));
                        break;
                    case 'region':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('r.id in (:idsRegion)');
                        $sql->setParameter(':idsRegion', explode(',', $value));
                        break;
                    case 'status':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('s.id in (:idsStatus)');
                        $sql->setParameter(':idsStatus', explode(',', $value));
                        break;
                    case 'departmentType':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('t.id in (:idsDepartmentType)');
                        $sql->setParameter(':idsDepartmentType', explode(',', $value));
                        break;
                    case 'address':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('lower(d.address) LIKE :address');
                        $sql->setParameter(':address', '%'.mb_strtolower($value, 'UTF-8').'%');
                        break;
                    case 'opermanager':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('u.id in (:idsUser)');
                        $sql->setParameter(':idsUser', explode(',', $value));
                        break;
                    case 'performer':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('m.organization in (:idsPerformer)');
                        $sql->setParameter(':idsPerformer', explode(',', $value));

                        break;
                }
            }
        }

        if ($allowedDepartments !== false) {
            if (count($allowedDepartments)>0) {
                $sql->andWhere('d.id in (:idsDepartments)');
                $sql->setParameter(':idsDepartments', $allowedDepartments);
            } else {
                $sql->andWhere('d.id < 0');
            }
        }

        return $sql->getQuery();
    }

    /**
     * Searches department by id and returns full info
     *
     * @param int $id
     *
     * @return mixed[]
     */
    public function getDepartmentInfoById($id)
    {
        $sql = $this->getAllDepartmentsBuilder();
        $sql->andWhere('d.id = :id');
        $sql->setParameter(':id', $id);
        $query = $sql->getQuery();

        return $query->getResult();
    }

    /**
     * @param array $allowedDepartments
     *
     * @return array
     */
    public function getDepartmentsFromAccess($allowedDepartments)
    {
        $sql = $this->createQueryBuilder('d');
        $sql->leftJoin('d.organization', 'o');
        $sql->leftJoin('d.status', 's');

        if ($allowedDepartments !== false) {
            if (count($allowedDepartments)>0) {
                $sql->andWhere('d.id in (:idsDepartments)');
                $sql->setParameter(':idsDepartments', $allowedDepartments);
            }
        } else {
            $sql->andWhere('d.id < 0');
        }
        $sql->andWhere('s.slug = :active');
        $sql->setParameter(':active', 'active');

        $sql->orderBy('o.name', 'ASC');

        return $sql->getQuery()->getResult();
    }

    /**
     * getSearchQuery
     * 
     * @param string $q
     * 
     * @return array
     */
    public function getSearchQuery($q)
    {
        $sql = $this->createQueryBuilder('d')
            ->leftJoin('d.status', 's')
            ->where('lower(d.name) LIKE :q')
            ->orWhere('lower(d.address) LIKE :q')

            ->andWhere('s.slug = :active')

            ->setParameter(':active', 'active')
            ->setParameter(':q', '%' . mb_strtolower($q, 'UTF-8') . '%');

        return $sql->getQuery()->getResult();
    }

    /**
     * getDepartmentsForCityQuery
     *
     * @param string  $searchText
     * @param integer $cityId
     *
     * @return array
     */
    public function getDepartmentsForCityQuery($searchText, $cityId)
    {
        $sql = $this->createQueryBuilder('d')
        ->innerJoin('d.city', 'c')
        ->where('lower(d.name) LIKE :q')
        ->orWhere('lower(d.address) LIKE :q')
        ->andWhere('c.id = :cityId')
        ->setParameter(':cityId', $cityId)
        ->setParameter(':q', '%' . mb_strtolower($searchText, 'UTF-8') . '%');

        return $sql->getQuery()->getResult();
    }
}
