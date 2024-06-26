<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Domain\Entity\CastMember as CastMemberEntity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Tests\TestCase;

class CastMemberEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CastMemberEloquentRepository(new CastMemberModel());
    }

    public function testCheckImplementsInterfaceRepository()
    {
        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new CastMemberEntity('teste', CastMemberType::ACTOR);

        $response = $this->repository->insert($entity);

        $this->assertDatabaseHas('cast_members', [
            'id' => $entity->id(),
        ]);
        $this->assertEquals($entity->name, $response->name);
    }

    public function testFindByIdNotFound()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->findById('fake_id');
    }

    public function testFindById()
    {
        $castMember = CastMemberModel::factory()->create();

        $response = $this->repository->findById($castMember->id);

        $this->assertEquals($castMember->id, $response->id);
        $this->assertEquals($castMember->name, $response->name);
    }

    public function testFindAllEmpty()
    {
        $response = $this->repository->findAll();

        $this->assertCount(0, $response);
    }

    public function testFindAll()
    {
        $castMembers = CastMemberModel::factory()->count(50)->create();

        $response = $this->repository->findAll();

        $this->assertCount(count($castMembers), $response);
    }

    public function testPagination()
    {
        CastMemberModel::factory()->count(20)->create();

        $response = $this->repository->paginate();

        $this->assertCount(15, $response->items());
        $this->assertEquals(20, $response->total());
    }

    public function testPaginationPageTwo()
    {
        CastMemberModel::factory()->count(80)->create();

        $response = $this->repository->paginate(totalPage: 10);

        $this->assertCount(10, $response->items());
        $this->assertEquals(80, $response->total());
    }

    public function testUpdateIdNotFound()
    {
        $this->expectException(NotFoundException::class);

        $castMember = new CastMemberEntity('name', CastMemberType::ACTOR);

        $this->repository->update($castMember);
    }

    public function testUpdate()
    {
        $castMember = CastMemberModel::factory()->create();

        $entity = new CastMemberEntity(
            id: new Uuid($castMember->id),
            name: 'new name',
            type: CastMemberType::DIRECTOR,
        );

        $response = $this->repository->update($entity);

        $this->assertNotEquals($castMember->name, $response->name);
        $this->assertEquals('new name', $response->name);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->delete('fake_id');
    }

    public function testDelete()
    {
        $castMember = CastMemberModel::factory()->create();

        $this->repository->delete($castMember->id);

        $this->assertSoftDeleted('cast_members', [
            'id' => $castMember->id,
        ]);
    }
}
