<?php

namespace Pimeo\Jobs\Pim\Attribute;

use Pimeo\Events\Pim\AttributeWasCreated;
use Pimeo\Jobs\Job;
use Pimeo\Models\Attribute;
use Pimeo\Models\Company;
use Pimeo\Models\User;
use Pimeo\Repositories\AttributeLabelRepository;
use Pimeo\Repositories\AttributeRepository;
use Pimeo\Repositories\AttributeTypeRepository;
use Pimeo\Repositories\AttributeValueRepository;

class CreateAttribute extends Job
{
    /**
     * The fields of the new attribute.
     *
     * @var array
     */
    protected $fields;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var AttributeLabelRepository
     */
    protected $labelRepository;

    /**
     * @var AttributeTypeRepository
     */
    protected $typeRepository;

    /**
     * @var AttributeValueRepository
     */
    protected $valueRepository;

    /**
     * Create a new job instance.
     *
     * @param array                    $fields
     * @param User                     $user
     * @param AttributeRepository      $attributeRepository
     * @param AttributeLabelRepository $labelRepository
     * @param AttributeTypeRepository  $typeRepository
     * @param AttributeValueRepository $valueRepository
     */
    public function __construct(
        array $fields,
        User $user,
        AttributeRepository $attributeRepository,
        AttributeLabelRepository $labelRepository,
        AttributeTypeRepository $typeRepository,
        AttributeValueRepository $valueRepository
    ) {
        $this->fields = $fields;
        $this->user = $user;
        $this->attributeRepository = $attributeRepository;
        $this->labelRepository = $labelRepository;
        $this->typeRepository = $typeRepository;
        $this->valueRepository = $valueRepository;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->normalizeNameField($this->getCompany()->defaultLanguage->code);
        $this->fields['attribute_type_id'] = $this->typeRepository->findByCode($this->fields['attribute_type_id'])->id;

        $label = $this->labelRepository->create($this->getLabelAttribute());

        $attribute = $this->attributeRepository->create(array_merge($this->getFields(), [
            'attribute_label_id' => $label->id,
            'created_by'     => $this->user->id,
            'updated_by'     => $this->user->id,
        ]));

        $this->saveValues($attribute);

        if (array_has($this->fields, 'natures')) {
            $attribute->natures()->sync($this->getNaturesIds());
        }

        if (array_has($this->fields, 'buildingComponents')) {
            $attribute->buildingComponents()->sync($this->getBuildingComponentsIds());
        }

        event(new AttributeWasCreated($attribute->fresh()));
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
            'company_id',
            'model_type',
        ]);
    }

    /**
     * Get the natures ids.
     *
     * @return array
     */
    protected function getNaturesIds()
    {
        return $this->fields['natures'];
    }

    protected function getBuildingComponentsIds()
    {
        return $this->fields['buildingComponents'];
    }

    /**
     * Format the label values.
     *
     * @return array
     */
    protected function getLabelAttribute()
    {
        return ['name' => $this->fields['name'], 'values' => $this->fields['label']];
    }

    protected function normalizeNameField($language_code)
    {
        $label = $this->fields['label'][$language_code];

        $this->fields['name'] = str_slug($label);
    }

    /**
     * @return Company
     */
    protected function getCompany()
    {
        return $this->user->companies()->first();
    }

    protected function saveValues(Attribute $attribute)
    {
        if (!isset($this->fields['choice'])) {
            return;
        }

        $values = $this->normalizeChoiceFields();

        $this->valueRepository->create([
            'attribute_id' => $attribute->id,
            'values'       => $values,
        ]);
    }

    protected function normalizeChoiceFields()
    {
        return $this->fields['choice'];
    }
}
