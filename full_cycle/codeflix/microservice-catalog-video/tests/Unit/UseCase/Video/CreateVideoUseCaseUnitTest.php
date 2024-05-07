<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Enum\Rating;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoInputDto;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoOutputDto;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\CreateVideoUseCase;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateVideoUseCaseUnitTest extends TestCase
{
    public function testConstructor()
    {
        new CreateVideoUseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockStorage(),
            eventManager: $this->createMockEventManage()
        );

        $this->assertTrue(true);
    }

    public function testExecuteInputOutput()
    {
        $useCase = new CreateVideoUseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockStorage(),
            eventManager: $this->createMockEventManage()
        );

        $response = $useCase->execute($this->createMockInputDto());

        $this->assertInstanceOf(CreateVideoOutputDto::class, $response);
    }

    private function createMockRepository()
    {
        return Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
    }

    private function createMockTransaction()
    {
        return Mockery::mock(stdClass::class, TransactionInterface::class);
    }

    private function createMockStorage()
    {
        return Mockery::mock(stdClass::class, FileStorageInterface::class);
    }

    private function createMockEventManage()
    {
        return Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
    }

    private function createMockInputDto()
    {
        return Mockery::mock(CreateVideoInputDto::class, [
            'title',
            'description',
            2023,
            90,
            true,
            Rating::RATE14,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
