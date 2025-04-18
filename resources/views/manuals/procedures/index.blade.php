@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <!-- タイトルエリア -->
    <div class="mb-6">
        <h1 class="text-4xl text-medical-neutral font-bold mb-2">マニュアル</h1>
    </div>

    @php
        // コントローラ側で以下を取得している想定
        // $specialty = Specialty::findOrFail($specialtyId);
        // $classification = Classification::findOrFail($classificationId);
        // $procedures = $classification->procedures;

        // ルートパラメータ 'procedure' がある場合にアクティブ判定するなら:
        $activeProcedure = request()->route('procedure');
    @endphp

    <!-- パンくずリスト（診療科 > 分類）-->
    <div class="flex items-center gap-2 mb-4">
        <!-- 診療科へのリンク -->
        <a href="{{ route('manuals.specialty.index') }}"
           class="cursor-pointer inline-block px-4 py-2 rounded border border-medical-neutral bg-medical-accent/20 text-medical-neutral hover:bg-medical-accent hover:text-white transition-colors duration-200">
            {{ $specialty->name ?? '診療科名' }}
        </a>
        <span class="text-medical-neutral font-bold">&gt;</span>
        <!-- 分類へのリンク -->
        <a href="{{ route('manuals.classification.index', $specialty->id) }}"
           class="cursor-pointer inline-block px-4 py-2 rounded border border-medical-neutral bg-medical-accent/20 text-medical-neutral hover:bg-medical-accent hover:text-white transition-colors duration-200">
            {{ $classification->name ?? '分類名' }}
        </a>
    </div>

    <!-- 術式ボタン一覧 -->
    <div class="grid gap-4 mb-6" style="grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));">
        @foreach($procedures as $procedure)
            <a href="{{ isset($procedure->manual) ? route('manuals.show', $procedure->manual->id) : '#' }}"
               class="cursor-pointer text-center px-6 py-3 bg-white hover:bg-medical-accent/20 text-medical-neutral rounded border border-medical-neutral transition-colors duration-200">
                {{ $procedure->name }}
            </a>
        @endforeach
    </div>

    <!-- 新規マニュアル作成ボタン -->
    <div>
        <a href="{{ route('manuals.create') }}"
           class="cursor-pointer inline-block text-center rounded-lg px-3 py-2 text-sm transition-colors duration-200 bg-medical-accent hover:bg-medical-neutral text-white">
            新規マニュアル作成
        </a>
    </div>
</div>
@endsection
