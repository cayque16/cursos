<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\BaseEntity;

interface ValidatorInterface
{
    public function validate(BaseEntity $entity): void;
}
