<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Genre
{
    use MethodsMagicsTrait;

    public function __construct(
        protected string $name,
        protected ?Uuid $id = null,
        protected bool $isActive = true,
        protected array $categories = [],
        protected ?DateTime $createdAt = null,
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function disable(): void
    {
        $this->isActive = false;
    }

    public function update(string $name)
    {
        $this->name = $name;

        $this->validate();
    }

    public function addCategory(string $categoryId)
    {
        if (!in_array($categoryId, $this->categories)) { 
            array_push($this->categories, $categoryId);
        }
    }

    public function removeCategory(string $categoryId)
    {
        unset($this->categories[array_search($categoryId, $this->categories)]);
    }

    private function validate()
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name);
    }
}
