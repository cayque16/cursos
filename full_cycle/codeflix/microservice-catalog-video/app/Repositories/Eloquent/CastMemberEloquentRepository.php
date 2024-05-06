<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Entity\CastMember as CastMemberEntity;
use App\Models\CastMember as CastMemberModel;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class CastMemberEloquentRepository implements CastMemberRepositoryInterface
{
    public function __construct(
        protected CastMemberModel $model
    ) { }

    public function insert(CastMemberEntity $castMember): CastMemberEntity
    {
        
    }

    public function findById(string $id): CastMemberEntity
    {

    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {

    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface
    {

    }

    public function update(CastMemberEntity $castMember): CastMemberEntity
    {

    }

    public function delete(string $id): bool
    {

    }

    public function toGenre(object $data): CastMemberEntity
    {

    }
}
