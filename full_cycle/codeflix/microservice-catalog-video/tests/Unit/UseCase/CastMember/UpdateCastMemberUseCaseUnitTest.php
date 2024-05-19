<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember as CastMemberEntity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\CastMember\UpdateCastMemberUseCase;
use Core\UseCase\DTO\CastMember\UpdateCastMembers\UpdateCastMemberInputDto;
use Core\UseCase\DTO\CastMember\UpdateCastMembers\UpdateCastMemberOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class UpdateCastMemberUseCaseUnitTest extends TestCase
{
    public function testCreate()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $mockEntity = Mockery::mock(CastMemberEntity::class, [
            'name',
            CastMemberType::ACTOR,
            new Uuid($uuid),
        ]);
        $mockEntity->shouldReceive('id');
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('update');

        $mockRepository = Mockery::mock(
            stdClass::class,
            CastMemberRepositoryInterface::class
        );
        $mockRepository->shouldReceive('findById')
            ->times(1)
            ->with($uuid)
            ->andReturn($mockEntity);
        $mockRepository->shouldReceive('update')
            ->once()
            ->andReturn($mockEntity);

        $useCase = new UpdateCastMemberUseCase($mockRepository);

        $mockDto = Mockery::mock(UpdateCastMemberInputDto::class, [
            $uuid, 'new name',
        ]);

        $response = $useCase->execute($mockDto);

        $this->assertInstanceOf(UpdateCastMemberOutputDto::class, $response);

        Mockery::close();
    }
}
