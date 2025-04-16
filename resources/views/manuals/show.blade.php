@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">

    <!-- パンくずリスト（診療科 > 分類 > 術式）-->
    <div class="flex items-center gap-2 mb-4">
        <a href="{{ route('manuals.specialty.index') }}"
           class="cursor-pointer inline-block px-4 py-2 rounded border border-medical-neutral bg-medical-accent/20 text-medical-neutral hover:bg-medical-accent hover:text-white transition-colors duration-200">
            {{ $specialty->name ?? '診療科名' }}
        </a>
        <span class="text-medical-neutral font-bold">&gt;</span>
        <a href="{{ route('manuals.classification.index', $specialty->id) }}"
            class="cursor-pointer inline-block px-4 py-2 rounded border border-medical-neutral bg-medical-accent/20 text-medical-neutral hover:bg-medical-accent hover:text-white transition-colors duration-200">
            {{ $classification->name ?? '分類名' }}
        </a>
        <span class="text-medical-neutral font-bold">&gt;</span>
        <a href="{{ route('manuals.procedure.index', [$specialty->id, $classification->id]) }}"
            class="cursor-pointer inline-block px-4 py-2 rounded border border-medical-neutral bg-medical-accent/20 text-medical-neutral hover:bg-medical-accent hover:text-white transition-colors duration-200">
            {{ $procedure->name ?? '術式名' }}
        </a>
    </div>

    <!-- マニュアルタイトル + 編集ボタン -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-medical-neutral">
            {{ $manual->title ?? 'マニュアルタイトル' }}
        </h1>

        <!-- 編集ボタン（権限がある場合に表示） -->
        @can('edit-manual', $manual)
            <a href="{{ route('manuals.edit', $manual->id) }}"
               class="cursor-pointer inline-block text-center px-4 py-2 rounded border border-medical-neutral bg-medical-neutral text-white hover:bg-medical-accent transition-colors duration-200">
                編集
            </a>
        @endcan
    </div>

    <!-- 閲覧専用ファイル（埋め込み表示） -->
    <div class="mb-6 bg-medical-base p-4 rounded">
    @php
        $files = json_decode($manual->files, true) ?? [];
    @endphp

    @if (!empty($files) && is_array($files))
            <h2 class="text-lg font-semibold text-medical-neutral mb-4">📄 マニュアルデータ</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($files as $file)
                    <div class="flex items-center justify-between bg-white shadow-md rounded-lg p-4 border border-medical-base-40">
                        <span class="text-medical-neutral font-medium">
                            📂  {{ $file['name'] ?? '不明なファイル' }}
                        </span>
                        <a href="{{ $file['url'] ?? '#' }}" target="_blank"
                           class="inline-block text-center px-4 py-2 rounded-lg border border-medical-neutral bg-medical-accent text-white hover:bg-medical-neutral transition-colors duration-200">
                            📄 参照
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-medical-neutral">このマニュアルにはファイルが登録されていません。</p>
        @endif
    </div>

    <!-- AI解析フォーム -->
    <div class="mt-8 p-4 bg-medical-base/20 border border-medical-neutral text-medical-neutral rounded-lg">
        <h2 class="text-lg font-bold text-medical-neutral mb-4">🧠 このマニュアルをAIで解析</h2>
        <form action="{{ route('manuals.analyze', ['manual' => $manual->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="file" name="file" accept=".docx,.xlsx"
                    class="block w-full px-4 py-2 border border-medical-neutral rounded focus:outline-none focus:ring-2 focus:ring-medical-accent"
                    required>
            </div>
            <button type="submit"
                class="px-4 py-2 bg-medical-accent text-white rounded hover:bg-medical-neutral transition-colors duration-200">
                📤 AIに送信して解析
            </button>
        </form>
        @if ($manual->aiAnalyses && $manual->aiAnalyses->count())
            <div class="mt-6 space-y-3">
                <h3 class="font-semibold text-medical-neutral">🧠 AIの解析履歴</h3>
                @foreach ($manual->aiAnalyses as $analysis)
                <div class="flex {{ $analysis->role === 'user' ? 'justify-end' : 'justify-start' }} relative">
                    <div class="relative max-w-[75%] p-3 my-1 rounded-lg text-sm leading-tight 
                                {{ $analysis->role === 'user' 
                                    ? 'bg-white text-medical-neutral border border-medical-neutral' 
                                    : 'bg-medical-neutral/10 text-medical-neutral border border-medical-accent/40' }}">
                        {!! preg_replace(
                            '/(https?:\/\/[\w\-\.\/\?\&\=\#\%\:]+)/i',
                            '<a href="$1" class="underline text-medical-neutral hover:text-medical-neutral" target="_blank" rel="noopener noreferrer">$1</a>',
                            nl2br(e(trim($analysis->content)))
                        ) !!}
                        <!-- 削除ボタンを吹き出しの右下に移動 -->
                        <form action="{{ route('ai-analyses.destroy', $analysis->id) }}" method="POST" 
                              onsubmit="return confirm('この解析履歴を削除しますか？');"
                              class="absolute bottom-1 {{ $analysis->role === 'user' ? 'right-1' : 'right-1' }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs transition-transform transform hover:scale-125">🗑️</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
        
        <!-- チャット入力フォーム -->
        <form action="{{ route('manuals.analyze.followup', ['manual' => $manual->id]) }}" method="POST" class="mt-6 space-y-3">
            @csrf
            <label for="followup" class="block text-sm font-medium text-medical-neutral">🔁 AIへの追加質問を入力：</label>
            <textarea id="followup" name="followup" rows="3" required
                class="w-full p-2 border border-medical-base rounded focus:outline-none focus:ring-2 focus:ring-medical-accent"></textarea>
            <button type="submit"
                class="px-4 py-2 bg-medical-accent text-white rounded hover:bg-medical-neutral transition-colors duration-200">
                🚀 質問を送信
            </button>
        </form>
    </div>
</div>
@endsection