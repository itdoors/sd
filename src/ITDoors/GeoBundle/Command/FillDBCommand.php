<?php
namespace ITDoors\GeoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ITDoors\GeoBundle\Entity\City;
use ITDoors\GeoBundle\Entity\Region;
use ITDoors\GeoBundle\Entity\District;

/**
 * FillDBCommand
 */
class FillDBCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('db:fill')
            ->setDescription('Fill regions data');
    }

    private function startsWithUpper($str)
    {
        $chr = mb_substr($str, 0, 1, "UTF-8");

        return mb_strtolower($chr, "UTF-8") != $chr;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '512M');

        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $connection = $em->getConnection();

        $statement = $connection->prepare("SELECT DISTINCT obl FROM my_geo ORDER BY obl");
        $statement->execute();
        $regions = $statement->fetchAll();

        foreach ($regions as $region) {
            if ($this->startsWithUpper($region['obl'])) {
                $reg = new Region($region['obl']);
                $em->persist($reg);
            }
        }
        $em->flush();

        $statement = $connection->prepare("SELECT DISTINCT reg,obl FROM my_geo ORDER BY reg");
        $statement->execute();
        $districts = $statement->fetchAll();

        foreach ($districts as $district) {
            if ($this->startsWithUpper($district['reg'])) {
                $dis = new District($district['reg']);
                $reg = $em->getRepository('ITDoorsGeoBundle:Region')->findOneBy(array('name' => $district['obl']));
                $dis->setRegion($reg);
                $em->persist($dis);
            }
        }
        $em->flush();

        $statement = $connection->prepare("SELECT DISTINCT city,reg,obl,long,lat FROM my_geo ORDER BY city");
        $statement->execute();
        $cities = $statement->fetchAll();

        $flushCounter = 0;
        foreach ($cities as $city) {
            $ct = new City($city['city']);
            $reg = $em->getRepository('ITDoorsGeoBundle:Region')->findOneBy(array('name' => $city['obl']));
            $dis = $em->getRepository('ITDoorsGeoBundle:District')->findOneBy(array('name' => $city['reg']));
            $ct->setDistrict($dis)->setRegion($reg)->setLat($city['lat'])->setLong($city['long']);
            $em->persist($ct);
            $flushCounter++;
            if ($flushCounter > 1000) {
                $em->flush();
            }
        }
        $em->flush();
    }
}
