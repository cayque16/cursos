<?php

namespace Tests\Unit\UseCase\CastMember;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\Domain\Entity\CastMember as CastMemberEntity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\ListCastMemberUseCase;
use Core\UseCase\DTO\CastMember\CastMemberInputDto;
use Core\UseCase\DTO\CastMember\CastMemberOutputDto;
use Mockery;
use stdClass;

class ListCastMemberUseCaseUnitTest extends TestCase
{
    public function testList()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $mockEntity = Mockery::mock(CastMemberEntity::class, ['name', CastMemberType::ACTOR]);
        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(
            stdClass::class,
            CastMemberRepositoryInterface::class
        );
        $mockRepository->shouldReceive('findById')
                        ->times(1)
                        ->with($uuid)
                        ->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(CastMemberInputDto::class, [$uuid]);

        $useCase = new ListCastMemberUseCase($mockRepository);
        
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CastMemberOutputDto::class, $response);

        Mockery::close();
    }
}
