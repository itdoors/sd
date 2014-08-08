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

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $em
     * @param type                                       $manager
     * @param object                                     $class
     */
    public function __construct(ObjectManager $em, $manager = null, $class = null)
    {
        $this->repo = $em->getRepository($class);
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        $this->result = array();
        $this->level = 0;
        $this->addChild();

        return $this->result;
    }

    /**
     * @param string $parent
     */
    protected function addChild($parent = null)
    {
        $level = $this->level;
        $ident = '';
        while ($level--) {
            $ident .= ' ~ ';
        }

        $this->level++;

        $folders = $this->repo->findBy(
            array('parent' => $parent),
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

    /**
     * @param string $identifier
     * @param array  $values
     * 
     * @return object
     */
    public function getEntitiesByIds($identifier, array $values)
    {
        return $this->repo->findBy(
            array($identifier => $values)
        );
    }
}