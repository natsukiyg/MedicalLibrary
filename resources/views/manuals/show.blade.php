@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <!-- タイトル -->
        <h1 class="text-2xl font-bold mb-4">マニュアル</h1>

        <!-- パンくずリスト：診療科、分類、術式 -->
        <div class="flex items-center gap-2 mb-4">
            <!-- 診療科に戻る -->
            <a href="{{ route('manuals.specialty.index') }}"
               class="px-2 py-1 bg-gray-200 text-black rounded hover:bg-gray-300">
                {{ $specialty->name ?? '診療科' }}
            </a>
            <!-- 分類に戻る -->
            <a href="{{ route('manuals.classification.index', $specialty->id ?? 0) }}"
               class="px-2 py-1 bg-gray-200 text-black rounded hover:bg-gray-300">
                {{ $classification->name ?? '分類' }}
            </a>
            <!-- 術式に戻る -->
            <a href="{{ route('manuals.procedure.index', [$specialty->id ?? 0, $classification->id ?? 0]) }}"
               class="px-2 py-1 bg-gray-200 text-black rounded hover:bg-gray-300">
                {{ $procedure->name ?? '術式' }}
            </a>
        </div>

        <!-- マニュアルのタイトル＋編集ボタン -->
        <div class="flex items-center gap-4 mb-4">
            <h2 class="text-xl font-semibold">
                {{ $manual->title ?? 'マニュアルタイトル' }}
            </h2>
            <!-- 編集ボタン（権限がある場合に表示） -->
            @can('edit-manual', $manual)
                <a href="{{ route('manuals.edit', $manual->id) }}"
                   class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                    編集
                </a>
            @endcan
        </div>

        <!-- ここに将来的に Excel/Wordのデータを埋め込み表示するイメージ -->
        <div class="mb-6">
            <!-- iframe でExcel OnlineやWord Onlineを埋め込む例（仮） -->
            <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=あなたのExcelまたはWordのURL"
                    width="100%"
                    height="500"
                    class="border border-gray-300">
            </iframe>
        </div>

        <!-- マニュアルの詳細テキストなど -->
        <div class="bg-white p-4 rounded shadow">
            {!! nl2br(e($manual->content ?? 'マニュアルの内容がここに表示されます')) !!}
        </div>
    </div>
@endsection
