<div class="panel panel-default flat pad">
    <div class="form-group" v-for="attribute in attributes" v-cloak>
        @{{ attribute.value }}
        <button @click.prevent="removeAttribute($index, attribute.id)" class="btn btn-xs btn-flat btn-danger btn-delete">
            <i class="fa fa-fw fa-trash"></i>
            <span class="hidden-sm hidden-xs">{{ trans('parent-product.index.table.delete') }}</span>
        </button>
    </div>
    @if (isset($attributes['choices']))
    <button class="btn btn-primary" @click.prevent="addAttribute">
        <i class="fa fa-fw fa-plus"></i>
        <span class="hidden-sm hidden-xs">{{trans('system.attribute.add')}}</span>
    </button>
    <div class="form-group" v-for="newAttribute in newAttributes">
        {!! Form::label('link_attribute[]', trans('partial.new-attribute'), ['class' => 'control-label']) !!}
        <select name="link_attribute[]" v-select="newAttribute.choice" :options="choices" style="width: 100%">
            <option value="">{{ trans('attribute.edit.default_choice') }}</option>
        </select>
        <button @click.prevent="removeNewAttribute($index)" class="btn btn-xs btn-flat btn-danger btn-delete">
            <i class="fa fa-fw fa-trash"></i>
            <span class="hidden-sm hidden-xs">{{ trans('parent-product.index.table.delete') }}</span>
        </button>
    </div>
    @endif
    <button type="submit" class="btn btn-primary" :disabled="!saveEnabled">
        <i class="fa fa-fw fa-pencil"></i>
        {{ trans('attribute.edit.save') }}
    </button>
    <input type="hidden" value="@{{ attributesToDelete.join(',') }}" name="attributesToDelete" />
</div>

@push('scripts')
<script type="text/javascript" src="/js/vue.js"></script>
<script>
    $(function () {

        $("select.select2").select2();

        Vue.directive('select', {
            twoWay: true,
            priority: 1000,
            params: ['options'],
            bind: function() {
                var self = this;
                $(this.el).select2({data: this.params.options}).on('change', function() {
                    self.set(this.value);
                });
            },
            update: function(value) {
                $(this.el).val(value).trigger('change');
            },
            unbind: function() {
                $(this.el).off().select2('destroy');
            }
        });

        new Vue({
            el: '#vue',
            data: {
                attributes: [
                    @foreach ($product->linkAttributes as $linkAttribute)
                    @if (!$linkAttribute->attribute->is_min_requirement) // Permet pas suppression d'attr required
                    {
                        id: {{ $linkAttribute->id }},
                        value: '{!! addslashes($linkAttribute->attribute->label->values[$current_language->code]) !!}'
                    },
                    @endif
                    @endforeach
                ],
                newAttributes: [],
                attributesToDelete: [],
                choices: {!! collect($attributes['choices']) !!},
                saveEnabled: true
            },
            methods: {
                addAttribute: function() {
                    this.newAttributes.push({choice: ''});
                },
                removeNewAttribute: function (index) {
                    this.newAttributes.splice(index, 1);
                },
                removeAttribute: function (index, id) {
                    this.attributes.splice(index, 1);
                    this.attributesToDelete.push(id);
                }
            }
        })
    });
</script>
@endpush
