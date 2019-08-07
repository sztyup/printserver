<?php

declare(strict_types=1);

namespace App\Network\Model;

final class SNMPResponse
{
    /** @var int */
    private $type;

    /** @var mixed */
    private $value;

    /**
     * SNMPResponse constructor.
     * @param int $type
     * @param mixed $value
     */
    public function __construct(int $type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}