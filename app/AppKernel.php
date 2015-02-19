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
            new Lists\DepartmentBundle\ListsDepartmentBundle(),
            new Lists\ReportBundle\ListsReportBundle(),
            new ITDoors\AjaxBundle\ITDoorsAjaxBundle(),
            new ITDoors\CommonBundle\ITDoorsCommonBundle(),
            new ITDoors\OperBundle\ITDoorsOperBundle(),
            new Lists\MpkBundle\ListsMpkBundle(),
            new Lists\IndividualBundle\ListsIndividualBundle(),
            new Lists\GrafikBundle\ListsGrafikBundle(),
            new ITDoors\ControllingBundle\ITDoorsControllingBundle(),
            new ITDoors\EmailBundle\ITDoorsEmailBundle(),
            new TSS\AutomailerBundle\TSSAutomailerBundle(),
            new Lists\CompanystructureBundle\ListsCompanystructureBundle(),
            new Lists\ArticleBundle\ListsArticleBundle(),
            new BCC\CronManagerBundle\BCCCronManagerBundle(),
            new ITDoors\CronBundle\ITDoorsCronBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new ITDoors\HistoryBundle\ITDoorsHistoryBundle(),
            new Lists\DocumentBundle\ListsDocumentBundle(),
            new SD\TaskBundle\SDTaskBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new ITDoors\SipBundle\ITDoorsSipBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new SD\ActivityBundle\SDActivityBundle(),
            new Gregwar\ImageBundle\GregwarImageBundle(),
            new ITDoors\HelperBundle\ITDoorsHelperBundle(),
            new Main\ErrorBundle\MainErrorBundle(),
            new Main\FilterBundle\MainFilterBundle(),
            new ITDoors\GeoBundle\ITDoorsGeoBundle(),
            new ITDoors\PayMasterBundle\ITDoorsPayMasterBundle(),
            new Lists\CoachBundle\ListsCoachBundle(),
            new ITDoors\FileAccessBundle\ITDoorsFileAccessBundle(),
            new ITDoors\CalculateBundle\ITDoorsCalculateBundle(),
            new ITDoors\ApiBundle\ITDoorsApiBundle(),
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new Lists\ProjectBundle\ListsProjectBundle(),
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
