@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">

    @php
        // コントローラ側で $specialty, $classification, $procedure, $manual を渡している想定
        // 例: $manual->title => "胸腔鏡補助下肺切除術(VATS)"
    @endphp

    <!-- パンくずリスト（診療科 > 分類 > 術式）-->
    <div class="flex items-center gap-2 mb-4">
        <!-- 診療科へのリンク -->
        <a href="{{ route('manuals.specialty.index') }}"
           class="cursor-pointer inline-block px-4 py-2 rounded border border-black bg-[rgba(247,79,191,0.36)] text-black hover:bg-[rgba(247,79,191,0.5)] transition-colors duration-200">
            {{ $specialty->name ?? '診療科名' }}
        </a>
        <span class="text-black font-bold">&gt;</span>
        <!-- 分類へのリンク -->
        <a href="{{ route('manuals.classification.index', $specialty->id) }}"
           class="cursor-pointer inline-block px-4 py-2 rounded border border-black bg-[rgba(247,79,191,0.36)] text-black hover:bg-[rgba(247,79,191,0.5)] transition-colors duration-200">
            {{ $classification->name ?? '分類名' }}
        </a>
        <span class="text-black font-bold">&gt;</span>
        <!-- 術式へのリンク -->
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
               class="cursor-pointer inline-block text-center px-4 py-2 rounded border border-black bg-[rgba(0,0,128,0.59)] text-black hover:bg-[rgba(0,0,128,0.8)] transition-colors duration-200">
                編集
            </a>
        @endcan
    </div>

    <!-- マニュアル本体の内容 (Excelから引用) -->
    <!-- 例: iframeで埋め込み表示する場合 -->
    <div class="mb-6 bg-gray-100 p-4 rounded">
        <!-- サンプルとしてExcel OnlineのURLを埋め込む例 -->
        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=あなたのExcelまたはWordのURL"
                width="100%" height="400px"
                class="border border-gray-300">
        </iframe>
        <!-- または単純に $manual->content を表示する場合：
             {!! nl2br(e($manual->content)) !!} -->
    </div>

</div>
@endsection
