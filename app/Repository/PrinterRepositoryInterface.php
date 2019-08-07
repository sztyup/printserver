<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entities\Printer;
use Doctrine\Common\Persistence\ObjectRepository;

interface PrinterRepositoryInterface extends ObjectRepository
{
    /**
     * @param string $uri
     * @return Printer|null
     */
    public function findOneByCupsURI(string $uri): ?Printer;
}