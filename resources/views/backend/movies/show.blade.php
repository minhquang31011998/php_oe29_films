
@extends('backend.layouts.master')

@section('title')
Movie Detail
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
@endsection

@section('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
    $('#roleId').select2();
</script>
@endsection

@section('main__title')
<h2>Movie Detail</h2>
{{-- <span class="main__title-stat">{{ count($movies) }} Total</span> --}}
<div class="main__title-wrap">
    <!-- filter sort -->
    <!-- end filter sort -->

    <!-- search -->
    <!-- end search -->
</div>
<a href="{{ route('backend.movie.index') }}" class="main__title-link">Movie List</a>
@endsection

@section('content')
<!-- profile -->
<div class="col-12">
    <div class="profile__content">
        <!-- profile user -->
        <div class="profile__user">
            <div class="profile__meta profile__meta--green">
                <h3>{{ $movie->name }}</h3>
                <span>ID: {{ $movie->id }}</span>
            </div>
        </div>
        <!-- end profile user -->

        <!-- profile tabs nav -->
        <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Detail</a>
            </li>
            @if($movie->genre==2)
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Playlists</a>
            </li>
            @endif
        </ul>
        <!-- end profile tabs nav -->

        <!-- profile mobile tabs nav -->
        <div class="profile__mobile-tabs" id="profile__mobile-tabs">
            <div class="profile__mobile-tabs-btn dropdown-toggle" role="navigation" id="mobile-tabs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <input type="button" value="Profile">
                <span></span>
            </div>

            <div class="profile__mobile-tabs-menu dropdown-menu" aria-labelledby="mobile-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Detail</a></li>
                    @if($movie->genre==2)
                    <li class="nav-item"><a class="nav-link" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Playlists</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- end profile mobile tabs nav -->

        <!-- profile btns -->
        <div class="profile__actions">
            <form action="{{ route('backend.movie.destroy' , $movie->id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="profile__action profile__action--delete">
                    <i class="icon ion-ios-trash"></i>
                </button>
            </form>
        </div>
        <!-- end profile btns -->
    </div>
</div>
<!-- end profile -->
<!-- content tabs -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
        <div class="col-12">
            <div class="row">
                <!-- details form -->
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-12">
                        <table class="main__table">
                            <tbody>
                                <tr>
                                    <td class="main__table-text">ID</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->id }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Name</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->name }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Card Cover</td>
                                    <td>
                                        <img src="{{ asset('$movie->card_cover') }}" style="max-height: 150px">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Description</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->description }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Age</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->age }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Nominations</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->nominations }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Genre</td>
                                    <td>
                                        @if($movie->genre == 1)
                                        <div class="main__table-text main__table-text--green">
                                        Movies
                                        </div>
                                        @else
                                        <div class="main__table-text main__table-text--green">
                                        TV series
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Runtime</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->runtime }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Release Year</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->release_year }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Quality</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->quality }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Country</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->country }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Rate</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->rate }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main__table-text">Slug</td>
                                    <td>
                                        <div class="main__table-text">{{ $movie->slug }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <!-- end details form -->
            </div>
        </div>  
    </div>

    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
        {{-- <div class="col-12">
            <div class="profile__content">
            <form action="{{ route('backend.movie.updatePlaylist', $movie->id) }}" method="POST" style="width:100%;">
                @csrf
                {{ method_field('PUT') }}
                <div class="form-group col-6" style="display: inline-block;">
                <select name="playlistId" id="playlistId">
                    @foreach($playlists as $playlist)
                    <option value="{{ $playlist->id }}">{{ $playlist->name }}</option>
                    @endforeach
                </select>   
                </div>
                <input type="hidden" name="id" value="{{ $movie->id }}">
                <button class="main__title-link"  style="float:right" type="submit">add new Playlist</button>
            </form>
            </div>
        </div> --}}
        <!-- table -->
        {{-- <div class="col-12">
            <div class="main__table-wrap">
                <table class="main__table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($moviePlaylists as $key => $role)
                        <tr>
                            <td>
                                <div class="main__table-text">{{ $role->id }}</div>
                            </td>
                            <td>
                                <div class="main__table-text">{{ $role->name }}</div>
                            </td>
                            <td>
                                <div class="main__table-text">{{ $role->description }}</div>
                            </td>
                            <td>
                                @if($role->status == 1)
                                <div class="main__table-text main__table-text--green">
                                Active
                                </div>
                                @else
                                <div class="main__table-text main__table-text--red">
                                Hidden
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="main__table-btns">
                                    <a href="#modal-view" class="main__table-btn main__table-btn--view open-modal">
                                        <i class="icon ion-ios-eye"></i>
                                    </a>
                                    <form action="{{ route('backend.movie.updatePlaylist', $movie->id) }}" method="POST">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <input type="hidden" name="roleId" value="{{ $role->id }}">
                                        <button class="main__table-btn main__table-btn--delete" type="submit"><i class="icon ion-ios-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}
        <!-- end table -->

        <!-- paginator -->
        {{-- <div class="col-12">
            <div class="paginator-wrap">
                {!! $links !!}
            </div>
        </div> --}}
        
        <!-- end paginator -->
    </div>
</div>
<!-- end content tabs -->
<!-- end users -->

<!-- paginator -->
<div class="col-12">
    <div class="paginator-wrap">
        {{-- <span>10 from {{ count($movies) }}</span> --}}
        
    </div>
</div>
<!-- end paginator -->
@endsection