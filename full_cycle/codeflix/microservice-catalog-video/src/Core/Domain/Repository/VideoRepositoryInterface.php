<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\BaseEntity;

interface VideoRepositoryInterface extends BaseEntityRepositoryInterface
{
    public function updateMedia(BaseEntity $entity): BaseEntity;
}
