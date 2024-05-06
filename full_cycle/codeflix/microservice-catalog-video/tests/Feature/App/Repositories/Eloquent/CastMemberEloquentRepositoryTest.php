<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
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
}
