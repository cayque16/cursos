<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\BaseEntity;

class VideoLaravelValidation implements ValidationInterface
{
    public function validate(BaseEntity $entity): void
    {
        $entity->notification->addErrors();
    }
}
