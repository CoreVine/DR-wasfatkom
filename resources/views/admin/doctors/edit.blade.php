@extends('layouts.app')
@section('title')
{{ __('messages_303.doctors') }}
@endsection
@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/rateyo/rateyo.css') }}" />
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  @include('inc.breadcrumb', [
  'breadcrumb_items' => [
  __('messages.Home') => route('home'),
  __('messages_303.doctors') => route('admin.doctors.index'),
  __('messages.Edit') => 'active',
  ],
  ])

  <div class="row justify-content-center">
    <!-- Form controls -->
    <div class="col-md-12">
      <!-- Multi Column with Form Separator -->
      <div class="card mb-4">
        <h5 class="card-header">{{ __('messages_303.doctor data') }}</h5>
        <form class="card-body" action="{{ route('admin.doctors.update', $item->id) }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <h6>1. {{ __('messages.Basic information') }}</h6>
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <img src="{{ isset($item?->image) ? asset($item->image) : asset('default-images/user-image.png') }}"
                alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
              <div class="button-wrapper">
                <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                  <span class="d-none d-sm-block">{{ __('messages_303.Upload new photo') }}</span>
                  <i class="ti ti-upload d-block d-sm-none"></i>
                  <input type="file" id="upload" name="image" class="account-file-input @error('image')
                                        is-invalid
                                      @enderror" hidden accept="image/png, image/jpeg, image/jpg" />
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
          <hr class="my-4 mx-n4" />
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="multicol-email">{{ __('messages.Name') }}</label>
              <input type="text" name="name" value="{{ old('name', $item->name) }}" id="multicol-email"
                class="form-control  @error('name') is-invalid @enderror" />
              @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label" for="multicol-email">{{ __('messages.Email') }}</label>
              <input type="text" name="email" value="{{ old('email', $item->email) }}" id="multicol-email"
                class="form-control  @error('email') is-invalid @enderror" />
              @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label" for="multicol-username">{{ __('messages.Phone Number') }}</label>
              <input type="text" id="multicol-username" class="form-control  @error('mobile') is-invalid @enderror"
                name="mobile" value="{{ old('mobile', $item->mobile) }}" />
              @error('mobile')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label" for="multicol-username">{{ __('messages_301.Clinic name') }}</label>
              <input type="text" id="multicol-username" class="form-control  @error('clinic_name') is-invalid @enderror"
                name="clinic_name" value="{{ old('clinic_name', $item->clinic_name) }}" />
              @error('clinic_name')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <div class="form-password-toggle">
                <label class="form-label" for="multicol-password">{{ __('messages.Password') }}</label>
                <div class="input-group input-group-merge">
                  <input type="password" name="password" id="multicol-password"
                    class="form-control @error('password') is-invalid @enderror"
                    aria-describedby="multicol-password2" />
                  <span class="input-group-text cursor-pointer" id="multicol-password2"><i
                      class="ti ti-eye-off"></i></span>
                </div>
                @error('password')
                <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-password-toggle">
                <label class="form-label" for="multicol-confirm-password">{{ __('messages.Confirm Password') }}</label>
                <div class="input-group input-group-merge">
                  <input type="password" name="password_confirmation" id="multicol-confirm-password"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    aria-describedby="multicol-confirm-password2" />
                  <span class="input-group-text cursor-pointer" id="multicol-confirm-password2"><i
                      class="ti ti-eye-off"></i></span>
                </div>
                @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label" for="multicol-is-active">{{ __('messages.isActive') }}</label>
              <select name="is_active" class="form-control">
                <option value="1" {{ old('is_active', $item->is_active) == 1 ? 'selected' : '' }}>
                  Active
                </option>
                <option value="0" {{ old('is_active', $item->is_active) == 0 ? 'selected' : '' }}>
                  Inactive
                </option>
              </select>
              @error('is_active')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

          </div>
          <hr class="my-4 mx-n4" />

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
<script>
  $(function() {
      $('#upload').change(function() {
        const file = this.files[0];
        if (file) {
          let reader = new FileReader();
          reader.onload = function(event) {
            $('#uploadedAvatar').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      })
    })
</script>
@endsection