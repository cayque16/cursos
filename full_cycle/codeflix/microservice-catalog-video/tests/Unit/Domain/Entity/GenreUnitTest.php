<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class GenreUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            id: new Uuid($uuid),
            name: 'New Genre',
            isActive: true,
            createdAt: new DateTime($date),
        );

        $this->assertEquals($uuid, $genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertEquals(true, $genre->isActive);
        $this->assertEquals($date, $genre->createdAt());
    }

    public function testAttributesCreate()
    {
        $genre = new Genre(
            name: 'New Genre',
        );

        $this->assertNotEmpty($genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertEquals(true, $genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }

    public function testActivate()
    {
        $genre = new Genre(
            name: 'Name',
            isActive: false,
        );

        $this->assertFalse($genre->isActive);
        $genre->activate();
        $this->assertTrue($genre->isActive);
    }

    public function testDisable()
    {
        $genre = new Genre(
            name: 'Name',
        );

        $this->assertTrue($genre->isActive);
        $genre->disable();
        $this->assertFalse($genre->isActive);
    }

    public function testUpdate()
    {
        $genre = new Genre(name: 'name');

        $this->assertEquals('name', $genre->name);

        $genre->update(name: 'name updated');

        $this->assertEquals('name updated', $genre->name);
    }

    public function testEntityException()
    {
        $this->expectException(EntityValidationException::class);

        $genre = new Genre(name: 's');
    }

    public function testEntityUpdateException()
    {
        $this->expectException(EntityValidationException::class);

        $uuid = (string) RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            id: new Uuid($uuid),
            name: 'New Genre',
            isActive: true,
            createdAt: new DateTime($date),
        );

        $genre->update(name: 's');
    }

    public function testAddCategoryToGenre()
    {
        $genre = new Genre(name: 'new genre');

        $this->assertIsArray($genre->categories);
        $this->assertCount(0, $genre->categories);

        $genre->addCategory(categoryId: (string) RamseyUuid::uuid4());
        $genre->addCategory(categoryId: (string) RamseyUuid::uuid4());

        $this->assertCount(2, $genre->categories);
    }

    public function testRemoveCategoryToGenre()
    {
        $categoryId = (string) RamseyUuid::uuid4();
        $categoryId2 = (string) RamseyUuid::uuid4();

        $genre = new Genre(
            name: 'new genre',
            categories: [
                $categoryId,
                $categoryId2,
            ]
        );

        $this->assertCount(2, $genre->categories);

        $genre->removeCategory(categoryId: $categoryId);

        $this->assertCount(1, $genre->categories);
        $this->assertEquals($categoryId2, $genre->categories[1]);
    }
}
