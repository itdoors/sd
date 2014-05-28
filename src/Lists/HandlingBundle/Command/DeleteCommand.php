<?php

/**
 * Command class for deleting handling
 */
namespace Lists\HandlingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;

class DeleteCommand extends ContainerAwareCommand
{
    /**
    * @var \Doctrine\ORM\EntityManager $em
    */
    protected $em;

    /**
     * @var Connection $connection
     */
    protected $connection;

    protected function configure()
    {
        $this
          ->setName('lists:handling:delete')
          ->setDescription('Delete handling')
          ->setDefinition(array(
            new InputArgument('handling_ids', InputArgument::REQUIRED, 'Handling ids (coma separator)'),
          ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Symfony\Component\Console\Helper\DialogHelper $dialog*/
        $dialog = $this->getHelperSet()->get('dialog');

        $handlingIdsString = $input->getArgument('handling_ids');

        $handlingIds = explode(',', $handlingIdsString);

        if (!$dialog->askConfirmation($output, sprintf('Are you sure you want to delete handling with ID = %s(yes/no)', $handlingIdsString), false )) {
            return;
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->connection = $this->getContainer()->get('database_connection');

        foreach ($handlingIds as $handlingId) {
            $output->writeln('Deleting handling with ID = ' . $handlingIdsString);
            $this->deleteHandling(intval($handlingId), $output);
        }

        $output->writeln($this->getDescription() . ' END');
    }

   /**
    * physical delete handling
    *
    * @param int $handlingId
    * @param OutputInterface $output
    * @throws \Exception
    */
    public function deleteHandling($handlingId, OutputInterface $output)
    {
        if (!$handlingId) {
            $output->writeln(sprintf('Invalid Handling ID = %d', $handlingId));

            return;
        }

        $handling = $this->em->getRepository('ListsHandlingBundle:Handling')
            ->find($handlingId);

        if (!$handling) {
            $output->writeln(sprintf('Handling with ID = %d does not exist', $handlingId));

            return;
        }

        $this->connection->beginTransaction();

        try {
            // Handling User
            $sql = "DELETE FROM handling_user where handling_id = :handlingId";
            $statement = $this->connection->prepare($sql);
            $statement->execute(array(
              ':handlingId' => $handlingId,
            ));

            // Handling Messages
            $sql = "DELETE FROM handling_message where handling_id = :handlingId";
            $statement = $this->connection->prepare($sql);
            $statement->execute(array(
                ':handlingId' => $handlingId,
            ));

            // Handling Handling Service
            $sql = "DELETE FROM handling_handling_service where handling_id = :handlingId";
            $statement = $this->connection->prepare($sql);
            $statement->execute(array(
                ':handlingId' => $handlingId,
            ));

            // Handling
            $sql = "DELETE FROM handling where id = :handlingId";
            $statement = $this->connection->prepare($sql);
            $statement->execute(array(
                ':handlingId' => $handlingId,
            ));

            $this->connection->commit();

            $output->writeln(sprintf('Done! Handling with ID = %d deleted successfully', $handlingId));

        } catch (\Exception $e) {
            $this->connection->rollback();
            $this->em->close();
            throw $e;
        }
    }

    /**
    * @see Command
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('handling_ids')) {
            $handlingId = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please enter Handling IDS (with coma separator (example 314,5,208)): ',
                function ($handlingId) {
                    if (empty($handlingId)) {
                        throw new \Exception('Handling ID is required ');
                    }

                    return $handlingId;
                }
            );
        $input->setArgument('handling_ids', $handlingId);
        }
    }
}
