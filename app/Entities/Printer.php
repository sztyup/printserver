<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\Timestampable as TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrinterRepository")
 */
class Printer implements Timestampable
{
    use TimestampableTrait;

    /**
     * @var int id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

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
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getCupsUri(): ?string
    {
        return $this->cupsUri;
    }

    /**
     * @param string $cupsUri
     */
    public function setCupsUri(?string $cupsUri): void
    {
        $this->cupsUri = $cupsUri;
    }
}
