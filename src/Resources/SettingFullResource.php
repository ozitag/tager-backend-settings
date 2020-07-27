<?php

namespace OZiTAG\Tager\Backend\Settings\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Ozerich\FileStorage\Models\File;
use Ozerich\FileStorage\Repositories\FileRepository;
use OZiTAG\Tager\Backend\Utils\Enums\FieldType;

class SettingFullResource extends JsonResource
{
    private function prepareValue()
    {
        if (($this->type != FieldType::Image && $this->type != FieldType::File && $this->type != FieldType::Gallery) || !$this->value) {
            return $this->value;
        }

        if ($this->type == FieldType::Image || $this->type == FieldType::File) {
            $repository = new FileRepository(new File());
            $model = $repository->find($this->value);
            if (!$model) {
                return null;
            }

            return $model->getShortJson();
        }

        if ($this->type == FieldType::Gallery) {
            $value = $this->value ? explode(',', $this->value) : [];
            $result = [];

            foreach ($value as $imageId) {
                $repository = new FileRepository(new File());
                $model = $repository->find($imageId);
                if (!$model) {
                    continue;
                }

                $result[] = $model->getShortJson();
            }

            return $result;
        }
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'type' => $this->type,
            'label' => $this->label,
            'value' => $this->prepareValue()
        ];
    }
}
