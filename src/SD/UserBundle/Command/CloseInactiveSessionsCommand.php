<?php

namespace SD\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use SD\UserBundle\Entity\User as FOSUser;
use SD\UserBundle\Entity\Group as FOSGroup;

/**
 * CloseInactiveSessionsCommand
 */
class CloseInactiveSessionsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('sd:user:close-inactive-sessions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $pdo = $this->getContainer()->get('session.handler.pdo');
        $userActivityRecords = $em->getRepository('SDUserBundle:UserActivityRecord')->findAll();

        foreach ($userActivityRecords as $userActivityRecord) {
            $user = $userActivityRecord->getUser();

            $userLoginRecords = $em->getRepository('SDUserBundle:UserLoginRecord')->findBy(array(
                            'user' => $user,
                            'logedOut' => null
            ));

            $sql = 'SELECT MAX(session_time) FROM session
                    JOIN login_statistic ON session_id = sessionid
                    WHERE user_id =:userId AND logedout IS NULL';

            $statement = $em->getConnection()->prepare($sql);
            $statement->execute(array(
                            ':userId' => $user->getId()
            ));
            $lastActivityTimestamp = $statement->fetch();

            $lastActivity = $lastActivityTimestamp['max'];
            $currentTime = new \DateTime("now");
            $currentTime = $currentTime->getTimestamp();
            $timeout = 20;//minutes

            if (($currentTime - $lastActivity) > $timeout * 60) {
                foreach ($userLoginRecords as $userLoginRecord) {
                    $userTime = new \DateTime();
                    $userTime->setTimestamp($currentTime);
                    $userLoginRecord->setLogedOut($userTime);
                    $em->merge($userLoginRecord);

                    $pdo->destroy($userLoginRecord->getSessionId());
                }
                $em->remove($userActivityRecord);
                $em->flush();
            }
        }
    }
}
