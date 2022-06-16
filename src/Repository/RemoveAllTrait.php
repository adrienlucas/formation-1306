<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

trait RemoveAllTrait
{
    public function removeAll(): void
    {
        if(!$this instanceof EntityRepository) {
            throw new \LogicException('You should not use this trait outside of an EntityRepository.');
        }

        $query = $this->getEntityManager()->createQuery(
            sprintf('DELETE FROM %s', $this->getClassName())
        );
        $query->execute();
    }
}