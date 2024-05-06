<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Domain\Entity\CastMember as CastMemberEntity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
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
}
