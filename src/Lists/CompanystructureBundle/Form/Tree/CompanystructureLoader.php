<?php
namespace Lists\CompanystructureBundle\Form\Tree;
 
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Lists\CompanystructureBundle\Entity\Companystructure;

/**
 * CompanystructureLoader
 */
class CompanystructureLoader implements EntityLoaderInterface
{
    private $repo;
    protected $result;
    protected $level;
 
    public function __construct(ObjectManager $em, $manager = null, $class = null)
    {
        $this->repo = $em->getRepository($class);
    }
 
    public function getEntities()
    {
        $this->result = array();
        $this->level = 0;
 
        $this->addChild();
        return $this->result;
    }
 
    protected function addChild($parent_folder = null)
    {
        $level = $this->level;
        $ident = '';
        while ($level--) {
            $ident .= ' ~ ';
        }
 
        $this->level++;
 
        $folders = $this->repo->findBy(
            array('parent' => $parent_folder),
            array('name' => 'ASC')
        );
 
        /** @var $folder Folder */
        foreach ($folders as $folder) {
            $folder->setName($ident . $folder->getName());
            $this->result[] = $folder;
            $this->addChild($folder);
        }
 
        $this->level--;
    }
 
    public function getEntitiesByIds($identifier, array $values)
    {
        return $this->repo->findBy(
            array($identifier => $values)
        );
    }
}