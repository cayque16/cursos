<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember as CastMemberEntity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Core\UseCase\DTO\CastMember\CreateCastMember\CreateCastMemberInputDto;
use Core\UseCase\DTO\CastMember\CreateCastMember\CreateCastMemberOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateCastMemberUseCaseUnitTest extends TestCase
{
    public function testCreate()
    {
        $mockEntity = Mockery::mock(CastMemberEntity::class, ['name', CastMemberType::ACTOR]);
        $mockEntity->shouldReceive('id');
        $mockEntity->shouldReceive('ceratedAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(
            stdClass::class,
            CastMemberRepositoryInterface::class
        );
        $mockRepository->shouldReceive('insert')
                        ->once()
                        ->andReturn($mockEntity);

        $useCase = new CreateCastMemberUseCase($mockRepository);

        $mockDto = Mockery::mock(CreateCastMemberInputDto::class, [
            'name', 1
        ]);

        $response = $useCase->execute($mockDto);

        $this->assertInstanceOf(CreateCastMemberOutputDto::class, $response);
        $this->assertNotEmpty($response->id);
        $this->assertEquals('name', $response->name);
        $this->assertEquals(1, $response->type);
        $this->assertNotEmpty($response->created_at);

        Mockery::close();
    }
}
