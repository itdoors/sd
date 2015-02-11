<?php

namespace SD\BusinessRoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessRole
 *
 * @ORM\Table(name="sd_business_role")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "businessRole" = "BusinessRole",
 *  "stuff" = "Stuff",
 *  "claimStuff" = "ClaimStuff",
 *  "claimCurator" = "ClaimCurator",
 *  "claimPerformer" = "ClaimPerformer",
 *  "client" = "Client",
 *  "companyClient" = "CompanyClient",
 *  "personClient" = "PersonClient"})
 * @ORM\Entity
 */
class BusinessRole
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Lists\IndividualBundle\Entity\Individual
     *
     * @ORM\ManyToOne(targetEntity="Lists\IndividualBundle\Entity\Individual", fetch="EAGER")
     * @ORM\JoinColumn(name="individual_id", referencedColumnName="id")
     */
    protected $individual;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set individual
     *
     * @param \Lists\IndividualBundle\Entity\Individual $individual
     * 
     * @return BusinessRole
     */
    public function setIndividual(\Lists\IndividualBundle\Entity\Individual $individual = null)
    {
        $this->individual = $individual;

        return $this;
    }

    /**
     * Get individual
     *
     * @return \Lists\IndividualBundle\Entity\Individual 
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    public function __toString() {
        return (string) $this->getIndividual();
    }
}