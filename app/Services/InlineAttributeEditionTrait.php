<?php

namespace Pimeo\Services;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Pimeo\Forms\InlineAttributesForm;
use Pimeo\Models\LinkAttribute;

trait InlineAttributeEditionTrait
{
    private function addInlineAttribute(
        Request $request,
        FormBuilder $formBuilder,
        $repositoryClass,
        $product,
        $modelType
    ) {
        $repository = app($repositoryClass);

        $newLinkAttributes = [];

        $uniqueNewAttributes = array_unique((array)$request->get('attributes'));
        foreach ($uniqueNewAttributes as $newAttributeID) {
            if (!$product->hasLinkAttribute($newAttributeID)) {
                $linkAttribute = new LinkAttribute(['attribute_id' => $newAttributeID]);
                $newLinkAttributes[] = $linkAttribute;
                $product->linkAttributes()->save($linkAttribute);
            }
        }

        $form = $formBuilder->create(InlineAttributesForm::class, [], [
            'linkAttributes' => $newLinkAttributes,
        ]);

        $nature = null;
        if (method_exists($repository, 'nature')) {
            $nature = $repository->nature($product);
        }
        $attributes = $this->attributes->allNotLinkedToProduct(
            $product,
            $nature,
            $modelType
        );

        $selectAttributes = view('pim.partials.add-attributes-inline-selectAttributes')
            ->withAttributes($attributes)->render();

        return [
            'form'             => $form->renderForm(),
            'selectAttributes' => $selectAttributes,
        ];
    }

    private function deleteInlineAttribute(Request $request, $repositoryClass, $product, $modelType)
    {
        $attributeId = $request->get('attribute_id');
        $repository = app($repositoryClass);

        $linkAttributesToDelete = $product->linkAttributes
            ->where('attribute_id', (int) $attributeId)
            ->pluck('id');
        LinkAttribute::destroy($linkAttributesToDelete);
        $product->load('linkAttributes');

        $nature = null;
        if (method_exists($repository, 'nature')) {
            $nature = $repository->nature($product);
        }
        $attributes = $this->attributes->allNotLinkedToProduct(
            $product,
            $nature,
            $modelType
        );

        $selectAttributes = view('pim.partials.add-attributes-inline-selectAttributes')
            ->withAttributes($attributes)->render();

        return [
            'selectAttributes' => $selectAttributes,
        ];
    }
}
