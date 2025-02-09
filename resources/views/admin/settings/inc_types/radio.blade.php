<div class="col-md-12 d-flex ">
    @php
        $values_list = (explode(",",$item->list_values));
    @endphp
    @foreach ( $values_list as $val)
    <div class="form-check m-3">
        <input name="{{ $item->key }}" @checked($val == $item->value) class="form-check-input" type="radio" value="{{ $val }}" id="{{ $val }}">
        <label class="form-check-label" for="{{ $val }}"> {{ $val }} </label>
    </div>
    @endforeach

</div>
