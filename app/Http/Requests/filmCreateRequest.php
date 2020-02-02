<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class filmCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function validationData()
    {
        $this->merge(json_decode($this->input('film'),true));
        return $this->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'logo'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|string|max:191',
            'date' => 'required',
            'rating' => 'required|numeric',
            'genre' => 'required',
            'description' => 'required',
            'price' => 'required',
            'country_id' => 'required'
        ];

        return $rules;;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        $attributes = [
            'logo'     => 'film picture',
            'name' => 'film name',
            'date' => 'release date',
            'rating' => 'film rating',
            'genre' => 'film genre',
            'description' => 'film description',
            'price' => 'film price',
            'country_id' => 'Country'
        ];
        return $attributes;
    }
}
