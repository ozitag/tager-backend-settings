<?php

namespace OZiTAG\Tager\Backend\Settings\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use Ozerich\FileStorage\Models\File;
use Ozerich\FileStorage\Repositories\FileRepository;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Utils\Enums\FieldType;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Utils\Formatter;

class ViewSettingsFeature extends Feature
{
    public function handle(SettingsRepository $repository, Formatter $formatter)
    {
        $items = $repository->all();

        $result = [];
        foreach ($items as $item) {
            $value = $formatter->formatValue($item->value, $item->type);;

            if (($item->type === FieldType::Image || $item->type === FieldType::File) && $value) {
                $value = $value->getFullJson();
            }

            if ($item->type == FieldType::Gallery) {
                if ($value) {
                    $imageIds = explode(',', $value);
                    $value = [];
                    foreach ($imageIds as $imageId) {


                        $repository = new FileRepository(new File());
                        $model = $repository->find($imageId);
                        if (!$model) {
                            continue;
                        }

                        $value[] = $model->getFullJson();
                    }
                }
            }

            $result[] = [
                'key' => $item->key,
                'value' => $value,
            ];
        }

        return new JsonResource($result);
    }
}
