<?php

namespace ITDoors\CalculateBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class CalculateService
 */
class CalculateService
{

    /** @var Doctrine $container */
    protected $doctrine;

    /**
     * @param Doctrine $container
     */
    public function __construct ($doctrine)
    {
        $this->doctrine = $doctrine;
    }


    public function getData() {
        $repoItem = $this->doctrine->getRepository('ITDoorsCalculateBundle:CalculatorItem');
        $rootLabels = $repoItem->findBy(
            array(
                'parent' => null
            ));

        $jsonTotal = array();

        foreach($rootLabels as $rootLabel) {
            $json = array();
            $json['name'] = $rootLabel->getName();
            $json['type'] = $rootLabel->getType();
            $json['children'] = array();
            $children= $repoItem->findBy(
                array(
                    'parent' => $rootLabel
                ));
            foreach ($children as $child) {
                $childJson = array();
                $childJson['name'] = $child->getName();
                $childJson['id'] = $child->getId();
                $childJson['type'] = $child->getType();

                $prices = array();
                if ($child->getType() == 'range') {
                    $prices[] = array(
                        'from' => 1,
                        'to' => 100,
                        'value' => 18.40,
                        'unit' => 'кв.м.'
                    );
                    $prices[] = array(
                        'from' => 101,
                        'to' => 500,
                        'value' => 3.56,
                        'unit' => 'кв.м.'
                    );
                    $prices[] = array(
                        'from' => 501,
                        'to' => 1000,
                        'value' => 2.73,
                        'unit' => 'кв.м.'
                    );
                    $prices[] = array(
                        'from' => 1001,
                        'to' => 5000,
                        'value' => 1.34,
                        'unit' => 'кв.м.'
                    );
                    $prices[] = array(
                        'from' => 50001,
                        'to' => 10000,
                        'value' => 0.48,
                        'unit' => 'кв.м.'
                    );
                } else {
                    $calculatePrices = $child->getCalculatorPrices();
                    foreach ($calculatePrices as $calculatePrice) {
                        $prices = array(//need to fix this
                            'value' => $calculatePrice->getValue(),
                            'unit' => $calculatePrice->getUnit()
                        );
                    }
                }

                $childJson['prices'] = $prices;

                $json['children'][] = $childJson;
            }
            $jsonTotal[] = $json;
        }

        return $jsonTotal;
    }






}
