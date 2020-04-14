@extends('layouts.app')

@section('content')

  <div class="container wrap">
    <h1>{{ $article->title }}</h1>

    <div class="p-3 m-3 lead">
        @if ($article->image)
            <center>
                <img src="{{ $article->image }}" alt="{{ $article->image }}">
            </center>
            <br />
        @endif

        {!! nl2br($article->body) !!}
    </div>
  </div>

@endsection
