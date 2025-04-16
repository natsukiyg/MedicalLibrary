@extends('layouts.app')

@section('content')
    <h2 class="text-xl font-semibold mb-4 text-medical-neutral">
        「<span class="text-medical-accent">{{ $keyword }}</span>」の検索結果（{{ $manuals->count() }}件）
    </h2>

    @if ($manuals->isEmpty())
        <p>該当するマニュアルは見つかりませんでした。</p>
    @else
        <ul>
            @foreach ($manuals as $manual)
                <li class="mb-2">
                    <a href="{{ route('manuals.show', $manual->id) }}" class="text-medical-accent underline font-semibold">
                        {{ $manual->title }}
                    </a>
                    <div class="text-sm text-medical-base ml-4">
                        診療科: <span class="text-medical-neutral underline cursor-default">{{ $manual->specialty->name ?? '未登録' }}</span> /
                        分類: <span class="text-medical-neutral underline cursor-default">{{ $manual->classification->name ?? '未登録' }}</span> /
                        術式: <span class="text-medical-neutral underline cursor-default">{{ $manual->procedure->name ?? '未登録' }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <button onclick="history.back()" class="mb-4 px-4 py-2 bg-medical-base/30 hover:bg-medical-base text-medical-neutral rounded">
        ← 検索ページへ戻る
    </button>

@endsection