@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">

    <!-- パンくずリスト（診療科 > 分類 > 術式）-->
    <div class="flex items-center gap-2 mb-4">
        <a href="{{ route('manuals.specialty.index') }}"
           class="cursor-pointer inline-block px-4 py-2 rounded border border-black bg-[rgba(247,79,191,0.36)] text-black hover:bg-[rgba(247,79,191,0.5)] transition-colors duration-200">
            {{ $specialty->name ?? '診療科名' }}
        </a>
        <span class="text-black font-bold">&gt;</span>
        <a href="{{ route('manuals.classification.index', $specialty->id) }}"
           class="cursor-pointer inline-block px-4 py-2 rounded border border-black bg-[rgba(247,79,191,0.36)] text-black hover:bg-[rgba(247,79,191,0.5)] transition-colors duration-200">
            {{ $classification->name ?? '分類名' }}
        </a>
        <span class="text-black font-bold">&gt;</span>
        <a href="{{ route('manuals.procedure.index', [$specialty->id, $classification->id]) }}"
           class="cursor-pointer inline-block px-4 py-2 rounded border border-black bg-[rgba(247,79,191,0.36)] text-black hover:bg-[rgba(247,79,191,0.5)] transition-colors duration-200">
            {{ $procedure->name ?? '術式名' }}
        </a>
    </div>

    <!-- マニュアルタイトル + 編集ボタン -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-black">
            {{ $manual->title ?? 'マニュアルタイトル' }}
        </h1>

        <!-- 編集ボタン（権限がある場合に表示） -->
        @can('edit-manual', $manual)
            <a href="{{ route('manuals.edit', $manual->id) }}"
               class="cursor-pointer inline-block text-center px-4 py-2 rounded border border-black bg-[rgba(0,0,128,0.59)] text-white hover:bg-[rgba(0,0,128,0.8)] transition-colors duration-200">
                編集
            </a>
        @endcan
    </div>

    <!-- 閲覧専用ファイル（埋め込み表示） -->
    <div class="mb-6 bg-gray-100 p-4 rounded">
    @php
        $files = json_decode($manual->files, true) ?? [];
    @endphp

    @if (!empty($files) && is_array($files))
            <h2 class="text-lg font-semibold text-black mb-4">📄 マニュアルデータ</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($files as $file)
                    <div class="flex items-center justify-between bg-white shadow-md rounded-lg p-4 border border-gray-200">
                        <span class="text-black font-medium">
                            📂  {{ $file['name'] ?? '不明なファイル' }}
                        </span>
                        <a href="{{ $file['url'] ?? '#' }}" target="_blank"
                           class="inline-block text-center px-4 py-2 rounded-lg border border-black bg-[rgba(0,142,20,0.59)] text-white hover:bg-[rgba(0,142,20,0.8)] transition-colors duration-200">
                            📄 参照
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">このマニュアルにはファイルが登録されていません。</p>
        @endif
    </div>
</div>
@endsection