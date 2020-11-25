@extends('backend.layouts.master')

@section('title')
    {{ trans('edit_type') }}
@endsection

@section('main__title')
    <h2>{{ trans('edit_type') }}</h2>
    <a href="{{ route('backend.type.index') }}" class="main__title-link">{{ trans('type_list') }}</a>
@endsection

@section('content')
    <div class="col-12">
        <div class="profile__content">
            <div class="profile__user">
                <div class="profile__meta profile__meta--green">
                    <h3>{{ $type['title'] }}</h3>
                    <span>{{ trans('id') }}: {{ $type['id'] }}</span>
                </div>
            </div>
            <div class="profile__actions">
                <form action="{{ route('backend.type.destroy' , $type['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="delete_type" class="profile__action profile__action--delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <form action="{{ route('backend.type.update', $type['id'])  }}" method="POST" class="profile__form">
                            <div class="row">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $type['id'] }}">
                                <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('title') }} (*)
                                        @error ('title')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <input id="username" type="text" name="title" class="profile__input" placeholder="{{ $type['title'] }}" value="{{ $type['title'] }}">
                                    </div>
                                </div>


                                <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('description') }} (*)
                                        @error ('description')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <textarea id="text" name="description" class="form__textarea" placeholder="{{ trans('description') }}">{{ $type['description'] }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="profile__btn" type="submit">{{ trans('save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('build/backend/js/crudtype.js') }}"></script>
@endsection
