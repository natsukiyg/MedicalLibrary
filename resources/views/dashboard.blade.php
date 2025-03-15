@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    @if(session('status'))
        <script>
            //ページがロードされた時にアラートを表示
            window.addEventListener('DOMContentLoaded', function(){
                alert('{{ session('status') }}');
            });
        </script>
    @endif
    
    <!-- ダッシュボード タイトル -->
    <h1 class="text-2xl font-bold mb-6">ダッシュボード</h1>

    <!-- ボタン一覧 -->
    <div class="space-y-4">
        <!-- マニュアル ボタン -->
        <a href="{{ route('manuals.specialty.index') }}"
           class="inline-block w-full max-w-xs text-left px-4 py-3 text-black border border-black rounded transition-colors duration-200 bg-white hover:bg-[rgba(247,79,191,0.36)]">
            <span class="text-xl">▶️ マニュアル</span>
        </a>

        <!-- ナレッジシェア ボタン -->
        <a href="{{ route('knowledges.index') }}"
           class="inline-block w-full max-w-xs text-left px-4 py-3 text-black border border-black rounded transition-colors duration-200 bg-white hover:bg-[rgba(247,79,191,0.36)]">
            <span class="text-xl">▶️ ナレッジシェア</span>
        </a>
    </div>
</div>
@endsection
