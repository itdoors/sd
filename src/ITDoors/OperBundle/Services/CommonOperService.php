<?php

namespace ITDoors\OperBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class CommonOperService
 */
class CommonOperService
{

    /** @var Container $container */
    protected $container;

    /** @var  EntityManager $em */
    protected $em;

    /**
     * @param Container $container
     */
    public function __construct (Container $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }
    /**
     * @param string  $date
     * @param integer $idCoworker
     * @param integer $idReplacement
     * @param integer $idDepartment
     *
     * @return array
     */
    public function getSumsCoworker ($date, $idCoworker, $idReplacement, $idDepartment)
    {
        list($year, $month) = explode('-', $date);

        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->container->get('doctrine')
            ->getRepository('ListsGrafikBundle:Grafik');

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->container->get('doctrine')
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->findOneBy(array (
            'departmentPeople' => $idCoworker,
            'year' => $year,
            'month' => $month,
            'replacementType' => 'r',
            'departmentPeopleReplacement' => $idReplacement
        ));

        $idMonthInfo = $monthInfo->getId();


        if (isset($monthInfo)) {
            $salaryOfficially = $monthInfo->getSalaryOfficially();
            if (!$salaryOfficially) {
                $salaryOfficially = 0;
            }

            $realSalary = $monthInfo->getRealSalary();
            if (!$realSalary) {
                $realSalary = 0;
            }
        }

        $officiallyTotal = $grafikRepository->getSumTotalOfficially(
            $year, $month, $idDepartment, $idCoworker, $idReplacement
        );
        $notOfficiallyTotal = $grafikRepository->getSumTotalNotOfficially(
            $year, $month, $idDepartment, $idCoworker, $idReplacement
        );

        $accrual = $this->getTotalOnceOnlyAccruals(
            $month, $year, $idCoworker
        );

        $plannedAccrualRepository = $this->container->get('doctrine')
            ->getRepository('ListsDepartmentBundle:PlannedAccrual');

        $plannedAccrual = $plannedAccrualRepository->findOneBy(
            array (
                'departmentPeople' => $idCoworker,
                'code' => 'UU',
                'isActive' => true
            )
        );

        if ($plannedAccrual) {
            $plannedAccrualValue = $plannedAccrual->getValue();
        } else {
            $plannedAccrualValue = 0;
        }
        $salaryNotOfficially = $monthInfo->getSalaryNotOfficially();
        if (!$salaryNotOfficially) {
            $salaryNotOfficially = $plannedAccrualValue;
            $salaryNotOfficially += $accrual['notOfficially']['plus'];
            $salaryNotOfficially -= $accrual['notOfficially']['minus'];
        }

        $totalSalary = $salaryOfficially + $salaryNotOfficially;
        $accessService = $this->container->get('access.service');

        $canEdit = $accessService->checkIfCanEdit();
        $return = array (
            'sumOfficially' => $officiallyTotal,
            'sumNotOfficially' => $notOfficiallyTotal,
            'plannedAccrual' => $plannedAccrualValue,
            'accrual' => $accrual,
            'salaryOfficially' => $salaryOfficially,
            'idCoworker' => $idCoworker,
            'idReplacement' => $idReplacement,
            'realSalary' => $realSalary,
            'salaryNotOfficially' => $salaryNotOfficially,
            'totalSalary' => $totalSalary,
            'idMonthInfo' => $idMonthInfo,
            'canEdit' => $canEdit
        );

        return $return;
    }
    /**
     * @param integer $month
     * @param integer $year
     * @param integer $idCoworker
     *
     * @return mixed[]
     */
    private function getTotalOnceOnlyAccruals ($month, $year, $idCoworker)
    {

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->container->get('doctrine')
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->findOneBy(array (
            'departmentPeople' => $idCoworker,
            'year' => $year,
            'month' => $month,
            'replacementType' => 'r',
            //'departmentPeopleReplacement' => 0
        ));


        $onceOnlyAccrualRepository = $this->container->get('doctrine')
            ->getRepository('ListsDepartmentBundle:OnceOnlyAccrual');


        $accrual['officially'] = array ();
        $accrual['notOfficially'] = array ();

        $accrual['officially']['plus'] = 0;
        $accrual['notOfficially']['plus'] = 0;

        $accrual['officially']['minus'] = 0;
        $accrual['notOfficially']['minus'] = 0;

        if (!$monthInfo) {

            return $accrual;
        }
        $onceOnlyAccruals = $onceOnlyAccrualRepository->findBy(array (
            'departmentPeopleMonthInfo' => $monthInfo->getId()
        ));

        foreach ($onceOnlyAccruals as $onceOnlyAccrual) {
            if ($onceOnlyAccrual->getCode() == 'uu') {
                $key = 'notOfficially';
            } else {
                $key = 'officially';
            }
            if ($onceOnlyAccrual->getType() == 'fine') {
                $accrual[$key]['minus'] += $onceOnlyAccrual->getValue();
            } else {
                $accrual[$key]['plus'] += $onceOnlyAccrual->getValue();
            }
        }

        return $accrual;
    }
}
