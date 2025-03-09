@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <!-- タイトル -->
        <h1 class="text-2xl font-bold mb-4">マニュアル</h1>

        <!-- パンくずリスト -->
        <div class="flex items-center gap-2 mb-4">
            <!-- 診療科に戻るリンク -->
            <a href="{{ route('manuals.specialty.index') }}"
               class="px-2 py-1 bg-gray-200 text-black rounded hover:bg-gray-300">
                {{ $specialty->name }}
            </a>
            <!-- 分類に戻るリンク -->
            <a href="{{ route('manuals.classification.index', $specialty->id) }}"
               class="px-2 py-1 bg-gray-200 text-black rounded hover:bg-gray-300">
                {{ $classification->name }}
            </a>
        </div>

        <!-- 検索フォーム -->
        <div class="mb-4 flex items-center gap-2">
            <input type="text" name="search" placeholder="診療科 / 術式 / 医師名 etc"
                   class="border border-gray-300 px-2 py-1 rounded w-1/3"
                   value="{{ request('search') }}">
            <button class="px-4 py-2 bg-blue-500 text-white rounded">検索</button>
        </div>

        <!-- 術式のボタン一覧 -->
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($procedures as $procedure)
                <!-- 1術式1マニュアル想定で manual_id がある場合 -->
                <a href="{{ route('manuals.show', $procedure->manual->id ?? 0) }}"
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-black rounded">
                    {{ $procedure->name }}
                </a>
            @endforeach
        </div>

        <!-- 新規マニュアル作成ボタン -->
        <div class="mb-6">
            <a href="{{ route('manuals.create') }}"
               class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                新規マニュアル作成
            </a>
        </div>
    </div>
@endsection
