@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <!-- タイトルエリア -->
    <div class="mb-6">
        <h1 class="text-4xl font-bold mb-2">マニュアル</h1>
    </div>

    @php
        // ルートパラメータ 'specialty' が設定されている場合、それを active とする
        $activeSpecialty = request()->route('specialty');
    @endphp

    <!-- 診療科ボタン一覧 -->
    <div class="grid gap-4 mb-6" style="grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));">
        @foreach($specialties as $specialty)
            <!-- 診療科ボタンがアクティブかどうかでスタイルを変更 -->
            @php
                $isActive = ($activeSpecialty == $specialty->id);
                $defaultClasses = 'cursor-pointer block px-6 py-3 rounded transition-colors duration-200 text-black border border-black';
                $activeClasses = 'bg-[rgba(247,79,191,0.36)]';
                $inactiveClasses = 'bg-white hover:bg-[rgba(247,79,191,0.5)]';
                $classes = $isActive ? "$defaultClasses $activeClasses" : "$defaultClasses $inactiveClasses";
            @endphp
            <!-- リンク先は、選択された診療科に紐づく分類一覧のページを想定 -->
            <a href="{{ route('manuals.classification.index', $specialty->id) }}"
                class="{{ $classes }} text-center">
                {{ $specialty->name }}
            </a>
        @endforeach
    </div>
    
    <!-- 検索フォーム -->
    <form action="{{ route('manuals.search') }}" method="GET" class="mb-6 flex items-center gap-2">
        <input type="text" name="keyword" placeholder="診療科 / 分類 / 術式 etc"
               class="border border-gray-300 px-4 py-2 rounded w-3/4 focus:outline-none focus:ring-2 focus:ring-blue-300" 
            value="{{ request('keyword') }}">
        <button type="submit" class="cursor-pointer rounded-lg px-3 py-2 text-sm transition-colors duration-200 bg-[rgba(0,0,128,0.59)] hover:bg-[rgba(0,0,128,0.8)] text-white">
            検索
        </button>
    </form>

    <!-- 新規マニュアル作成ボタン -->
    <div>
        <a href="{{ route('manuals.create') }}"
           class="cursor-pointer inline-block text-center rounded-lg px-3 py-2 text-sm transition-colors duration-200 bg-[rgba(0,0,128,0.59)] hover:bg-[rgba(0,0,128,0.8)] text-white">
            新規マニュアル作成
        </a>
    </div>
</div>
@endsection
