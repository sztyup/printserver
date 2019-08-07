<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entities\Printer;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class PrinterRepository extends EntityRepository implements PrinterRepositoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws NonUniqueResultException
     */
    public function findOneByCupsURI(string $uri): ?Printer
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.cupsUri = :uri')
            ->setParameter('uri', $uri)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}