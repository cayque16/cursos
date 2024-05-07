<?php

namespace Core\UseCase\Video;

use Core\Domain\Builder\Video\Builder;
use Core\Domain\Builder\Video\CreateVideoBuilder;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoInputDto;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoOutputDto;
use Throwable;

class CreateVideoUseCase extends BaseVideoUseCase
{
    protected function getBuilder(): Builder
    {
        return new CreateVideoBuilder;
    }
    
    public function execute(CreateVideoInputDto $input): CreateVideoOutputDto
    {
        $this->validateAllIds($input);
        $this->builder->createEntity($input);
        try {
            $this->repository->insert($this->builder->getEntity());

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

    private function output(): CreateVideoOutputDto
    {
        $entity = $this->builder->getEntity();

        return new CreateVideoOutputDto(
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
