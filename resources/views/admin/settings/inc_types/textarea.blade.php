<div class="col-md-6">
    <label class="form-label" for="{{ $item->key }}">{{ $item->name }}</label>
    <textarea style="height: 300px"  id="{{ $item->key }}" class="form-control  @error($item->key) is-invalid @enderror" name="{{ $item->key }}"  >{{ old($item->key , $item->value)  }}</textarea>

    @error($item->key)
    <div class="invalid-feedback">{{ $message }}</div>
   @enderror
</div>
