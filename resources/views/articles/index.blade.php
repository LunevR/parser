@extends('layouts.app')

@section('content')

  <div class="container">
    <h1>Список статей</h1>

    <div class="row">
        <ul>
            @foreach ($articles as $article)
                <li>
                    <a href="{{ route('article.show', $article->id) }}">{{ $article->title }}</a>
                    <p>{{ Str::limit($article->body, 200, '...') }}</p>
                </li>
            @endforeach
        </ul>
    </div>

    {{ $articles->links() }}
  </div>

@endsection
