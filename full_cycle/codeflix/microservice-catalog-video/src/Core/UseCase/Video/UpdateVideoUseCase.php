<?php

namespace Core\UseCase\Video;

use Core\Domain\Builder\Video\Builder;
use Core\Domain\Builder\Video\UpdateVideoBuilder;
use Core\UseCase\DTO\Video\UpdateVideo\UpdateVideoInputDto;
use Core\UseCase\DTO\Video\UpdateVideo\UpdateVideoOutputDto;
use Throwable;

class UpdateVideoUseCase extends BaseVideoUseCase
{
    protected function getBuilder(): Builder
    {
        return new UpdateVideoBuilder;
    }

    public function execute(UpdateVideoInputDto $input): UpdateVideoOutputDto
    {
        $this->validateAllIds($input);

        $entity = $this->repository->findById($input->id);
        $entity->update(
            title: $input->title,
            description: $input->description,
        );

        $this->builder
            ->setEntity($entity)
            ->addIds($input);
        
        try {
            $this->repository->update($this->builder->getEntity());

            $this->storageFiles($input);
            $this->repository->updateMedia($this->builder->getEntity());

            $this->transaction->commit();

            return $this->output();
        } catch (Throwable $th) {
            $this->transaction->rollback();

            // if (isset($pathMedia)) $this->storage->delete($pathMedia);

            throw $th;
        }
    }

    private function output(): UpdateVideoOutputDto
    {
        $entity = $this->builder->getEntity();

        return new UpdateVideoOutputDto(
            $entity->id(),
            $entity->title,
            $entity->description,
            $entity->yearLaunched,
            $entity->duration,
            $entity->opened,
            $entity->rating,
            $entity->categoriesId,
            $entity->genresId,
            $entity->castMembersId,
            $entity->videoFile()?->filePath,
            $entity->thumbFile()?->path,
            $entity->thumbHalfFile()?->path,
            $entity->bannerFile()?->path,
            $entity->trailerFile()?->filePath,
        );
    }
}
