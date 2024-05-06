<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\BaseEntity;

interface ValidationInterface
{
    public function validate(BaseEntity $entity): void;
}
