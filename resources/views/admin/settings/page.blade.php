@extends('layouts.app')
@section('title')
  {{ __("messages.Settings") }}
@endsection
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    @include('inc.breadcrumb' , ['breadcrumb_items' => [
         __("messages.Home") => route('home'),
         __("messages.Settings")=>null,
         __("messages.".$page) =>"active"
    ]])


    <div class="row justify-content-center">
      @foreach ($settings as  $key => $setting)

        <div class="col-md-12">
          <!-- Multi Column with Form Separator -->
          <div class="card mb-4">
            <h5 class="card-header">{{ $key }}</h5>
            <form class="card-body" action="{{ route('admin.settings.pages.update' , $key) }}" method="POST"
                  enctype="multipart/form-data">
              @csrf
              <div class="row g-3">
                @foreach ($setting as $item)
                  @if (in_array($item->type , ['text' , 'number' , 'email' , 'url']))
                    @include('admin.settings.inc_types.input')
                  @elseif($item->type == "textarea")
                    @include('admin.settings.inc_types.textarea')
                  @elseif($item->type == "radio")
                    @include('admin.settings.inc_types.radio')
                  @elseif($item->type === 'select')
                    @include('admin.settings.inc_types.select')
                  @endif
                @endforeach
              </div>

              <div class="pt-4">
                <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('messages.Save') }}</button>
                <button type="reset" class="btn btn-label-secondary">{{ __('messages.Cancel') }}</button>
              </div>
            </form>
          </div>


        </div>
      @endforeach

      <div class="card mb-4">
          <h5 class="card-header">{{ __('messages.Change Password') }}</h5>
          <form class="card-body" action="{{ route('admin.admins.change-password' , $key) }}" method="POST"
                enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="current_password">{{ __("messages.current_password" ) }}</label>
                <input type="password" id="current_password" step=".1" class="form-control  @error('current_password') is-invalid @enderror" name="current_password"  value="{{ old('current_password')  }}"  />
                @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label" for="new_password">{{ __("messages.new_password" ) }}</label>
                <input type="password" id="new_password" step=".1" class="form-control  @error('new_password') is-invalid @enderror" name="new_password"  value="{{ old('new_password')  }}"  />
                @error('new_password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label" for="new_password_confirmation">{{ __("messages.new_password_confirmation" ) }}</label>
                <input type="password" id="new_password_confirmation" step=".1" class="form-control  @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation"  value="{{ old('new_password_confirmation')  }}"  />
                @error('new_password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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
@endsection
