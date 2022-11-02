<select multiple id="addAttributes" class="form-control">
@foreach ($attributes as $attribute)
        <option
            value="{{ $attribute['id'] }}">{{ is_array($attribute['text']) ? $attribute['text']['value'] : $attribute['text'] }}</option>
@endforeach
</select>
