<?php

namespace App\Entities;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Illuminate\Support\Str;

class Entity
{
    /*
     * Properties shared by all Entity
     */

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Carbon $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /*
     * Methods for the shared properties
     */

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        if ($this->updatedAt instanceof \DateTime) {
            $this->updatedAt = Carbon::instance($this->updatedAt);
        }

        return $this->updatedAt;
    }

    /**
     * @param Carbon $updatedAt
     *
     * @return static
     */
    public function setUpdatedAt($updatedAt)
    {
        $value = null;

        if ($updatedAt instanceof Carbon)
            $value = $updatedAt;
        elseif (is_string($updatedAt))
            $value = Carbon::createFromTimeString($updatedAt);

        $this->updatedAt = $value;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        if ($this->createdAt instanceof \DateTime) {
            $this->createdAt = Carbon::instance($this->createdAt);
        }

        return $this->createdAt;
    }

    /**
     * @param Carbon $createdAt
     *
     * @return static
     */
    public function setCreatedAt($createdAt)
    {
        $value = null;

        if ($createdAt instanceof Carbon)
            $value = $createdAt;
        elseif (is_string($createdAt))
            $value = Carbon::createFromTimeString($createdAt);

        $this->createdAt = $value;

        return $this;
    }


    /**
     * @param array|null $attributes
     *
     * @return static
     */
    public static function create(array $attributes = [])
    {
        $entity = new static();

        foreach ($attributes as $field => $value) {
            if (is_null($value)) {
                continue;
            }

            $setter = 'set' . Str::camel($field);

            if (method_exists($entity, $setter)) {
                $entity->{$setter}($value);
            }
        }

        return $entity;
    }

    public function update(array $attributes = null)
    {
        if (is_null($attributes)) {
            $this->setUpdatedAt(Carbon::now());

            return $this;
        }

        foreach ($attributes as $field => $value) {
            if (is_null($value)) {
                continue;
            }

            $setter = 'set' . Str::studly($field);

            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            }
        }

        return $this;
    }
}