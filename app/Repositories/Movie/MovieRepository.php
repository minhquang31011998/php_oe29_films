<?php
namespace App\Repositories\Movie;

use App\Models\Movie;
use App\Models\Type;
use App\Models\Video;
use App\Models\Source;
use App\Models\Channel;
use App\Models\Playlist;
use App\Models\Tag;
use App\Models\Option;
use App\Models\OptionValue;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MovieRepository extends BaseRepository implements MovieRepositoryInterface
{
    public function getModel()
    {
        return Movie::class;
    }

    public function getMovies($data = [])
    {
        $movies = Movie::select('id', 'name', 'nominations', 'slug', 'created_at');
        if (isset($data['name'])) {
            $movies = $movies->where('name', 'like', "%" . $request->get('name') . "%");
        }
        if (isset($data['sort'])) {
            if ($data['sort'] == trans('name')) {
                $movies = $movies->orderByRaw('name ASC');
            } elseif ($data['sort'] == trans('release_year')) {
                $movies = $movies->orderByRaw('release_year DESC');
            } elseif ($data['sort'] == trans('rate')) {
                $movies = $movies->orderByRaw('rate DESC');
            } elseif ($data['sort'] == trans('nomination')) {
                $movies = $movies->orderByRaw('nominations DESC');
            }
        }

        $movies = $movies->orderByRaw('created_at DESC')->get();
        foreach ($movies as $index => $movie) {
            $movie->index = $index + 1;
        }

        return $movies;
    }

    public function changeNomination($movieId)
    {
        try {
            $movie = Movie::findOrFail($movieId);
            if ($movie->nominations == config('config.nomination_on')) {
                $movie->nominations = config('config.nomination_off');
            } else {
                $movie->nominations = config('config.nomination_on');
            }
            $movie->save();

            return $movie;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function getTags()
    {
        $tags = Tag::get();

        return $tags;
    }

    public function getMoviePlaylists($movieId, $data = [])
    {
        $playlists = Playlist::select('id', 'title', 'order', 'description', 'movie_id')
            ->orderByRaw('playlists.order ASC')
            ->where('movie_id', '=', $movieId);

        if (isset($data['title'])) {
            $playlists = $playlists->where('title', 'like', "%" . $data['title'] . "%");
        }

        $playlists = $playlists->get();

        foreach ($playlists as $index => $playlist) {
            $playlist->index = $index + 1;
        }

        return $playlists;
    }

    public function getPlaylists($data = [])
    {
        $playlists = Playlist::select('id', 'title', 'order', 'description')
            ->orderByRaw('playlists.order ASC')
            ->where('movie_id', '=', null);

        if (isset($data['title'])) {
            $playlists = $playlists->where('title', 'like', "%" . $data['title'] . "%");
        }

        $playlists = $playlists->get();

        foreach ($playlists as $index => $playlist) {
            $playlist->index = $index + 1;
        }

        return $playlists;
    }

    public function getVideos($data = [])
    {
        $videos = Video::select('id', 'title', 'description')
            ->where([
                ['movie_id', '=', null],
                ['playlist_id', '=', null],
                ['status', '=', config('config.status_active')]
            ]);

        $videos = $videos->get();

        foreach ($videos as $index => $video) {
            $video->index = $index + 1;
        }

        return $videos;
    }

    public function getOptionValue($name)
    {
        $option = Option::where('name', $name)->first();
        $optionValues = $option->optionValues()->get();

        return $optionValues;
    }

    public function storeMovie($request)
    {
        $movie = new Movie();
        $movie->description = $request->get('description');
        $movie->name = $request->get('name');
        $movie->name_origin = $request->get('name_origin');
        $movie->age = $request->get('age');
        $movie->genre = $request->get('genre');
        $movie->runtime = $request->get('runtime');
        $movie->release_year = $request->get('release_year');
        $movie->quality = $request->get('quality');
        $movie->country = $request->get('country');
        $movie->card_cover = '';
        $movie->user_id = Auth::user()->id;

        if ($request->hasFile('card_cover')) {
            $movie->card_cover = $this->storeImages($request->file('card_cover'), $movie);
        }
        $movie->slug = Str::slug($request->get('name'));
        $oldMovie = Movie::where('slug', $movie->slug)->first();

        if ($oldMovie == null) {
            $movie->slug = Str::slug($request->get('name'));
        } else {
            $movie->slug = $oldMovie->slug . '-' . Str::random(3);
        }
        $movie->save();
        $types = $request->get('types');
        $movie->types()->attach($types);

        $this->storeTags($request->get('tags'), $movie);

        return $movie;
    }

    public function storeImages($cardCover, $movie)
    {
        $name = $movie->id . '.' . $cardCover->getClientOriginalName();
        $cardCover->storeAs(config('config.store_images_movie'), $name);
        $movie->card_cover = config('config.link_images_movie') . $name;

        return $movie->card_cover;
    }

    public function storeTags($tags, $movie)
    {
        $tags = explode(',', $tags);
        for ($i = 0; $i < count($tags); $i++) {
            $tag = Tag::where('name', $tags[$i])->first();
            if ($tag !== null) {
                $movie->tags()->attach($tag);
            } else {
                $tag = new Tag();
                $tag->name = $tags[$i];
                $tag->slug = Str::slug($tags[$i]) . time();
                $tag->save();

                $movie->tags()->attach($tag);
            }
        }
    }

    public function getChannels()
    {
        return Channel::get();
    }

    public function getTypes()
    {
        return Type::get();
    }

    public function getMovieTags($movie)
    {
        return $movie->tags()->get();
    }

    public function getMovieTypes($movie)
    {
        return $movie->types()->get();
    }

    public function getMovieVideos($movie)
    {
        return $movie->videos()->get();
    }

    public function updateMovie($movieId, $request)
    {
        try {
            $movie = Movie::findOrFail($movieId);
            $movie->name = $request->get('name');
            $movie->name_origin = $request->get('name_origin');
            $movie->description = $request->get('description');
            $movie->age = $request->get('age');
            $movie->runtime = $request->get('runtime');
            $movie->release_year = $request->get('release_year');
            $movie->quality = $request->get('quality');
            $movie->country = $request->get('country');
            $movie->user_id = Auth::user()->id;

            if ($request->hasFile('card_cover')) {
                $movie->card_cover = $this->updateImage($request->file('card_cover'), $movie);
            }
            $result = $movie->save();

            $this->updateTags($request->get('tags'), $movie);

            $movie->types()->detach();
            $types = $request->get('types');
            $movie->types()->attach($types);

            return $result;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function updateTags($tags, $movie)
    {
        $movie->tags()->detach();
        $this->storeTags($tags, $movie);
    }

    public function updateImage($cardCover, $movie)
    {
        $name = $movie->id . '.' . $cardCover->getClientOriginalName();
        $link = str_replace(config('config.link_images_movie'), '', $movie->card_cover);
        Storage::delete(config('config.image_store') . $link);
        $cardCover->storeAs(config('config.store_images_movie'), $name);
        $movie->card_cover = config('config.link_images_movie') . $name;

        return $movie->card_cover;
    }

    public function updatePlaylist($request, $movieId)
    {
        try {
            $moviePlaylists = Movie::findOrFail($movieId)
                ->playlists()
                ->get()
                ->sortBy('order');
            $playlist = Playlist::findOrFail($request->get('playlistId'));
            $playlist->movie_id = $movieId;
            $i = $playlist->order;
            foreach ($moviePlaylists as $moviePlaylist) {
                if ($moviePlaylist->order >= $playlist->order) {
                    $moviePlaylist->order = ++$i;
                    $moviePlaylist->save();
                }
            }
            $playlist->user_id = Auth::user()->id;
            $playlist->save();

            return $playlist;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function updateVideo($request, $movieId)
    {
        try {
            $video = Video::findOrFail($request->get('videoId'));
            $video->movie_id = $movieId;
            $video->user_id = Auth::user()->id;
            return $video->save();
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function deleteMovie($movieId)
    {
        try {
            $movie = Movie::findOrFail($movieId);
            $playlists = $movie->playlists;
            if ($playlists != null) {
                foreach ($playlists as $playlist) {
                    $playlist->movie_id = null;
                    $playlist->save();
                }
            }
            $video = $movie->videos()->first();
            if ($video != null) {
                $video->movie_id = null;
                $video->save();
            }

            return Movie::destroy($movieId);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function getNewMovieInMonth()
    {
        $month = Carbon::now()->month;
        $movies = Movie::whereMonth('created_at', $month)->get();
        $movies->count = $movies->count();

        return $movies;
    }
}
