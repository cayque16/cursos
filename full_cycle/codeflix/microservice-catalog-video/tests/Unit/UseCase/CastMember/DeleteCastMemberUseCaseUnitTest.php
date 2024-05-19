<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\DeleteCastMemberUseCase;
use Core\UseCase\DTO\CastMember\CastMemberInputDto;
use Core\UseCase\DTO\CastMember\DeleteCastMember\DeleteCastMemberOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class DeleteCastMemberUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {
        $uuid = (string) Uuid::uuid4();

        $mockRepository = Mockery::mock(
            stdClass::class,
            CastMemberRepositoryInterface::class
        );
        $mockRepository->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $mockInputDto = Mockery::mock(CastMemberInputDto::class, [$uuid]);

        $useCase = new DeleteCastMemberUseCase($mockRepository);

        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(DeleteCastMemberOutputDto::class, $response);
    }
}
