<?php

namespace Pimeo\Forms;

use Pimeo\Models\Language;

class SystemLayerGroupsEditForm extends BaseForm
{
    public function buildForm()
    {
        $languages = get_current_company_languages();
        $layer_group = $this->getData('layer_group');

        foreach ($languages as $lang) {
            $value = '';

            // Modif de couche
            if (isset($layer_group->name[$lang->code])) {
                $value = $layer_group->name[$lang->code];
            }

            $this->add(
                "name[{$lang->code}]",
                'text',
                [
                    'label' => trans('system.layer-groups.name') . ' (' . trans(language_code_trans($lang->code)) . ')',
                    'value' => $value,
                ]
            );
        }

        $this->add('position', 'number', ['value' => $layer_group->position]);

        $this->addSaveField('system.layer-groups.edit');
    }
}
