<div class="p-3 d-flex justify-content-between">
    <div>
        <form>
            <div class="row">
                {{-- <div class="col-6">


                    <div class="input-group">
                        <input value="{{ request('key_words') }}" name="key_words" type="text"
                            class="form-control" placeholder="{{ __('messages.Name') }}"
                            aria-label="Example text with button addon" aria-describedby="button-addon1">
                        <button type="submit" class="btn btn-outline-primary waves-effect" type="button"
                            id="button-addon1">{{ __('messages.Search') }}<i
                                class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div> --}}
                <div class="col-12">

                    <div class="input-group">
                        <select id="select2Basic" name="filter_school_id_admin" class="select2 form-select form-select-lg" data-allow-clear="true">
                            @isset($schools)
                                <option value="">{{ __('messages.Choose school') }}</option>
                                @foreach ($schools as $school)
                                <option value="{{ $school->id }}" @if(request('filter_school_id_admin') == $school->id) selected @endif>{{ $school->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                        <button type="submit" class="btn btn-outline-primary waves-effect" type="button"
                            id="button-addon1">{{ __('messages.Search') }}<i
                                class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>
            </div>

        </form>
    </div>
    <div>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="#"
                class="btn btn-outline-primary waves-effect text-primary">{{ __('messages.EXPORT XLS') }}<i
                    class="fa-solid fa-file-export"></i></a>
        </div>
    </div>
</div>
