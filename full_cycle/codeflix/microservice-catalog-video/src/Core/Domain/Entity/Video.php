<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Video extends BaseEntity
{
    protected array $categoriesId = [];
    protected array $genresId = [];
    protected array $castMembersId = [];

    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected bool $published = false,
        protected ?Uuid $id = null,
        protected ?Image $thumbFile = null,
        protected ?Image $thumbHalfFile = null,
        protected ?Image $bannerFile = null,
        protected ?Media $trailerFile = null,
        protected ?Media $videoFile = null,
        protected ?DateTime $createdAt = null,
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validation();
    }

    public function addCategoryId(String $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategoryId(String $categoryId)
    {
        unset($this->categoriesId[array_search($categoryId, $this->categoriesId)]);
    }

    public function addGenreId(String $genreId)
    {
        array_push($this->genresId, $genreId);
    }

    public function removeGenreId(String $genreId)
    {
        unset($this->genresId[array_search($genreId, $this->genresId)]);
    }

    public function addCastMemberId(String $castMemberId)
    {
        array_push($this->castMembersId, $castMemberId);
    }

    public function removeCastMemberId(String $castMemberId)
    {
        unset($this->castMembersId[array_search($castMemberId, $this->castMembersId)]);
    }

    public function thumbFile(): ?Image
    {
        return $this->thumbFile;
    }

    public function thumbHalfFile(): ?Image
    {
        return $this->thumbHalfFile;
    }

    public function bannerFile(): ?Image
    {
        return $this->bannerFile;
    }

    public function trailerFile(): ?Media
    {
        return $this->trailerFile;
    }

    public function videoFile(): ?Media
    {
        return $this->videoFile;
    }

    protected function validation()
    {
        if (empty($this->title)) {
            $this->notification->addError([
                'context' => 'video',
                'message' => 'Should not be empty or null',
            ]);
        }

        if (strlen($this->title) < 3) {
            $this->notification->addError([
                'context' => 'video',
                'message' => 'invalid qtd',
            ]);
        }

        if (strlen($this->description) < 3) {
            $this->notification->addError([
                'context' => 'video',
                'message' => 'invalid qtd',
            ]);
        }

        if ($this->notification->hasErrors()) {
            throw new EntityValidationException(
                $this->notification->messages()
            );
        }
    }
}
