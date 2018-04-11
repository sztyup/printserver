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

    public function getId()
    {
        return $this->id;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(Carbon $updatedAt): Entity
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }


    public static function create(?array $attributes)
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

    public function update(array $attributes)
    {
        foreach ($attributes as $field => $value) {
            if (is_null($value)) {
                continue;
            }

            $setter = 'set' . Str::camel($field);

            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            }
        }

        return $this;
    }
}