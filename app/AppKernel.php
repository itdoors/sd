<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Lists\OrganizationBundle\ListsOrganizationBundle(),
            new Lists\CityBundle\ListsCityBundle(),

            new FOS\UserBundle\FOSUserBundle(),
            new SD\UserBundle\SDUserBundle(),
            new SD\DashboardBundle\SDDashboardBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Lists\RegionBundle\ListsRegionBundle(),
            new Lists\LookupBundle\ListsLookupBundle(),
            new Lists\HandlingBundle\ListsHandlingBundle(),
            // new SD\ModelBundle\SDModelBundle(),
            new SD\CommonBundle\SDCommonBundle(),
            new Lists\TeamBundle\ListsTeamBundle(),
            new Lists\ContactBundle\ListsContactBundle(),
            new SD\CalendarBundle\SDCalendarBundle(),
            new Lists\DogovorBundle\ListsDogovorBundle(),
            new Lists\CompanystructureBundle\ListsCompanystructureBundle(),
            new Lists\DepartmentBundle\ListsDepartmentBundle(),
            new Lists\ReportBundle\ListsReportBundle(),
            new ITDoors\AjaxBundle\ITDoorsAjaxBundle(),
            new ITDoors\CommonBundle\ITDoorsCommonBundle(),
            new ITDoors\ControllingBundle\ITDoorsControllingBundle(),
            new ITDoors\EmailBundle\ITDoorsEmailBundle(),
            new TSS\AutomailerBundle\TSSAutomailerBundle()

        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
