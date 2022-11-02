<?php

namespace Pimeo\Forms;

use Auth;
use Kris\LaravelFormBuilder\Form;
use Pimeo\Models\Attribute;
use Pimeo\Models\AttributeLabel;
use Pimeo\Models\BuildingComponent;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\Nature;

class BaseForm extends Form
{
    /**
     * Create instance of a field type for an attribute
     *
     * @param LinkAttribute $linkAttribute
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function createField(LinkAttribute $linkAttribute)
    {
        $attribute = $linkAttribute->attribute;

        $fieldType = $this->createFieldForAttribute($attribute);

        if (isset($linkAttribute->values)) {
            $fieldType->setValues($linkAttribute->values);
        }

        return $fieldType;
    }

    protected function createFieldForAttribute(Attribute $attribute)
    {
        $field_code = $attribute->type->code;
        $fieldClass = 'Pimeo\\Forms\\Fields\\' . studly_case($field_code);
        $fieldType = app($fieldClass);

        $fieldType->setAttribute($attribute);
        $fieldType->setValues(null);

        if (method_exists($fieldType, 'setCompany')) {
            $fieldType->setCompany(auth()->user()->companies->first()->id);
        }

        return $fieldType;
    }

    protected function addFieldsForFieldType($fieldType)
    {
        $parentClass = get_class($this);
        $isParentEditForm = $parentClass == 'Pimeo\Forms\ParentProductEditForm';
        foreach ($fieldType->getFields() as $field) {
            //Ne pas permettre d'edit la nature du produit
            if ($isParentEditForm && $fieldType->getAttribute()->name == 'product_nature') {
                $field = $this->addFieldForTypeNature($field);
            } else {
                $field = $this->addInlineDeleteButton($fieldType, $field);
            }
            $this->add($field['name'], $field['type'], $field['attrs']);
        }
    }

    protected function addFieldForTypeNature($field)
    {
        /// Ajout d'un hidden avec la valeur et modifie le champ pour être un div readonly
        if (!empty($field['attrs']['selected'])) {
            $field['attrs']['value'] = $field['attrs']['selected'][0];
            $this->add($field['name'], 'hidden', $field['attrs']);

            $field['attrs']['attr']['readonly'] = 'readonly';
            $field['type'] = 'static';
            $field['attrs']['tag'] = 'div';
            $field['attrs']['value'] = $field['attrs']['choices'][$field['attrs']['selected'][0]];
            $field['name'] = 'product_nature';
        }
        return $field;
    }

    protected function addInlineDeleteButton($fieldType, $field)
    {
        /// Ajout de bouton pour supprimer un attribut dans le help_block du form builder
        if ($fieldType->getAttribute()->is_min_requirement == false) {
            $field['attrs']['help_block'] = [
                'text' => ' ',
                'tag'  => 'div',
                'attr' => ['class' => 'fa fa-trash inline-delete', 'id' => $fieldType->getAttribute()->id],
            ];
            if (!isset($field['attrs']['attr']['class'])) {
                $field['attrs']['attr']['class'] = 'form-control';
            }
            $field['attrs']['attr']['id'] = str_replace(['[', ']'], ['_', ''], $field['name']);
            $field['attrs']['attr']['class'] .= ' inline-control';
        }

        return $field;
    }

    /**
     * @param LinkAttribute[] $attributes
     */
    protected function addMissingRequiredAttributes($linkAttributes)
    {
        $required_attributes = $this->getData('required_attributes');
        $existingAttributeIDs = $linkAttributes->pluck('attribute_id');

        foreach ($required_attributes as $attribute) {
            if (!$existingAttributeIDs->contains($attribute->id)) {
                $fieldType = $this->createFieldForAttribute($attribute);
                $this->addFieldsForFieldType($fieldType);#
            }
        }
    }

    protected function addMediaField()
    {
        // add Media
        $field_code = 'ChoiceMedia';
        $fieldClass = 'Pimeo\\Forms\\Fields\\' . studly_case($field_code);
        $fieldType = app($fieldClass);
        if ($this->getData('medias') != null) {
            $fieldType->setValues(array_pluck($this->getData('medias')->toArray(), 'media_id'));
        }
        $field = $fieldType->getFields();
        $this->add($field['name'], $field['type'], $field['attrs']);
    }

    protected function addNatureField($selectedValue = null)
    {
        $natures = Nature::all();

        $choices = [];
        foreach ($natures as $nature) {
            $choices[$nature->id] = trans("attribute.natures.{$nature->code}");
        }

        $attrs['choices'] = $choices;
        $attrs['label'] = AttributeLabel::find(2)->values[Auth::user()->getLanguageCode()];

        if ($selectedValue !== null) {
            $attrs['selected'] = $selectedValue;
        }

        $this->add(
            'nature_id',
            'choice',
            $attrs
        );
    }

    protected function addBuildingComponentsField($selectedValue = null)
    {
        $buildingComponents = BuildingComponent::forCurrentCompany()->get();

        $choices = [];
        foreach ($buildingComponents as $component) {
            $choices[$component->id] = trans('building-component.component.' . $component->code);
        }

        $attrs = [
            'choices'  => $choices,
            'label'    => trans('building-component.system.edit.field.label'),
            'multiple' => true
        ];

        if ($selectedValue !== null) {
            $attrs['selected'] = $selectedValue;
        }

        $this->add(
            'buildingComponents',
            'choice',
            $attrs
        );
    }

    protected function addSaveField($label = 'parent-product.edit.save')
    {
        $this->add(
            'submit',
            'submit',
            [
                'label'  => trans($label),
                'attr'   => [
                    'value' => trans($label),
                    'id'    => 'save_field',
                    'class' => 'btn btn-primary',
                    'name'  => 'submit',
                ],
            ]
        );
    }

    protected function addParentNewProductField()
    {
        $attrs = [
            'label'   => trans('parent-product.form.new_product'),
            'value'   => 1,
            'checked' => !empty($this->getData('product')->new_product),
        ];
        $this->add('new_product', 'checkbox', $attrs);
    }

    protected function addParentStarProductField()
    {
        $attrs = [
            'label'   => trans('parent-product.form.star_product'),
            'value'   => 1,
            'checked' => !empty($this->getData('product')->star_product),
        ];
        $this->add('star_product', 'checkbox', $attrs);
    }
}
