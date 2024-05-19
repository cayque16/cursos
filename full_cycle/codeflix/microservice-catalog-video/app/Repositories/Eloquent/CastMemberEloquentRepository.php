<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\CastMember as CastMemberEntity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;

class CastMemberEloquentRepository implements CastMemberRepositoryInterface
{
    public function __construct(
        protected CastMemberModel $model
    ) {
    }

    public function insert(CastMemberEntity $castMember): CastMemberEntity
    {
        $dataBd = $this->model->create([
            'id' => $castMember->id(),
            'name' => $castMember->name,
            'type' => $castMember->type->value,
            'created_at' => $castMember->createdAt(),
        ]);

        return $this->toCastMember($dataBd);
    }

    public function findById(string $id): CastMemberEntity
    {
        if (! $dataDb = $this->model->find($id)) {
            throw new NotFoundException("Cast Member {$id} Not Found");
        }

        return $this->toCastMember($dataDb);
    }

    public function getIdsListIds(array $castMembersId = []): array
    {
        return $this->model
            ->whereIn('id', $castMembersId)
            ->pluck('id')
            ->toArray();
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $dataBd = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('name', $order)
            ->get();

        return $dataBd->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query = $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query = $query->orderBy('name', $order);

        $dataBd = $query->paginate($totalPage);

        return new PaginationPresenter($dataBd);
    }

    public function update(CastMemberEntity $castMember): CastMemberEntity
    {
        if (! $dataDb = $this->model->find($castMember->id())) {
            throw new NotFoundException("Cast Member {$castMember->id()} Not Found");
        }

        $dataDb->update([
            'name' => $castMember->name,
            'type' => $castMember->type->value,
        ]);

        $dataDb->refresh();

        return $this->toCastMember($dataDb);
    }

    public function delete(string $id): bool
    {
        if (! $dataDb = $this->model->find($id)) {
            throw new NotFoundException("Cast Member {$id} Not Found");
        }

        return $dataDb->delete();
    }

    public function toCastMember(object $data): CastMemberEntity
    {
        return new CastMemberEntity(
            id: new Uuid($data->id),
            name: $data->name,
            type: CastMemberType::from($data->type),
            createdAt: $data->created_at
        );
    }
}
