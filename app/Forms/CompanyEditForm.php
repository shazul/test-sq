<?php

namespace Pimeo\Forms;

class CompanyEditForm extends BaseForm
{
    public function buildForm()
    {
        $languages = $this->getData('languages');
        $company = $this->getData('company');
        $users = $this->getData('user');
        $models = $this->getData('model');

        $this->add(
            'name',
            'text',
            [
                'label'  => trans('company.form.name'),
                'value'  => $company->name,
            ]
        );

        $this->add(
            'languages',
            'choice',
            [
                'label'    => trans('company.form.languages'),
                'choices'  => $languages->pluck('name', 'id')->toArray(),
                'selected' => $company->languages->lists('id')->toArray(),
                'attr'     => [
                    'class' => 'form-control select2',
                    'disabled',
                ],
                'multiple' => true,
            ]
        );

        $this->add(
            'default_language_id',
            'choice',
            [
                'label'    => trans('company.form.default-language'),
                'choices'  => $company->languages()->pluck('name', 'id')->toArray(),
                'selected' => $company->default_language_id,
                'attr'     => [
                    'class' => 'form-control select2',
                ],
            ]
        );

        $this->add(
            'models',
            'choice',
            [
                'label'   => trans('company.form.models'),
                'choices' => $models,
                'attr'    => [
                    'class' => 'form-control select2',
                    'disabled',
                ],
                'multiple' => true,
                'selected' => array_keys($models),
            ]
        );

        $this->add(
            'submit',
            'submit',
            [
                'label'  => '<i class="fa fa-fw fa-save"></i>' . trans('user.form.save'),
                'attr'   => [
                    'class'    => 'btn btn-primary',
                ],
            ]
        );

        $this->add(
            'cancel',
            'static',
            [
                'wrapper' => false,
                'label'   => false,
                'tag'     => 'a',
                'attr'    => [
                    'href'   => route('user.index'),
                    'class'  => 'btn btn-warning',
                ],
                'value' => '<i class="fa fa-fw fa-reply"></i>' . trans('user.form.cancel'),
            ]
        );
    }
}
