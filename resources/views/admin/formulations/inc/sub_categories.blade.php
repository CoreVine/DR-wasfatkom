<div class="col-4 mt-4">
    <label class="mb-1">{{ __('messages_301.Select sub categories') }}</label>
    <select name="sub_category_id" id="select_category_id" class="form-control select2 select_sub_category_id">
        <option value="">--------</option>
        @foreach ($sub_categories as $sub_category)
            <option value="{{ $sub_category->id }}" @selected(old('sub_category_id') == $sub_category->id)>
                {{ $sub_category->name }}</option>
        @endforeach
    </select>
</div>
