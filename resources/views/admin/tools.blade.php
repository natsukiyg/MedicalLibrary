@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <!-- ページタイトル -->
    <h1 class="text-2xl font-bold mb-6">管理者ツール</h1>

    <!-- メニュー一覧 -->
    <div class="space-y-4">
        <!-- 登録ユーザーリスト -->
        <a href="{{ route('admin.users.index') }}"
           class="block text-center px-6 py-3 border border-black rounded bg-white text-black hover:bg-[rgba(247,79,191,0.36)] transition-colors duration-200">
            登録ユーザーリスト
        </a>

        <!-- 未承認ユーザーリスト -->
        <a href="{{ route('admin.users.pending') }}"
           class="block text-center px-6 py-3 border border-black rounded bg-white text-black hover:bg-[rgba(247,79,191,0.36)] transition-colors duration-200">
            未承認ユーザーリスト
        </a>

        <!-- 削除ユーザーリスト -->
        <a href="{{ route('admin.users.deleted') }}"
           class="block text-center px-6 py-3 border border-black rounded bg-white text-black hover:bg-[rgba(247,79,191,0.36)] transition-colors duration-200">
            削除ユーザーリスト
        </a>

        <!-- マニュアル管理 -->
        <a href="{{ route('admin.manuals.index') }}"
           class="block text-center px-6 py-3 border border-black rounded bg-white text-black hover:bg-[rgba(247,79,191,0.36)] transition-colors duration-200">
            マニュアル管理
        </a>

        <!-- ナレッジ管理 -->
        <a href="{{ route('admin.knowledges.index') }}"
           class="block text-center px-6 py-3 border border-black rounded bg-white text-black hover:bg-[rgba(247,79,191,0.36)] transition-colors duration-200">
            ナレッジ管理
        </a>
    </div>
</div>
@endsection
