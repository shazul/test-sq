<?php

namespace Pimeo\Forms;

class CompanyCreateForm extends BaseForm
{
    public function buildForm()
    {
        $languages = $this->getData('languages');
        $models = $this->getData('models');

        $this->add(
            'name',
            'text',
            [
                'label'  => trans('company.form.name'),
            ]
        );

        $this->add(
            'languages',
            'choice',
            [
                'label'   => trans('company.form.languages'),
                'choices' => $languages->pluck('name', 'id')->toArray(),
                'attr'    => [
                    'class' => 'form-control select2',
                ],
                'multiple' => true,
                'selected' => 2,
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
                    'class'  => 'btn btn-primary',
                    'name'   => 'save',
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
