<?php
namespace ITDoors\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('acme:oauth-server:client:create')
            ->setDescription('Creates a new client')
            ->addOption(
                'redirect-uri',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Sets redirect uri for client. Use this option multiple times to set multiple redirect URIs.',
                null
            )
            ->addOption(
                'grant-type',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Sets allowed grant type for client. Use this option multiple times to set multiple grant types..',
                null
            )
            ->setHelp(
                <<<EOT
                    The <info>%command.name%</info>command creates a new client.

<info>php %command.full_name% [--redirect-uri=...] [--grant-type=...] name</info>

EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris($input->getOption('redirect-uri'));
        $client->setAllowedGrantTypes($input->getOption('grant-type'));
        $clientManager->updateClient($client);
        $output->writeln(
            sprintf(
                'Added a new client with public id <info>%s</info>, secret <info>%s</info>',
                $client->getPublicId(),
                $client->getSecret()
            )
        );
    }
    //2_pfyizsmieas48o4sw0g0kcoks84w0g48sgck8c04gw8gg4gsw,
    //2bskum2f6j6s8o04sk4ksogkwgg0cswscscsso4gc40woksssc
    //{"access_token":"Zjk5ZDRhZWFjMzc3M2QyNThlYmRiNmQzNTcyOGNjOTgzYzJkYzQyMDY5ZDEzYzBmZGJmOTcyOWJiZmM3MDM5ZQ","expires_in":3600,"token_type":"bearer","scope":"user","refresh_token":"ZGMyZjkwNjI4MjQ1NmM2NzFiMWEwNmJlNWU1ZmQ5M2UxYTk5Y2I2YTJlMzk5ZTJjY2EwODM4YzZlNTA4ZjAxNQ"}
}