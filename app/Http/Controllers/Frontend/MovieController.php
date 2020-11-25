<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Type;
use App\Models\Source;
use App\Models\Video;
use App\Models\Channel;
use App\Models\Playlist;
use App\Models\Tag;

class MovieController extends Controller
{
    public function index()
    {
        //Nominations
        $movieNominations = Movie::select('id', 'name', 'name_origin', 'description', 'card_cover', 'runtime', 'slug', 'rate')
            ->where('nominations', config('config.default_nomination'))
            ->orderBy('rate', 'desc')
            ->take(config('config.take_movie'))
            ->get();

        foreach ($movieNominations as $movie) {
            $movie->types = $movie->Types()
                ->select('title', 'slug')
                ->get()
                ->toArray();
        }
        $movieNominations = $movieNominations->toArray();

        $movies = $this->getMovieWithGenre(config('config.genre_of_movie'))->toArray();

        $tvSeries = $this->getMovieWithGenre(config('config.genre_of_tvserie'))->toArray();

        return view('frontend.pages.movie.home')->with([
            'movieNominations' => $movieNominations,
            'movies' => $movies,
            'tvSeries' => $tvSeries,
        ]);
    }

    public function getMovieWithGenre($genre)
    {
        $films = Movie::select('id', 'name', 'name_origin', 'age', 'description', 'card_cover', 'runtime', 'slug', 'rate', 'quality')
            ->where('genre', $genre)
            ->orderBy('updated_at', 'desc')
            ->take(config('config.take_movie'))
            ->get();

        foreach ($films as $film) {
            $film->types = $film->Types()
                ->select('title', 'slug')
                ->get();

            $film->quality = $this->getOptionValueOfFilm('quality', $film);
            $film->country = $this->getOptionValueOfFilm('country', $film);
        }

        return $films;
    }

    public function getOptionValueOfFilm($name, $film)
    {
        $options = Option::select('id', 'name')->get();
        foreach ($options as $option) {
            if ($option->name == $name) {
                $optionValues = $option->optionValues;
                foreach ($optionValues as $optionValue) {
                    if ($optionValue->order == $film->$name) {
                        return $optionValue->name;
                    }
                }
            }
        }
    }

    public function watchMovie($slug, $prioritize, $video = null)
    {
        $movie = Movie::select('id', 'name', 'name_origin', 'age', 'description', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')
            ->where('slug', $slug)
            ->first();
        if ($movie == null) {
            return abort(404);
        }
        $types = $movie->types->toArray();
        $tags = $movie->tags->toArray();

        $movie->quality = $this->getOptionValueOfFilm('quality', $movie);
        $movie->country = $this->getOptionValueOfFilm('country', $movie);

        if ($movie['genre'] == config('config.genre_of_movie')) {
            //Trường hợp phim lẻ
            $video = $movie->videos()
                ->select('videos.id', 'tags')
                ->first();
            if ($video == null) {
                return abort(404);
            }
            if ($prioritize == config('config.default_prioritize')) {
                $source = $video->sources()
                    ->select('id', 'source_key', 'video_id', 'prioritize', 'status', 'channel_id')
                    ->where([
                        ['status', config('config.status_true')],
                        ['prioritize', $prioritize],
                    ])
                    ->first();
            } else {
                $source = Source::select('sources.id', 'source_key', 'video_id', 'prioritize', 'sources.status', 'channel_id')
                    ->join('videos', function ($join) {
                        $join->on('sources.video_id', '=', 'videos.id');
                    })
                    ->join('channels', function ($join) {
                        $join->on('sources.channel_id', '=', 'channels.id');
                    })
                    ->where([
                        ['sources.status', config('config.status_true')],
                        ['videos.id', $video->id],
                        ['prioritize', $prioritize],
                    ])
                    ->first();
            }
            if ($source == null) {
                return abort(404);
            }

            $channel = Channel::select('id', 'title', 'channel_type', 'status', 'order')
                ->where([
                    ['status', config('config.status_true')],
                    ['channels.id', $source->channel_id],
                ])
                ->first()
                ->toArray();

            $backups = Source::select('channels.id', 'channels.title', 'channels.status', 'channel_type', 'order', 'sources.prioritize')
                ->join('channels', function ($join) {
                    $join->on('channel_id', '=', 'channels.id');
                })
                ->join('videos', function ($join) {
                    $join->on('sources.video_id', '=', 'videos.id');
                })
                ->where([
                    ['channels.status', config('config.status_true')],
                    ['videos.id', $source->video_id],
                ])
                ->orderBy('sources.prioritize', 'asc')
                ->get()
                ->toArray();


            if ($video == null) {
                return abort(404);
            }
            $source = $source->toArray();
            $video = $video->toArray();

            return view('frontend.pages.movie.movie_detail')->with([
                'movie' => $movie,
                'types' => $types,
                'source' => $source,
                'channel' => $channel,
                'backups' => $backups,
                'tags' => $tags,
                'video' => $video,
            ]);
        } else {
            //Trường hợp phim bộ
            if ($video == null) {
                $playlist = $movie->playlists()
                    ->where([
                        ['movie_id', $movie['id']],
                        ['playlists.status', config('config.status_true')],
                    ])
                    ->orderBy('order', 'asc')
                    ->first();
                if ($playlist == null) {
                    return abort(404);
                }
                $chap = $playlist->videos()
                    ->select('id', 'title', 'status', 'tags', 'slug', 'movie_id', 'playlist_id', 'chap')
                    ->where('status', config('config.status_true'))
                    ->orderBy('chap', 'asc')
                    ->first();
            } else {
                $chap = Video::select('id', 'title', 'status', 'slug', 'movie_id', 'playlist_id', 'chap')
                    ->where([
                        ['status', config('config.status_true')],
                        ['slug', $video],
                    ])
                    ->first();
            }
            if ($chap == null) {
                 return abort(404);
            }

            if ($prioritize == config('config.default_prioritize')) {
                $source = Source::select('id', 'source_key', 'video_id', 'status', 'prioritize', 'status', 'movie_id', 'channel_id', 'video_id')
                    ->where([
                        ['prioritize', $prioritize],
                        ['status', config('config.status_true')],
                        ['video_id', $chap->id],
                    ])
                    ->first();
            } else {
                $source = Source::select('sources.id', 'source_key', 'sources.status', 'video_id', 'prioritize', 'sources.status', 'sources.movie_id', 'channel_id')
                    ->join('channels', function ($join) {
                        $join->on('sources.channel_id', '=', 'channels.id');
                    })
                    ->where([
                        ['prioritize', $prioritize],
                        ['sources.status', config('config.status_true')],
                        ['video_id', $chap->id],
                    ])
                    ->first();
            }

            $channel = Channel::select('id', 'channel_type', 'status', 'order')
                ->where([
                    ['id', $source->channel_id],
                    ['status', config('config.status_true')],
                ])
                ->first()
                ->toArray();

            $backups = Source::select('channels.id', 'channels.title', 'channel_type', 'sources.prioritize')
                ->join('channels', function ($join) {
                    $join->on('channel_id', '=', 'channels.id');
                })
                ->join('videos', function ($join) {
                    $join->on('sources.video_id', '=', 'videos.id');
                })
                ->where('channels.status', config('config.status_true'))
                ->where('videos.id', $chap['id'])
                ->orderBy('sources.prioritize', 'asc')
                ->get()
                ->toArray();

            $playlists = $movie->playlists()
                ->select('id', 'title', 'description', 'status', 'order')
                ->where('status', config('config.status_true'))
                ->orderBy('order', 'asc')
                ->get();

            foreach ($playlists as $playlist) {
                $playlist->videos = $playlist->videos()
                    ->orderBy('chap')
                    ->get();
            }
            $playlists = $playlists->toArray();
            $movie = $movie->toArray();
            $source = $source->toArray();

            return view('frontend.pages.movie.movie_series')->with([
                'movie' => $movie,
                'types' => $types,
                'source' => $source,
                'playlists' => $playlists,
                'channel' => $channel,
                'backups' => $backups,
                'chap' => $chap,
                'tags' => $tags,
            ]);
        }
    }


    public function listMovie($key, $slug)
    {
        if ($key == 'type') {
            $type = Type::select('id', 'title', 'slug')
                ->where('slug', $slug)
                ->first();

            $movies = $type->movies()
                ->select('movies.id', 'name', 'name_origin', 'age', 'description', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')
                ->paginate(config('config.pagination_movie'));

            foreach ($movies as $video) {
                $video->type = $video->types()
                    ->select('title', 'slug')
                    ->get();
            }
            $params['type'] = $type->toArray();
        } else if ($key == 'country') {
            $movies = $this->getMovieFromOption($key, $slug);
            $params['country'] = $this->getOptionValue($key, $slug)->toArray();
        } else if ($key == 'genre') {
            $movies = $this->getMovieFromOption($key, $slug);
            $params['genre'] = $this->getOptionValue($key, $slug)->toArray();
        }
        $filters= $this->getDataForFilter();

        return view('frontend.pages.movie.catalog_grid')->with([
            'movies' => $movies,
            'params' => $params,
            'filters' => $filters,
        ]);
    }

    public function getDataForFilter()
    {
        $filters['types'] = Type::select('id', 'title', 'slug')->get();
        $options = Option::select('id', 'name')->get();
        foreach ($options as $option) {
            if ($option->name == 'genre') {
                $filters['genre'] = $option->optionValues;
            } elseif ($option->name == 'quality') {
                $filters['quality'] = $option->optionValues;
            } elseif ($option->name == 'country') {
                $filters['country'] = $option->optionValues;
            }
        }

        return $filters;
    }

    public function getOptionValue($key, $slug)
    {
        $option_id = Option::select('id')
                ->where('name', $key)
                ->first();
        $option_value = OptionValue::select('name', 'order')
            ->where('option_id', $option_id->id)
            ->where('order', $slug)
            ->first();

        return $option_value;
    }

    public function getMovieFromOption($key, $slug)
    {
        $movies = Movie::select('id', 'name', 'name_origin', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')
                ->where($key, $slug)
                ->paginate(config('config.pagination_movie'));
        foreach ($movies as $video) {
            $video->type = $video->types()
                ->select('title', 'slug')
                ->get()
                ->toArray();
        }

        return $movies;
    }

    public function filter(Request $request)
    {
        $movies = Movie::select('movies.id', 'name', 'name_origin', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year');

        if ($request->get('type') != null) {
            $movies = $movies->join('movie_type', function ($join) {
                $join->on('movies.id', '=', 'movie_type.movie_id');
            })
                ->where('movie_type.type_id', '=', $request->get('type'));

            $params['type'] = Type::select('id', 'title')
                ->where('id', $request->get('type'))
                ->first()
                ->toArray();
        }

        if ($request->get('genre') != null) {
            $movies = $movies->where('genre', $request->get('genre'));
            $option_id = Option::select('id')->where('name', 'genre')->first();
            $genre = OptionValue::select('id', 'name', 'order')
                ->where('option_id', $option_id->id)
                ->where('order', $request->get('genre'))
                ->first();
            $params['genre'] = $genre->toArray();
        }

        if ($request->get('country') != null) {
            $movies = $movies->where('country', $request->get('country'));
            $option_id = Option::select('id')->where('name', 'country')->first();
            $country = OptionValue::select('id', 'name', 'order')
                ->where('option_id', $option_id->id)
                ->where('order', $request->get('country'))
                ->first();
            $params['country'] = $country->toArray();
        }

        $movies = $movies->paginate(config('config.pagination_movie'));
        foreach ($movies as $video) {
            $video->type = $video->Types()
                ->select('types.id', 'title', 'slug')
                ->get()
                ->toArray();
        }

        $filters = $this->getDataForFilter();

        return view('frontend.pages.movie.catalog_grid')->with([
            'movies' => $movies,
            'params' => $params,
            'filters' => $filters,
        ]);
    }

    public function search(Request $request)
    {
        $movies = Movie::select('id', 'name', 'name_origin', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')
            ->where('name', 'like', '%' . $request->get('search') . '%')
            ->paginate(config('config.pagination_movie'));
        foreach ($movies as $video) {
            $video->type = $video->types()
                ->select('title', 'slug')
                ->get()
                ->toArray();
        }

        return view('frontend.pages.movie.search')->with([
            'movies' => $movies,
            'key' => $request->get('search'),
        ]);
    }

    public function searchTag($slug)
    {
        $tag = Tag::select('id', 'name', 'slug')
            ->where('slug', $slug)
            ->first();

        $movies = $tag->movies()
            ->select('movies.id', 'name', 'name_origin', 'age', 'card_cover', 'runtime', 'slug', 'rate', 'genre', 'country', 'quality', 'release_year')
            ->paginate(config('config.pagination_movie'));
        foreach ($movies as $video) {
            $video->type = $video->types()
                ->select('title', 'slug')
                ->get()
                ->toArray();
        }
        $tag = $tag->toArray();

        return view('frontend.pages.movie.searchTag')->with([
            'movies' => $movies,
            'tag' => $tag,
        ]);
    }
}
