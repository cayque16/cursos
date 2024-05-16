<?php 

namespace App\Repositories\Eloquent\Traits;

use App\Enums\ImageTypes;
use App\Enums\MediaTypes;
use Core\Domain\Entity\Video;
use Illuminate\Database\Eloquent\Model;

trait VideoTrait
{
    public function updateMediaVideo(Video $entity, Model $model):void
    {
        if ($mediaFile = $entity->videoFile()) {
            $action = $model->media()->first() ? 'update' : 'create';
            $model->media()->{$action}([
                'file_path' => $mediaFile->filePath,
                'media_status' => (string) $mediaFile->mediaStatus->value,
                'encode_path' => $mediaFile->encodePath,
                'type' => (string) MediaTypes::VIDEO->value
            ]);
        }
    }

    public function updateMediaTrailer(Video $entity, Model $model):void
    {
        if ($trailer = $entity->trailerFile()) {
            $action = $model->trailer()->first() ? 'update' : 'create';
            $model->trailer()->{$action}([
                'file_path' => $trailer->filePath,
                'media_status' => (string) $trailer->mediaStatus->value,
                'encode_path' => $trailer->encodePath,
                'type' => (string) MediaTypes::TRAILER->value
            ]);
        }
    }

    public function updateImageBanner(Video $entity, Model $model):void
    {
        if ($banner = $entity->bannerFile()) {
            $action = $model->banner()->first() ? 'update' : 'create';
            $model->banner()->{$action}([
                'path' => $banner->path,
                'type' => (string) ImageTypes::BANNER->value,
            ]);
        }
    }

    public function updateImageThumb(Video $entity, Model $model):void
    {
        if ($thumb = $entity->thumbFile()) {
            $action = $model->thumb()->first() ? 'update' : 'create';
            $model->thumb()->{$action}([
                'path' => $thumb->path,
                'type' => (string) ImageTypes::THUMB->value,
            ]);
        }
    }

    public function updateImageThumbHalf(Video $entity, Model $model):void
    {
        if ($thumbHalf = $entity->thumbHalfFile()) {
            $action = $model->thumbHalf()->first() ? 'update' : 'create';
            $model->thumbHalf()->{$action}([
                'path' => $thumbHalf->path,
                'type' => (string) ImageTypes::THUMB_HALF->value,
            ]);
        }
    }
}
