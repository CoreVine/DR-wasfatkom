@extends('layouts.app')
@section('title')
  {{ __('messages_303.categories') }}
@endsection
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    @include('inc.breadcrumb', [
        'breadcrumb_items' => [
            __('messages.Home') => route('home'),
            __('messages_303.categories') => route('admin.categories.index'),
            __('messages.Edit') => 'active',
        ],
    ])


    <div class="row justify-content-center">
      <!-- Form controls -->
      <div class="col-md-12">
        <!-- Multi Column with Form Separator -->
        <div class="card mb-4">
          <h5 class="card-header">{{ __('messages_303.categories') }}</h5>
          <form class="card-body" action="{{ route('admin.categories.update', $item->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="d-flex align-items-start align-items-sm-center gap-4">
                <img src="{{ asset($item->image) }}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded"
                  id="uploadedAvatar" />
                <div class="button-wrapper">
                  <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                    <span class="d-none d-sm-block">{{ __('messages_303.Upload new photo') }}</span>
                    <i class="ti ti-upload d-block d-sm-none"></i>
                    <input type="file" id="upload" name="image"
                      class="account-file-input @error('image')
                                is-invalid
                              @enderror"
                      hidden accept="image/png, image/jpeg, image/jpg" />
                  </label>
                  <button type="button" class="btn btn-label-secondary account-image-reset mb-3">
                    <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">{{ __('messages_303.Reset') }}</span>
                  </button>

                  <div class="text-muted">Allowed JPG, JPEG or PNG. Max size of 2M</div>
                  @error('image')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
              </div>
            </div>
            <div class="row g-3">
              <div class="nav-align-top  mb-4">
                <ul class="nav nav-tabs" role="tablist">
                  @foreach ($languages as $lang)
                    <li class="nav-item" role="presentation">
                      <button type="button" class="nav-link {{ $loop->index == 0 ? 'active' : '' }}" role="tab"
                        data-bs-toggle="tab" data-bs-target="#navs-{{ $lang->id }}"
                        aria-controls="navs-{{ $lang->id }}" aria-selected="true">
                        {{ $lang->native_name }}
                      </button>
                    </li>
                  @endforeach
                </ul>
                <div class="tab-content p-0">
                  @foreach ($languages as $lang)
                    <div class="tab-pane fade {{ $loop->index == 0 ? 'active show' : '' }}  "
                      id="navs-{{ $lang->id }}" role="tabpanel">
                      <div class="row">
                        <div class="col-md-6 mt-3">
                          <label class="form-label mb-1" for="name_{{ $lang->code }}">{{ __('messages_303.name') }}
                            ({{ $lang->code }})
                          </label>
                          <input type="text" id="name_{{ $lang->code }}" data-slug_name="slug_{{ $lang->code }}"
                            class="form-control input_name  @error('name_' . $lang->code) is-invalid @enderror"
                            name="name_{{ $lang->code }}" value="{{ old('name_' . $lang->code, $item->name) }}" />
                          @error('name_' . $lang->code)
                            <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                        </div>
                        {{-- <div class="col-md-12 mt-3">
                                                    <label class="form-label mb-1"
                                                        for="description_{{ $lang->code }}">{{ __('messages_303.description') }}
                                                        (
                                                        {{ $lang->code }} )</label>
                                                    <textarea id="description_{{ $lang->code }}"
                                                        class="form-control editor_style_{{ $lang->code }}  @error('description_' . $lang->code) is-invalid @enderror"
                                                        name="description_{{ $lang->code }}">{{ old('description_' . $lang->code, $item->description) }}</textarea>
                                                    @error('description_' . $lang->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div> --}}


                      </div>
                    </div>
                  @endforeach
                  <br class="my-4 mx-n4" />

                  <div class="row g-3">

                    <div class="col-md-6">
                      <label for="" class="form-label d-block"><small class="text-danger "
                          style="font-size: 12px">{{ __('messages_301.When selected, commission will be applied for all doctors in this category') }}</small></label>
                      <label class="switch switch-primary ">
                        <input type="checkbox" name="is_commission" @checked(old('is_commission') == 'on' || $item->is_commission == 1) id="is_doctor"
                          class="switch-input">
                        <span class="switch-toggle-slider">
                          <span class="switch-on">
                            <i class="ti ti-check"></i>
                          </span>
                          <span class="switch-off">
                            <i class="ti ti-x"></i>
                          </span>
                        </span>
                        <span class="switch-label"
                          style="cursor: pointer !important">{{ __('messages_301.Apply commission') }}</span>
                      </label>
                    </div>
                  </div>

                </div>
              </div>


            </div>



            <div class="pt-4">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('messages.Save') }}</button>
              <button type="reset" class="btn btn-label-secondary">{{ __('messages.Cancel') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
@endsection
@section('script')
  <script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>
@endsection
