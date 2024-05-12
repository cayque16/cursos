<?php

namespace App\Repositories\Eloquent;

use App\Models\Video as VideoModel;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Video as VideoEntity;
use Core\Domain\Entity\BaseEntity;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;

class VideoEloquentRepository implements VideoRepositoryInterface
{
    public function __construct(
        protected VideoModel $model,
    ) {}

    public function insert(BaseEntity $entity): BaseEntity
    {
        $entityDb = $this->model->create([
            'id' => $entity->id(),
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'rating' => $entity->rating,
            'duration' => $entity->duration,
            'opened' => $entity->opened,
        ]);

        $this->syncRelationships($entityDb, $entity);

        return $this->toBaseEntity($entityDb);
    }
    
    public function findById(string $id): BaseEntity
    {
        if (!$entityDb = $this->model->find($id)) {
            throw new NotFoundException('Video not found');
        }

        return $this->toBaseEntity($entityDb);
    }
    
    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $result = $this->model
            ->where(function ($query) use ($filter){
                if ($filter) {
                    $query->where('title', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('title', $order)
            ->get();

        return $result->toArray();
    }
    
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface
    {
        $result = $this->model
                        ->where(function ($query) use ($filter){
                            if ($filter) {
                                $query->where('title', 'LIKE', "%{$filter}%");
                            }
                        })
                        ->orderBy('title', $order)
                        ->paginate($totalPage, ['*'], 'page', $page);

        return new PaginationPresenter($result);
    }
    
    public function update(BaseEntity $entity): BaseEntity
    {

    }
    
    public function delete(string $id): bool
    {

    }
    
    public function toBaseEntity(object $data): BaseEntity
    {
        $entity = new VideoEntity(
            id: new Uuid($data->id),
            title: $data->title,
            description: $data->description,
            yearLaunched: (int) $data->year_launched,
            rating: $data->rating instanceof Rating ? $data->rating :  Rating::from($data->rating),
            duration: (bool) $data->duration,
            opened: $data->opened,
        );

        foreach ($data->categories as $category) {
            $entity->addCategoryId($category->id);
        }

        foreach ($data->genres as $genre) {
            $entity->addGenreId($genre->id);
        }

        foreach ($data->castMembers as $castMember) {
            $entity->addCastMemberId($castMember->id);
        }

        return $entity;
    }
    
    public function updateMedia(BaseEntity $entity): BaseEntity
    {

    }

    protected function syncRelationships(VideoModel $model, VideoEntity $entity)
    {
        $model->categories()->sync($entity->categoriesId);
        $model->genres()->sync($entity->genresId);
        $model->castMembers()->sync($entity->castMembersId);
    }
}
