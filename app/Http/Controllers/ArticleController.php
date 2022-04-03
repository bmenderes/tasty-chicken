<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Inertia\Inertia;



class ArticleController extends Controller
{
    /**
     * Constructor
     * 
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Article::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();
        return Inertia::render('Articles/Index', [
            'articles' => $articles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Articles/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        $article = Article::create(
            array_merge(
                $request->validated(),
                ['user_id' => auth()->id()]
            )
        );


        return redirect()->route('articles.show', $article);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {

        $permissions = [
            'update' => auth()->user()->can('update', $article),
            'delete' => auth()->user()->can('delete', $article),
        ];

        return Inertia::render('Articles/Show', compact('article', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        if (auth()->id() !== $article->user_id) {
            abort(403);
        }

        $article->update($request->validated());

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //bu kisim automatic olarak __construct $this->authorizeResource(Article::class); sayesinde ekleniyor
        // if (
        //     auth()->user()->cant('delete', $article)
        // )
        //     return abort(403);

        $article->delete();

        return redirect()->route('articles.index');
    }
}
