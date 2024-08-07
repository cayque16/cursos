<?php

use DateTime;
use Core\Category\Domain\Entities\Category;
use Core\SeedWork\Domain\Exceptions\Exception\EntityValidationException;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use Faker\Factory;

beforeEach(fn () => $this->category = new Category(
    name: 'test',
));

test("constructor of category and getters and setters", function () {
    expect($this->category->name)->not->toBeNull();
    expect($this->category->name)->toBeString();
    expect($this->category->name)->toBe('test');
    expect($this->category->description)->toBeNull();
    expect($this->category->isActive)->toBeTrue();
    expect($this->category->createdAt)->not->toBeNull();
    expect($this->category->createdAt)->toBeInstanceOf(DateTime::class);

    $date = new DateTime();
    $category = new Category(
        name: 'test',
        description: 'desc',
        isActive: false,
        createdAt: $date,
    );
    expect($category->description)->not->toBeNull();
    expect($category->description)->toBe('desc');
    expect($category->isActive)->toBeFalsy();
    expect($category->isActive)->toBeBool();
    expect($category->createdAt)->not->toBeNull();
    expect($category->createdAt)->toBe($date);
    expect($category->createdAt())->toBeString();
    expect($category->createdAt())->not->toBeNull();
});

test('id field', function () {
    expect($this->category->id)->not->toBeString();
    expect($this->category->id)->not->toBeNull();
    expect($this->category->id)->toBeInstanceOf(Uuid::class);
    expect($this->category->id())->toBeString();

    $id = Uuid::random();
    $category = new Category(name: 'test', id: $id);

    expect($category->id)->not->toBeString();
    expect($category->id)->not->toBeNull();
    expect($category->id)->toBeInstanceOf(Uuid::class);
    expect($category->id())->toBeString();
    expect($category->id)->toBe($id);
});

test('should throw exception with name is invalid - min', function () {
    new Category(name: 'sd');
})->throws(EntityValidationException::class);

test('should throw exception with name is invalid - max', function () {
    $name  = Factory::create()->sentence(400);
    new Category(name: $name);
})->throws(EntityValidationException::class);

test('should throw exception with description is invalid - max', function () {
    $description  = Factory::create()->sentence(4000);
    new Category(name: 'valid name', description: $description);
})->throws(EntityValidationException::class);

test('should active a category', function () {
    $category = new Category(name: 'test', isActive: false);
    expect($category->isActive)->toBeFalsy();
    $category->activate();
    expect($category->isActive)->toBeTrue();
});

test('should disable a category', function () {
    $category = new Category(name: 'test', isActive: true);
    expect($category->isActive)->toBeTrue ();
    $category->disable();
    expect($category->isActive)->toBeFalsy();
});

test('should update a category', function () {
    $category = new Category(name: 'test', description: 'desc');

    $category->update(name: 'new name', description: 'new desc');
    expect($category->name)->toBe('new name');
    expect($category->description)->toBe('new desc');

    $category->update(name: 'new name 2');
    expect($category->name)->toBe('new name 2');
    expect($category->description)->toBe('new desc');
});

test('should throw exception if update with invalid value', function () {
    $category = new Category(name: 'test', description: 'desc');

    $category->update(name: 'ne', description: 'new desc');
})->throws(EntityValidationException::class);
