<?php

namespace App\Http\Controllers;

use App\Contracts\FilmGenreRepository;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\filmCreateRequest;
use App\Http\Requests\FilmUpdateRequest;
use App\Contracts\FilmRepository;
use App\Validators\FilmValidator;
use Prettus\Validator\LaravelValidator;

/**
 * Class FilmsController.
 *
 * @package namespace App\Http\Controllers;
 */
class FilmsController extends Controller
{
    /**
     * @var FilmRepository
     */
    protected $repository;

    /**
     * @var FilmValidator
     */
    protected $validator;
    /**
     * @var \App\Contracts\FilmGenreRepository
     */
    private $filmGenreRepository;

    /**
     * FilmsController constructor.
     *
     * @param FilmRepository                     $repository
     * @param LaravelValidator                   $validator
     * @param \App\Contracts\FilmGenreRepository $filmGenreRepository
     */
    public function __construct(
        FilmRepository $repository,
        LaravelValidator $validator,
        FilmGenreRepository $filmGenreRepository
    )
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->filmGenreRepository = $filmGenreRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $films = $this->repository->all();

        return response()->json([
            'details' => $films,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param filmCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function store(filmCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            // Upload Image
            $imageName = time().'.'.request()->logo->getClientOriginalExtension();
            request()->logo->move(public_path('images'), $imageName);
            $request->merge([
                'photo'  => $imageName
            ]);

            // Store Film Data
            $film = $this->repository->store($request);

            // Store Film Genre Data
            $request->merge([
                'film_id'   => $film->id,
            ]);
            $this->filmGenreRepository->multipleGenre($request);
            $response = [
                'message' => 'Film created.',
                'details'    => $film,
            ];

            return response()->json($response);

        } catch (ValidatorException $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessageBag()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $film = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $film,
            ]);
        }

        return view('films.show', compact('film'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $film = $this->repository->find($id);

        return view('films.edit', compact('film'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FilmUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(FilmUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $film = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Film updated.',
                'data'    => $film->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Film deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Film deleted.');
    }

    /**get
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createComment(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $film = $this->repository->find($request->input('film_id'));
        $film->commentAsUser($user, $request->input('content'));

        if(count($film->comments) > 0){
            foreach ($film->comments as $key => $value){
                $film->comments[$key]->user = User::find($value->user_id);
            }
        }
        return response()->json([
            'details' => $film->comments,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilmBySlug(Request $request)
    {
        $film = $this->repository->getFilmBySlug($request);

        return response()->json([
            'details' => $film,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilmComments(Request $request)
    {
        $film = $this->repository->find($request->input('film_id'));
        if(count($film->comments) > 0){
            foreach ($film->comments as $key => $value){
                $film->comments[$key]->user = User::find($value->user_id);
            }
        }
        return response()->json([
            'details' => $film->comments,
        ]);
    }
}
