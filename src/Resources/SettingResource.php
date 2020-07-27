<?php

namespace OZiTAG\Tager\Backend\Settings\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Ozerich\FileStorage\Models\File;
use Ozerich\FileStorage\Repositories\FileRepository;
use OZiTAG\Tager\Backend\Core\Enums\Enum\FieldType;

class SettingResource extends JsonResource
{
    private function prepareValue()
    {
        if ($this->type != SettingType::IMAGE || !$this->value) {
            return $this->value;
        }

        $repository = new FileRepository(new File());
        $model = $repository->find($this->value);
        if (!$model) {
            return null;
        }

        return $model->getUrl();
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
