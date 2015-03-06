<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CostNal
 *
 * @ORM\Entity()
 */
class CostNal
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
     * @var CostNalType
     *
     * @ORM\Column(name="type", type="costNalType")
     */
    protected $type;

    /**
     * @var \SD\ServiceDeskBundle\Entity\ClaimFinanceRecord
     *
     * @ORM\ManyToOne(targetEntity="SD\ServiceDeskBundle\Entity\ClaimFinanceRecord", inversedBy="costsN")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="finance_record_id", referencedColumnName="id")
     * })
     */
    protected $financeRecord;

    /**
     * @var double
     *
     * @ORM\Column(name="value", type="float")
     */
    protected $value = 0;

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
     * Set type
     *
     * @param costNalType $type
     *
     * @return CostNal
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return costNalType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value
     *
     * @param float $value
     *
     * @return CostNal
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set financeRecord
     *
     * @param ClaimFinanceRecord $financeRecord
     *
     * @return CostNal
     */
    public function setFinanceRecord(ClaimFinanceRecord $financeRecord = null)
    {
        $this->financeRecord = $financeRecord;

        return $this;
    }

    /**
     * Get financeRecord
     *
     * @return ClaimFinanceRecord
     */
    public function getFinanceRecord()
    {
        return $this->financeRecord;
    }
}

final class CostNalType extends \ITDoors\DBAL\EnumType
{
    const TRANS = 'Транспорт/бензин';
    const STUFF = 'Товар/матеріали';
    const EXTRA_STUFF = 'Додаткові працівники';
    const OTHER = 'Інше';
    const EXTRA_PAY = 'Доплата нашому працівнику';

    protected static $name = 'costNalType';

    protected static $values = array(
        self::TRANS,
        self::STUFF,
        self::EXTRA_STUFF,
        self::OTHER,
        self::EXTRA_PAY
    );
}