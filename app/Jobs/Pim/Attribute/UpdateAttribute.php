<?php

namespace Pimeo\Jobs\Pim\Attribute;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\Filesystem as LocalFilesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Pimeo\Events\Pim\AttributeWasUpdated;
use Pimeo\Jobs\Job;
use Pimeo\Models\Attribute;
use Pimeo\Models\LinkAttribute;
use Pimeo\Repositories\AttributeTypeRepository;
use Pimeo\Repositories\AttributeValueRepository;
use Request;

class UpdateAttribute extends Job
{
    /**
     * The instance of the attribute to update.
     *
     * @var Attribute
     */
    protected $attribute;

    /**
     * The fields to apply to the attribute.
     *
     * @var array
     */
    protected $fields;

    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @var AttributeTypeRepository
     */
    protected $typeRepository;

    /**
     * @var AttributeValueRepository
     */
    protected $valueRepository;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var LocalFilesystem
     */
    protected $localFiles;

    /**
     * Create a new job instance.
     *
     * @param Attribute                $attribute
     * @param array                    $fields
     * @param Guard                    $auth
     * @param AttributeTypeRepository  $typeRepository
     * @param AttributeValueRepository $valueRepository
     * @param Filesystem               $files
     * @param LocalFilesystem          $localFiles
     */
    public function __construct(
        Attribute $attribute,
        array $fields,
        Guard $auth,
        AttributeTypeRepository $typeRepository,
        AttributeValueRepository $valueRepository,
        Filesystem $files,
        LocalFilesystem $localFiles
    ) {
        $this->attribute = $attribute;
        $this->oldAttributeType = $attribute->type;
        $this->fields = $fields;
        $this->auth = $auth;
        $this->typeRepository = $typeRepository;
        $this->valueRepository = $valueRepository;
        $this->files = $files;
        $this->localFiles = $localFiles;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $currentAttributeValues = $this->attribute->value;

        $this->updateAttribute();
        $this->updateNatures();
        $this->updateBuildingComponents();
        $this->updateLabel();
        $this->updateValues();
        $this->deleteLinkAttributes();

        event(new AttributeWasUpdated($this->attribute->fresh(), $currentAttributeValues));
    }

    /**
     * Get the fields to apply to the attribute.
     *
     * @return array
     */
    protected function getFields()
    {
        return array_only($this->fields, [
            'name',
            'attribute_type_id',
        ]);
    }

    /**
     * Get the natures ids.
     *
     * @return array
     */
    protected function getNaturesIds()
    {
        return array_get($this->fields, 'natures');
    }

    protected function getBuildingComponentsIds()
    {
        return array_get($this->fields, 'buildingComponents');
    }

    /**
     * Format the label values.
     *
     * @return array
     */
    protected function getLabelValues()
    {
        return $this->fields['label'];
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getUser()
    {
        return $this->auth->user();
    }

    protected function updateAttribute()
    {
        $this->fields['attribute_type_id'] = $this->typeRepository->findByCode($this->fields['attribute_type_id'])->id;

        $fields = array_merge($this->getFields(), [
            'updated_by' => $this->getUser()->id,
        ]);
        $this->attribute->update($fields);
    }

    protected function updateNatures()
    {
        if (!($ids = $this->getNaturesIds())) {
            return;
        }

        $this->attribute->natures()->sync($ids);
    }

    protected function updateBuildingComponents()
    {
        if (!($ids = $this->getBuildingComponentsIds())) {
            return;
        }

        $this->attribute->buildingComponents()->sync($ids);
    }

    protected function updateLabel()
    {
        $this->attribute->label->update(['values' => $this->getLabelValues()]);
    }

    protected function updateValues()
    {
        if (!isset($this->fields['choice'])) {
            if ($this->attribute->value) { // Supprime les attribute_values si n'est plus un choix
                $this->valueRepository->delete($this->attribute->value->id);
            }
            return;
        }

        $this->cleanUpFiles();
        $this->saveFiles();

        $choices = $this->normalizeChoiceFields();

        if ($this->attribute->value) {
            $this->valueRepository->update($this->attribute->value->id, [
                'values' => $choices,
            ]);

            return;
        }

        $this->valueRepository->create([
            'attribute_id' => $this->attribute->id,
            'values'       => $choices,
        ]);
    }

    private function deleteLinkAttributes()
    {
        // N'est pas une opération destructice ou n'affect aucun produits
        if (!isset($this->fields['deleteLinkAttributes'])) {
            return;
        }

        if ($this->oldAttributeType->code != $this->attribute->fresh()->type->code) {
            $this->deleteLinkAttributeIfInvalid($this->oldAttributeType->code, $this->attribute->fresh()->type->code);
        }

        if (isset($this->fields['choice'])) {
            $this->deleteOldValuesFromLinkAttributes();
        }
    }

    private function deleteLinkAttributeIfInvalid($oldType, $newType)
    {
        $conditionsSkipDelete = (
               ($oldType == 'file' && $newType == 'files')
            || ($oldType == 'choice' && $newType == 'choice_multiple')
            || ($oldType == 'choice' && $newType == 'choice_checkbox_multiple')
            || ($oldType == 'choices' && $newType == 'choice_checkbox_multiple')
            || ($oldType == 'choice_checkbox_multiple' && $newType == 'choices')
        );

        if ($conditionsSkipDelete) {
            return;
        }

        LinkAttribute::whereAttributeId($this->attribute->id)->delete();
    }

    private function deleteOldValuesFromLinkAttributes()
    {
        if (Request::has('attributesValuesToDelete')) {
            $attributesValuesToDelete = explode(',', Request::input('attributesValuesToDelete'));
            $linkAttributes = LinkAttribute::whereAttributeId($this->attribute->id)->with('values')->get();

            foreach ($linkAttributes as $linkAttribute) {
                if (!isset($linkAttribute->values['keys'])) {
                    continue;
                }

                $newValue['keys'] = collect($linkAttribute->values['keys'])->diff($attributesValuesToDelete);

                $linkAttribute->values = $newValue;
                $linkAttribute->values()->save($linkAttribute->values);
            }
        }
    }

    protected function merge(array $original, array $new)
    {
        $keys = array_keys(array_dot($new));

        foreach ($keys as $key) {
            if ($value = array_get($new, $key)) {
                array_set($original, $key, $value);
            }
        }

        return $original;
    }

    protected function normalizeChoiceFields()
    {
        $choices = $this->fields['choice'];
        $values = [];

        foreach ($choices as $language => $choice) {
            foreach ($choice as $key => $value) {
                $values[$language][$key] = $this->normalizeValue($value, $key, $language);
            }
        }

        return $values;
    }

    protected function normalizeValue($value, $key, $language)
    {
        if (!is_array($value)) {
            return $value;
        }

        if (isset($value['image']) && ($file = $value['image']) && $file instanceof UploadedFile) {
            $value['image'] = $file->getClientOriginalName();
        } elseif (!isset($value['image']) &&
            isset($this->attribute->value->values[$language][$key]['image']) &&
            !isset($value['delete'])
        ) {
            $value['image'] = $this->attribute->value->values[$language][$key]['image'];
        }

        if (isset($value['delete'])) {
            array_forget($value, 'delete');
        }

        return $value;
    }

    protected function cleanUpFiles()
    {
        $keys = array_dot($this->fields['choice']);

        /** @var Collection $toSet */
        $toSet = collect();

        collect($keys)->each(function ($value, $key) use ($toSet) {
            if (substr($key, -6) == 'delete' && $value == '1') {
                $imageKey = str_replace('delete', 'image', $key);

                $file = array_get($this->attribute->value->values, $imageKey);

                $toSet->put($imageKey, '');

                array_forget($this->fields, $imageKey);

                $values = $this->attribute->value->values;
                array_forget($values, $imageKey);
                $this->attribute->value->values = $values;
            }
        });

        $toSet->each(function ($value, $key) {
            $this->setValue($key, $value);
        });
    }

    protected function deleteImage($filename)
    {
        if (!is_null($filename) && $this->files->exists($filename)) {
            $this->files->delete($filename);
        }
    }

    protected function setValue($key, $value)
    {
        $values = $this->attribute->value->values;
        array_set($values, $key, $value);

        $this->attribute->value->update(['values' => $values]);
    }

    protected function saveFiles()
    {
        $images = collect(array_dot($this->fields['choice']))->filter(function ($value, $key) {
            return substr($key, -5) == 'image' && !is_null($value);
        });

        if ($images->count() == 0) {
            return;
        }

        $this->attribute->value->touch();

        $basename = $this->attribute->value->updated_at->timestamp;

        $images->each(function ($image, $key) use ($basename) {
            /** @var UploadedFile $image */
            $name = array_get($this->fields['choice'], str_replace('image', 'name', $key));
            $extension = $image->getClientOriginalExtension();
            $basefilename = $image->getClientOriginalName();
            $filename = $basefilename . sha1($basename . $name . $basefilename) . '.' . $extension;
            $content = $this->localFiles->get($image->path());

            $this->localFiles->delete($image->path());
            $this->files->put($filename, $content);

            array_set($this->fields['choice'], $key, $filename);
        });
    }
}
