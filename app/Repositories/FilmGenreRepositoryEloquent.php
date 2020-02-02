<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Contracts\filmGenreRepository;
use App\Models\FilmGenre;
use App\Validators\FilmGenreValidator;

/**
 * Class FilmGenreRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FilmGenreRepositoryEloquent extends BaseRepository implements FilmGenreRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FilmGenre::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        return $this->create(
            [
                'film_id'   => $request->film_id,
                'name'      => $request->name
            ]);
    }

    /**
     * @param $request
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function multipleGenre($request)
    {
        foreach ($request->input('genre') as $key => $value) {
            $request->merge([
                'film_id'   => $request->film_id,
                'name'      => $value
            ]);
            $this->store($request);
        }
    }
}
