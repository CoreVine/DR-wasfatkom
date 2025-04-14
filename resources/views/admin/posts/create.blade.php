@extends('layouts.app')
@section('title')
{{ __("messages.Articles") }}
@endsection
@section('style')
<style>


</style>
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('inc.breadcrumb' , ['breadcrumb_items'=>[
         __("messages.Home")=>route('home'),
         __("messages.Articles")=>route('admin.posts.index'),
         __("messages.Create")=>"active"
    ]])



    <div class="row justify-content-center">
        <!-- Form controls -->
        <div class="col-md-12">
            <!-- Multi Column with Form Separator -->
            <div class="card mb-4">
              <h5 class="card-header">{{ __("messages.Article data") }}</h5>
              <form class="card-body" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="nav-align-top  mb-4">
                        <ul class="nav nav-tabs" role="tablist">
                           @foreach ($languages as $lang )
                           <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link {{ $loop->index == 0 ? 'active'  : ''}}" role="tab" data-bs-toggle="tab" data-bs-target="#navs-{{ $lang->id }}" aria-controls="navs-{{ $lang->id }}" aria-selected="true">
                              {{ $lang->native_name }}
                            </button>
                          </li>
                           @endforeach


                        </ul>
                        <div class="tab-content p-0">
                            @foreach ($languages as $lang )
                            <div class="tab-pane fade {{ $loop->index == 0 ? 'active show'  : ''}}  " id="navs-{{ $lang->id }}" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label mb-1" for="title_{{ $lang->code }}">{{ __("messages.Title Article") }}  ( {{ $lang->code }} ) <span class="prefix_title"> [    {{ config('app.name') }} <span class="prefix_title_word"></span> ]</span></label>
                                        <input type="text" id="title_{{ $lang->code }}" data-slug_name="slug_{{ $lang->code }}" class="form-control input_name  @error('title_'.$lang->code) is-invalid @enderror" name="title_{{ $lang->code }}"  value="{{ old('title_'.$lang->code)  }}"  />
                                        @error('title_'.$lang->code)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label class="form-label mb-1" for="content_{{ $lang->code }}">{{ __("messages.Content") }}  ( {{ $lang->code }} )</label>
                                        <textarea  id="content_{{ $lang->code }}"  class="form-control editor_style_{{ $lang->code }}  @error('content_'.$lang->code) is-invalid @enderror" name="content_{{ $lang->code }}">{{ old('content_'.$lang->code)  }}</textarea>
                                        @error('content_'.$lang->code)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <hr class="mt-5">
                                    <h5 class="card-header">SEO</h5>
                                    <div class="col-md-6 mt-3 ">
                                        <label class="form-label mb-1" for="slug_{{ $lang->code }}">Slug  ( {{ $lang->code }} )</label>
                                        <input type="text" id="slug_{{ $lang->code }}"  class="form-control  @error('slug_'.$lang->code) is-invalid @enderror" name="slug_{{ $lang->code }}"  value="{{ old('slug_'.$lang->code)  }}"  />
                                        @error('slug_'.$lang->code)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mt-3 ">
                                        <label class="form-label mb-1" for="meta_description_{{ $lang->code }}">Meta Description  ( {{ $lang->code }} )</label>
                                        <input type="text" id="meta_description_{{ $lang->code }}"  class="form-control  @error('slug_'.$lang->code) is-invalid @enderror" name="meta_description_{{ $lang->code }}"  value="{{ old('meta_description_'.$lang->code)  }}"  />
                                        @error('meta_description_'.$lang->code)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>



                                  </div>
                                </div>

                            @endforeach


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
@include('admin.posts.script')
@include('inc.editor')
@endsection
