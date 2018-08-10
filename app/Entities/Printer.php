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
    protected $label;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $cupsUri;

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Printer
     */
    public function setLabel(string $label): Printer
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getCupsUri()
    {
        return $this->cupsUri;
    }

    /**
     * @param string $cupsUri
     * @return Printer
     */
    public function setCupsUri(string $cupsUri): Printer
    {
        $this->cupsUri = $cupsUri;

        return $this;
    }
}
