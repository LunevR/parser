<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Show the list of news.
     *
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $articleList = Article::orderBy('created_at', 'desc')->paginate(10);

        return view('articles/index', [
            'articles' => $articleList,
        ]);
    }

    /**
     * Show the article.
     *
     * @return Renderable
     */
    public function show(Article $article): Renderable
    {
        return view('articles/show', [
            'article' => $article,
        ]);
    }
}
