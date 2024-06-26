<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CreateCategory\CreateCategoryInputDto;
use Core\UseCase\DTO\Category\CreateCategory\CreateCategoryOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;
use stdClass;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    private $mockEntity;

    private $mockRepo;

    private $mockInputDto;

    private $spy;

    public function testCreateNewCategory()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $uuid = (string) Uuid::uuid4()->toString();
        $categoryName = 'name cat';

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            $categoryName,
        ]);

        $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            $categoryName,
        ]);

        $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('insert')->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CreateCategoryInputDto::class, [
            $categoryName,
        ]);
        $this->mockRepo->shouldReceive('insert')->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CreateCategoryInputDto::class, [
            $categoryName,
        ]);

        $useCase = new CreateCategoryUseCase($this->mockRepo);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CreateCategoryOutputDto::class, $responseUseCase);
        $this->assertEquals($categoryName, $responseUseCase->name);
        $this->assertEquals('', $responseUseCase->description);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('insert')->andReturn($this->mockEntity);
        $useCase = new CreateCategoryUseCase($this->spy);
        $responseUseCase = $useCase->execute($this->mockInputDto);
        $this->spy->shouldHaveReceived('insert');

        Mockery::close();
    }
}
