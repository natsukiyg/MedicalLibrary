@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <!-- タイトルエリア -->
    <div class="mb-6">
        <h1 class="text-4xl font-bold mb-2">マニュアル</h1>
    </div>

    <!-- 選択された診療科の情報表示 -->
    <!-- 例: 呼吸器外科 というボタンをピンク表示したいなら -->
    @php
        // ルートパラメータ 'classification' が設定されている場合、それを active とする
        // (もし classificationIndex($specialtyId) のみなら特にactive判定は不要です)
        $activeClassification = request()->route('classification');

        // $specialty はコントローラ側で渡している想定
        // 例: $specialty = Specialty::findOrFail($specialtyId);
    @endphp

    <!-- パンくずリスト（診療科）-->
    <div class="flex items-center gap-2 mb-4">
        <!-- 診療科へのリンク -->
        <a href="{{ route('manuals.specialty.index') }}"
           class="cursor-pointer inline-block px-4 py-2 rounded border border-black bg-[rgba(247,79,191,0.36)] text-black hover:bg-[rgba(247,79,191,0.5)] transition-colors duration-200">
            {{ $specialty->name ?? '診療科名' }}
        </a>
    </div>
    
    <!-- 分類ボタン一覧 -->
    <!-- 例: 正中開胸手術、胸腔鏡下手術、ロボット支援下手術、縦隔腫瘍下手術、気管切開術、その他... -->
    <div class="grid gap-4 mb-6" style="grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));">
        @foreach($classifications as $classification)
            @php
                $isActive = ($activeClassification == $classification->id);
                $defaultClasses = 'cursor-pointer block px-6 py-3 rounded transition-colors duration-200 text-black border border-black';
                $activeClasses = 'bg-[rgba(247,79,191,0.36)]';
                $inactiveClasses = 'bg-white hover:bg-[rgba(247,79,191,0.5)]';
                $classes = $isActive ? "$defaultClasses $activeClasses" : "$defaultClasses $inactiveClasses";
            @endphp
            <a href="{{ route('manuals.procedure.index', [$specialty->id, $classification->id]) }}"
               class="{{ $classes }} text-center">
                {{ $classification->name }}
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