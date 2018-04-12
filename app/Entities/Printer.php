<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Printer extends Entity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $sn;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @return string
     */
    public function getSn(): string
    {
        return $this->sn;
    }

    /**
     * @param string $sn
     * @return Printer
     */
    public function setSn(string $sn): Printer
    {
        $this->sn = $sn;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Printer
     */
    public function setName(string $name): Printer
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Printer
     */
    public function setType(string $type): Printer
    {
        $this->type = $type;

        return $this;
    }
}
