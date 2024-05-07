<?php

namespace Core\Domain\Builder\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;

class BuilderVideo implements Builder
{
    private ?Video $entity = null;

    public function __construct()
    {
        $this->reset();
    }

    private function reset()
    {
        $this->entity = null;
    }

    public function createEntity(object $input): void
    {
        $this->entity = new Video(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: $input->rating,
        );

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

    public function addMediaVideo(string $path, MediaStatus $mediaStatus): void
    {
        $media = new Media(
            filePath: $path,
            mediaStatus: MediaStatus::PROCESSING
        );
        $this->entity->setVideoFile($media);
    }

    public function addTrailer(string $path): void
    {
        $media = new Media(
            filePath: $path,
            mediaStatus: MediaStatus::PROCESSING
        );
        $this->entity->setTrailerFile($media);
    }

    public function addThumb(string $path): void
    {
        $media = new Image(
            path: $path,
        );
        $this->entity->setThumbHalfFile($media);
    }

    public function addThumbHalf(string $path): void
    {
        $media = new Image(
            path: $path,
        );
        $this->entity->setThumbHalfFile($media);
    }

    public function addBanner(string $path): void
    {
        $media = new Image(
            path: $path,
        );
        $this->entity->setBannerFile($media);
    }

    public function getEntity(): Video
    {
        return $this->entity;
    }

}
