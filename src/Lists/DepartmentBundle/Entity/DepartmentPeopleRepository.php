<?php

namespace Lists\DepartmentBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DepartmentPeopleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DepartmentPeopleRepository extends EntityRepository
{

    /**
     * @param integer|array $idDepartment
     *
     * @return array
     */
    public function getOrderedPeopleFromDepartment($idDepartment)
    {
        $result = $this->createQueryBuilder('dp')
            ->select('dp.id as id')
            ->addSelect('i.firstName')
            ->addSelect('i.lastName')
            ->addSelect('i.middleName')
            ->addSelect('dp.dismissalDateNotOfficially')
            ->addSelect('m.name as mpkName')

            ->leftJoin('dp.department', 'd')
            ->leftJoin('dp.individual', 'i')
            ->leftJoin('dp.mpks', 'm');

        if (is_array($idDepartment)) {
            $result = $result->andWhere('d.id IN (:id)')
                ->setParameter(':id', $idDepartment);
        } else {
            $result = $result->andWhere('d.id = :id')
            ->setParameter(':id', $idDepartment);
        }

        $result = $result->orderBy('i.lastName')
            //->orderBy('m.name')

            ->getQuery()
            ->getResult();

        return $result;
    }
    /**
     * creates builder query to find all departmentPeople by id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function departmentPeopleBuilder()
    {
        $query = $this->createQueryBuilder('dp')
            ->select('dp.id as id')
            ->addSelect('dp.isGph as gph')
            ->addSelect('i.firstName')
            ->addSelect('i.lastName')
            ->addSelect('i.middleName')
            ->addSelect('dp.admissionDate')
            ->addSelect('dp.dismissalDate')
            ->addSelect('dp.admissionDateNotOfficially')
            ->addSelect('dp.dismissalDateNotOfficially')
            ->addSelect('dp.drfo')
            ->addSelect('o.name as organizationName')
            ->addSelect('i.tin')
            ->addSelect('i.address')
            ->addSelect('i.phone')
            ->addSelect('i.birthday')
            ->addSelect('i.passport')
            ->addSelect('m.name as mpkName')
            ->addSelect('ompk.name as selfOrganizationName')
            ->addSelect('ompk.shortname as selfOrganizationShortName')
            ->leftJoin('dp.department', 'd')
            ->leftJoin('dp.individual', 'i')
            ->leftJoin('dp.mpks', 'm')
            ->leftJoin('d.organization', 'o')
            ->leftJoin('m.organization', 'ompk');

        return $query;
    }

    /**
     * creates query to find all departmentPeople by id
     *
     * @param integer $idDepartment
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllDepartmentPeopleQueryById($idDepartment)
    {
        $query = $this->departmentPeopleBuilder()
            ->andWhere('d.id = :id')
            ->setParameter(':id', $idDepartment)
            ->orderBy('i.lastName', 'ASC')
            ->getQuery();

        return $query;
    }

    /**
     * creates query to find all Filtered departmentPeople
     *
     * @param integer $idDepartment
     * @param mixed[] $filters
     * @param string  $type
     *
     * @return \Doctrine\ORM\Query
     */
    public function getFilteredDepartmentPeopleQuery($idDepartment, $filters, $type = 'data')
    {
        if ($type == 'data') {
            $sql = $this->departmentPeopleBuilder();
        } elseif ($type == 'count') {
            $sql = $this->countAllDepartmentPeopleBuilder();
        }
            $sql->andWhere('d.id = :id')
                ->setParameter(':id', $idDepartment);
        if (sizeof($filters)) {

            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'mpk':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('m.id in (:idsMpk)');
                        $sql->setParameter(':idsMpk', explode(',', $value));
                        break;
                    case 'coworker':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('i.id in (:idsIndividual)');
                        $sql->setParameter(':idsIndividual', explode(',', $value));
                        break;
                }
            }
        }
        if ($type == 'data') {
            $sql->orderBy('i.lastName', 'ASC');
        }

        return $sql->getQuery();
    }

    /**
     * creates count query to find the number of all departments
     *
     * @param integer $idDepartment
     *
     * @return integer
     */
    public function countAllDepartmentPeopleById($idDepartment)
    {
        $countQuery = $this->countAllDepartmentPeopleBuilder()
            ->andWhere('d.id = :id')
            ->setParameter(':id', $idDepartment)
            ->getQuery();

        return $countQuery->getSingleScalarResult();
    }

    /**
     * creates QueryBuilder to find the number of all departments
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function countAllDepartmentPeopleBuilder()
    {
        $countQuery = $this->createQueryBuilder('dp')
            ->select('COUNT(dp.id) as id')
            ->leftJoin('dp.department', 'd')
            ->leftJoin('dp.individual', 'i')
            ->leftJoin('dp.mpks', 'm');

        return $countQuery;
    }

    /**
     * Trying to get all info of individual by id
     *
     * @param integer $id
     *
     * @return \Doctrine\ORM\Query
     */
    public function getInfoById($id)
    {
        $query = $this->departmentPeopleBuilder()
            ->andWhere('dp.id = :id')
            ->setParameter(':id', $id)
            ->getQuery();

        return $query;
    }

    /**
     * Searches mpk by $q
     *
     * @param string  $q
     * @param integer $idDepartment
     * @param mixed[] $filters
     *
     * @return mixed[]
     */
    public function getSearchQueryPeople($q, $idDepartment = null, $filters = array())
    {
        $sql = $this->createQueryBuilder('dp')
            ->leftJoin('dp.individual', 'i')
            ->leftJoin('dp.department', 'd')
            ->leftJoin('dp.mpks', 'm')
            ->where('lower(i.firstName) LIKE :q')
            ->setParameter(':q', mb_strtolower($q, 'UTF-8') . '%')
            ->orWhere('lower(i.lastName) LIKE :q1')
            ->setParameter(':q1', mb_strtolower($q, 'UTF-8') . '%');

        if ($idDepartment) {
            if (is_array($idDepartment)) {
                $sql->andWhere('d.id IN (:department)')
                    ->setParameter(':department', $idDepartment);
            } else {
                $sql->andWhere('d.id = :department')
                    ->setParameter(':department', $idDepartment);
            }
        }

        if (sizeof($filters)) {

            foreach ($filters as $key => $value) {
                if (!$value) {
                    continue;
                }
                switch ($key) {
                    case 'mpk':
                        if (isset($value[0]) && !$value[0]) {
                            break;
                        }
                        $sql->andWhere('m.id in (:idsMpk)');
                        $sql->setParameter(':idsMpk', explode(',', $value));
                        break;
                }
            }
        }
        $sql->orderBy('i.lastName', 'ASC');

        $sql = $sql->getQuery();

        return $sql->getResult();
    }
}
