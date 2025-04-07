@extends('layouts.app')

@section('content')
    <h2 class="text-xl font-semibold mb-4">
        「<span class="text-blue-600">{{ $keyword }}</span>」の検索結果（{{ $manuals->count() }}件）
    </h2>

    @if ($manuals->isEmpty())
        <p>該当するマニュアルは見つかりませんでした。</p>
    @else
        <ul>
            @foreach ($manuals as $manual)
                <li class="mb-2">
                    <a href="{{ route('manuals.show', $manual->id) }}" class="text-blue-700 underline font-semibold">
                        {{ $manual->title }}
                    </a>
                    <div class="text-sm text-gray-600 ml-4">
                        診療科: <span class="text-gray-700 underline cursor-default">{{ $manual->specialty->name ?? '未登録' }}</span> /
                        分類: <span class="text-gray-700 underline cursor-default">{{ $manual->classification->name ?? '未登録' }}</span> /
                        術式: <span class="text-gray-700 underline cursor-default">{{ $manual->procedure->name ?? '未登録' }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <button onclick="history.back()" class="mb-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">
        ← 検索ページへ戻る
    </button>

@endsection