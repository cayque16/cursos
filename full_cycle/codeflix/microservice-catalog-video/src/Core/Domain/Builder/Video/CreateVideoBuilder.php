<?php

namespace Core\Domain\Builder\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;

class CreateVideoBuilder implements Builder
{
    protected ?Video $entity = null;

    public function __construct()
    {
        $this->reset();
    }

    private function reset()
    {
        $this->entity = null;
    }

    public function createEntity(object $input): Builder
    {
        $this->entity = new Video(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: $input->rating,
        );

        $this->addIds($input);

        return $this;
    }

    protected function addIds(object $input)
    {
        foreach ($input->categories as $categoryId) {
            $this->entity->addCategoryId($categoryId);
        }

        foreach ($input->genres as $genreId) {
            $this->entity->addGenreId($genreId);
        }

        foreach ($input->castMembers as $castMemberId) {
            $this->entity->addCastMemberId($castMemberId);
        }
    }

    public function addMediaVideo(string $path, MediaStatus $mediaStatus, ?string $encodePath = ''): Builder
    {
        $media = new Media(
            filePath: $path,
            mediaStatus: MediaStatus::PROCESSING,
            encodePath: $encodePath,
        );
        $this->entity->setVideoFile($media);

        return $this;
    }

    public function addTrailer(string $path): Builder
    {
        $media = new Media(
            filePath: $path,
            mediaStatus: MediaStatus::PROCESSING
        );
        $this->entity->setTrailerFile($media);

        return $this;
    }

    public function addThumb(string $path): Builder
    {
        $media = new Image(
            path: $path,
        );
        $this->entity->setThumbFile($media);

        return $this;
    }

    public function addThumbHalf(string $path): Builder
    {
        $media = new Image(
            path: $path,
        );
        $this->entity->setThumbHalfFile($media);

        return $this;
    }

    public function addBanner(string $path): Builder
    {
        $media = new Image(
            path: $path,
        );
        $this->entity->setBannerFile($media);

        return $this;
    }

    public function getEntity(): Video
    {
        return $this->entity;
    }

}
