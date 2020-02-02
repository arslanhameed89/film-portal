<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Contracts\filmRepository;
use App\Models\Film;
use Illuminate\Container\Container as Application;

/**
 * Class FilmRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FilmRepositoryEloquent extends BaseRepository implements FilmRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Film::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        return $this->create(
            [
                'name' => $request->name,
                'slug' => $this->createSlug($request->name),
                'release_date' => $request->date,
                'rating' => $request->rating,
                'description' => $request->description,
                'country_id' => $request->country_id,
                'photo' => $request->photo,
                'price' => $request->price
            ]);
    }

    /**
     * @param $title
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)) {
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= ($allSlugs->count() + 1); $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new Exception('Can not create a unique slug');
    }

    /**
     * @param $slug
     * @param int $id
     * @return mixed
     */
    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Film::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    /**
     * @param $request
     *
     * @return mixed
     */
    public function getFilmBySlug($request)
    {
        return $this->with(['genres'])->findWhere([
            'slug' => $request->input('slug')
        ])
        ->first();
    }
}
