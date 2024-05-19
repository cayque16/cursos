<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\BaseEntity;
use Illuminate\Support\Facades\Validator;

class VideoLaravelValidator implements ValidatorInterface
{
    public function validate(BaseEntity $entity): void
    {
        $data = $this->convertEntityForArray($entity);

        $validator = Validator::make($data, [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'yearLaunched' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $error) {
                $entity->notification->addError([
                    'context' => 'video',
                    'message' => $error[0],
                ]);
            }
        }
    }

    private function convertEntityForArray(BaseEntity $entity): array
    {
        return [
            'title' => $entity->title,
            'description' => $entity->description,
            'yearLaunched' => $entity->yearLaunched,
            'duration' => $entity->duration,
        ];
    }
}
