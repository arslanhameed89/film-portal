<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use BeyondCode\Comments\Traits\HasComments;
/**
 * Class Film.
 *
 * @package namespace App\Models;
 */
class Film extends Model implements Transformable
{
    use TransformableTrait, HasComments;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo',
        'slug',
        'name',
        'release_date',
        'rating',
        'description',
        'price',
        'country_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function genres()
    {
        return $this->hasMany(FilmGenre::class, 'film_id', 'id');
    }
}
