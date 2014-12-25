<?php

/**
 * Command class for deleting handling
 */

namespace Lists\OrganizationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;
use Lists\HandlingBundle\Entity\HandlingUser;
use Lists\OrganizationBundle\Entity\OrganizationUser;

/**
 * Class DeleteCommand
 */
class ParserBanks extends ContainerAwareCommand
{

    /**
     * @var \Doctrine\ORM\EntityManager $em
     */

    protected $em;

    /**
     * @var Connection $connection
     */
    protected $connection;

    /**
     * {@inheritdoc}
     */
    protected function configure ()
    {
        $this
            ->setName('lists:organization:parser-banks')
            ->setDescription('Parser banks (json to BD)');
    }
    /**
     * {@inheritdoc}
     */
    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $parser = $this->getContainer()->get('lists_organization.service');

        $res = $parser->parserFile();

        $output->writeln($res);
    }
}
