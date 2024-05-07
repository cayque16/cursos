<?php

namespace Core\Domain\Builder\Video;

use Core\Domain\Entity\Video;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class CreateVideoBuilder extends CreateVideoBuilder
{
    public function createEntity(object $input): Builder
    {
        $this->entity = new Video(
            id: new Uuid($input->id),
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: $input->rating,
            createdAt: new DateTime($input->createdAt)
        );

        $this->addIds($input);

        return $this;
    }
}
