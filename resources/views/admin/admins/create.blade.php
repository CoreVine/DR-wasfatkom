@extends('layouts.app')
@section('title')
    {{ __('messages.Technical support') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages.Technical support') => route('admin.admins.index'),
                __('messages.Create') => 'active',
            ],
        ])



        <div class="row justify-content-center">
            <!-- Form controls -->
            <div class="col-md-12">
                <!-- Multi Column with Form Separator -->
                <div class="card mb-4">
                    <h5 class="card-header">{{ __('messages.Employee Data') }}</h5>
                    <form class="card-body" action="{{ route('admin.admins.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <h6>1. {{ __('messages.Basic information') }}</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="multicol-username">{{ __('messages.Name') }}</label>
                                <input type="text" id="multicol-username"
                                    class="form-control  @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="multicol-email">{{ __('messages.Email') }}</label>
                                <input type="text" name="email" value="{{ old('email') }}" id="multicol-email"
                                    class="form-control  @error('email') is-invalid @enderror" />
                                @error('email')
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
                                    <label class="form-label"
                                        for="multicol-confirm-password">{{ __('messages.Confirm Password') }}</label>
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

                        </div>
                        <hr class="my-4 mx-n4" />
                        <h6>2. {{ __('messages.Identify doctors') }}</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="" class="form-label d-block"><small class="text-danger "
                                        style="font-size: 12px">{{ __('messages.When selected, it will be applied to all available doctors who will be added later') }}</small></label>
                                <label class="switch switch-primary ">
                                    <input type="checkbox" name="is_all_doctor" @checked(old('is_all_doctor')=='on') id="is_doctor" class="switch-input" data-check_confirm="{{ __('messages.Confirm') }}"  data-check_cancel="{{ __('messages.Cancel') }}" data-message="{{ __('messages.Are you sure to activate the account ?') }}">
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="ti ti-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="ti ti-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label"
                                        style="cursor: pointer !important">{{ __('messages.Follow all doctors') }}</span>
                                </label>
                            </div>
                            <!-- Users List -->
                            <div class="col-md-6 mb-4">
                                <label for="TagifyUserList"
                                    class="form-label">{{ __('messages.Identify doctors') }}</label>
                                <input id="TagifyUserList" name="doctors"
                                    class="form-control @error('doctors') is-invalid
                                @enderror"
                                    value="demo@admin.com" />
                                @error('doctors')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 mx-n4" />

                        <h6>3. {{ __('messages.Roles And Permission') }}</h6>
                        <!-- Form with Tabs -->
                        <div class="row">
                            <div class="col">
                                {{--  <h6 class="mt-4">Form with Tabs</h6>  --}}
                                <div class="card mb-3">
                                    <div class="card-header pt-2">
                                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                                            @foreach (__('permissions_superadmin') as $key => $val)
                                                <li class="nav-item">
                                                    <button type="button"
                                                        class="nav-link {{ $loop->index == 0 ? 'active' : '' }}"
                                                        data-bs-toggle="tab"
                                                        data-bs-target="#form-tabs-{{ $loop->index }}" role="tab"
                                                        aria-selected="true">{{ $key }}</button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="tab-content">

                                        @foreach (__('permissions_superadmin') as $key => $val)
                                            <div class="tab-pane fade {{ $loop->index == 0 ? ' active show' : '' }} "
                                                id="form-tabs-{{ $loop->index }}" role="tabpanel">
                                                <div class="row">
                                                    @foreach ($val as $sub_key => $sub_val)
                                                        <div class="col-md-2 col-6 mb-2">
                                                            <div class="form-check custom-option custom-option-basic">
                                                                <label class="form-check-label custom-option-content"
                                                                    for="{{ $sub_key }}">
                                                                    <input @checked(old('permissions') && in_array($sub_key, old('permissions')))
                                                                        name="permissions[]" class="form-check-input"
                                                                        type="checkbox" name="permission"
                                                                        value="{{ $sub_key }}"
                                                                        id="{{ $sub_key }}">
                                                                    <span class="custom-option-header">
                                                                        <span class="h6 mb-0">{{ $sub_val }}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach


                                                </div>
                                            </div>
                                        @endforeach


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4">
                            <button type="submit"
                                class="btn btn-primary me-sm-3 me-1">{{ __('messages.Save') }}</button>
                            <button type="reset" class="btn btn-label-secondary">{{ __('messages.Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    @include('admin.admins.script')
@endsection
