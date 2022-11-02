<?php

namespace Pimeo\Forms;

class SystemLayerGroupsCreateForm extends BaseForm
{
    public function buildForm()
    {
        $languages = get_current_company_languages();

        foreach ($languages as $lang) {
            $this->add(
                "name[{$lang->code}]",
                'text',
                [
                    'label' => trans('system.layer-groups.name') . ' (' . trans(language_code_trans($lang->code)) . ')',
                ]
            );
        }

        $this->add('position', 'number', ['value' => 1]);

        $this->addSaveField('system.layer-groups.save');
    }
}
