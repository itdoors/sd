<?php

/**
 * Command class for parser
 */

namespace ITDoors\CalculateBundle\Command;

use ITDoors\CalculateBundle\Entity\CalculatorItem;
use ITDoors\CalculateBundle\Entity\CalculatorPrice;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ParsePriceCommand
 */
class ParsePriceCommand extends ContainerAwareCommand
{
    /**
     * configure
     */
    protected function configure ()
    {
        $this
            ->setName('itdoors:parce_price')
            ->setDescription('Parcing the prices from xls');
    }
    /**
     * execute
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string
     */
    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $phpExcelObject = $this->getContainer()->get('phpexcel')->createPHPExcelObject(__DIR__.'/../../../../web/calculate.xlsx');

        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getEntityManager();

        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $itemParent = null;
        for ($j=4;$j<=60; $j++) {
            $name = $phpExcelObject->getActiveSheet()->getCell('A' . $j)->getValue();
            if (in_array($j, array(4,11,17,23,28,32,40,45,48,55,57))) {
                $calculatorItem = new CalculatorItem();
                $calculatorItem->setName($name);
                $calculatorItem->setType('label');
                $calculatorItem->setParent(null);
                $em->persist($calculatorItem);
                $em->flush();
                $itemParent = $calculatorItem;
                continue;
            }

            $unit = $phpExcelObject->getActiveSheet()->getCell('B' . $j)->getValue();
            $price = $phpExcelObject->getActiveSheet()->getCell('C' . $j)->getValue();

            $calculatorItem = new CalculatorItem();
            $calculatorItem->setName($name);
            $calculatorItem->setType('linear');
            $calculatorItem->setParent($itemParent);
            $em->persist($calculatorItem);
            $em->flush();
            $em->refresh($calculatorItem);

            $calculatorPrice = new CalculatorPrice();
            $calculatorPrice->setCalculatorItem($calculatorItem);
            $calculatorPrice->setUnit($unit);
            $calculatorPrice->setValue($price);
            $em->persist($calculatorPrice);

            $output->writeln($calculatorItem->getName().' - '.$unit.' - '.$price);

        }

        $em->flush();


    }
}
