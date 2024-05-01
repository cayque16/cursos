<?php

namespace Tests\Unit\UseCase\Category;

use CategoryRepositoryInterface;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListCategoryUseCaseUnitTest extends TestCase
{
    private $mockEntity;
    private $mockRepo;
    private $mockInputDto;
    private $spy;

    public function testGetById()
    {
        $id = (string) Uuid::uuid4()->toString();

        $this->mockEntity = Mockery::mock(Category::class, [
            $id,
            'teste'
        ]);

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepo->shouldReceive('findById')
                        ->with($id)
                        ->andReturn($this->mockEntity);
        
        $this->mockInputDto = Mockery::mock(CategoryInputDto::class, [
            $id
        ]);

        $useCase = new ListCategoryUseCase($this->mockRepo);
        $response = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryOutputDto::class, $response);
        $this->assertEquals('teste', $response->name);
        $this->assertEquals($id, $response->id);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')
                        ->with($id)
                        ->andReturn($this->mockEntity);
        $useCase = new ListCategoryUseCase($this->spy);
        $response = $useCase->execute($this->mockInputDto);
        $this->spy->shouldHaveReceived('findById');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
