<div class="col-md-6">
    <label class="form-label" for="{{ $item->key }}">{{ __("messages.".$item->name ) }}</label>
    <input type="{{ $item->type }}" id="{{ $item->key }}" step=".1" class="form-control  @error($item->key) is-invalid @enderror" name="{{ $item->key }}"  value="{{ old($item->key , $item->value)  }}"  />
    @error($item->key)
    <div class="invalid-feedback">{{ $message }}</div>
   @enderror
</div>
