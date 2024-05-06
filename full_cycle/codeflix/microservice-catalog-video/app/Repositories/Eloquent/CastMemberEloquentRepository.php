<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Entity\CastMember as CastMemberEntity;
use App\Models\CastMember as CastMemberModel;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Enum\CastMemberType;
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
        $dataBd = $this->model->create([
            'id' => $castMember->id(),
            'name' => $castMember->name,
            'type' => $castMember->type->value,
            'created_at' => $castMember->createdAt()
        ]);

        return $this->toCastMember($dataBd);
    }

    public function findById(string $id): CastMemberEntity
    {
        if (!$dataDb = $this->model->find($id)) {
            throw new NotFoundException("Cast Member {$id} Not Found");
        }

        return $this->toCastMember($dataDb);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $dataBd = $this->model
                        ->where(function ($query) use ($filter){
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

    }

    public function update(CastMemberEntity $castMember): CastMemberEntity
    {

    }

    public function delete(string $id): bool
    {

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
