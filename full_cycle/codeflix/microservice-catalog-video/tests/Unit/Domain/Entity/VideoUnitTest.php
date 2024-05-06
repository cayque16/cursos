<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Genre;
use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use PhpParser\Node\Expr\New_;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class VideoUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $entity = new Video(
            id: new Uuid($uuid),
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            createdAt: new DateTime(date('Y-m-d H:i:s'))
        );

        $this->assertEquals($uuid, $entity->id());
        $this->assertEquals('new title', $entity->title);
        $this->assertEquals('description', $entity->description);
        $this->assertEquals(2029, $entity->yearLaunched);
        $this->assertEquals(12, $entity->duration);
        $this->assertEquals(true, $entity->opened);
        $this->assertEquals(true, $entity->published);
    }

    public function testIdAndCreatedAt()
    {
        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertNotEmpty($entity->id());
        $this->assertNotEmpty($entity->createdAt());
    }

    public function testAddCategoryId()
    {
        $categoryId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertCount(0, $entity->categoriesId);

        $entity->addCategoryId($categoryId);
        $entity->addCategoryId($categoryId);

        $this->assertCount(2, $entity->categoriesId);
    }

    public function testRemoveCategoryId()
    {
        $categoryId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $entity->addCategoryId($categoryId);
        $entity->addCategoryId('uuid');

        $this->assertCount(2, $entity->categoriesId);

        $entity->removeCategoryId($categoryId);

        $this->assertCount(1, $entity->categoriesId);
    }

    public function testAddGenreId()
    {
        $genreId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertCount(0, $entity->genresId);

        $entity->addGenreId($genreId);
        $entity->addGenreId($genreId);

        $this->assertCount(2, $entity->genresId);
    }

    public function testRemoveGenreId()
    {
        $genreId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $entity->addGenreId($genreId);
        $entity->addGenreId('uuid');

        $this->assertCount(2, $entity->genresId);

        $entity->removeGenreId($genreId);

        $this->assertCount(1, $entity->genresId);
    }

    public function testAddCastMemberId()
    {
        $castMemberId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertCount(0, $entity->castMembersId);

        $entity->addCastMemberId($castMemberId);
        $entity->addCastMemberId($castMemberId);

        $this->assertCount(2, $entity->castMembersId);
    }

    public function testRemoveCastMemberId()
    {
        $castMemberId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $entity->addCastMemberId($castMemberId);
        $entity->addCastMemberId('uuid');

        $this->assertCount(2, $entity->castMembersId);

        $entity->removeCastMemberId($castMemberId);

        $this->assertCount(1, $entity->castMembersId);
    }

    public function testValueObjectImage()
    {
        $entity = new Video(
            title: 'new title',
            description: 'description',
            yearLaunched: 2029,
            duration: 12,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            thumbFile: new Image(
                path: 'test/image-movie.png'
            )
        );

        $this->assertNotNull($entity->thumbFile());
        $this->assertInstanceOf(Image::class, $entity->thumbFile());
        $this->assertEquals('test/image-movie.png', $entity->thumbFile()->path());
    }
}
