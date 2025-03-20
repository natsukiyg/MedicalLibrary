@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <h1 class="text-2xl font-bold text-black mb-6">マニュアル編集</h1>

    <form action="{{ route('manuals.update', $manual->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- タイトル編集 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">タイトル</label>
            <input type="text" name="title" value="{{ old('title', $manual->title) }}" class="mt-1 p-2 border border-gray-300 rounded w-full">
        </div>

        <!-- 編集可能なファイルURL -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">編集可能なファイルURL</label>

            <div id="file-input-container">
                @php
                    $editableFiles = json_decode($manual->editable_files, true) ?? [];
                @endphp

                @foreach ($editableFiles as $index => $file)
                <div class="file-entry flex items-center gap-2 mb-2" data-index="{{ $index }}">
                 <!-- 通常表示（ファイル名 + 編集ボタン + 削除ボタン） -->
                    <div class="file-view flex justify-between items-center bg-white shadow-md rounded-lg p-4 border border-gray-200 w-full">
                    <!-- 左側（ファイル名） -->
                        <span class="text-black font-medium flex-1">
                            📂  {{ $file['name'] ?? '不明なファイル' }}
                        </span>

                    <!-- 右側（ボタンたち） -->
                        <div class="flex gap-4">
                            <a href="{{ $file['url'] ?? '#' }}" target="_blank"
                               class="file-link text-blue-500 underline hover:text-blue-700 truncate"
                               data-index="{{ $index }}">
                                編集
                            </a>

                            <button type="button" class="edit-file bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
                                    data-index="{{ $index }}">
                                ✎ URL
                            </button>

                            <button type="button" class="remove-file bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                ✕ 削除
                            </button>
                        </div>
                
                        <!-- 編集モード（URL入力 + キャンセル） -->
                        <div class="file-edit hidden w-full">
                            <input type="text" name="editable_files[]" value="{{ $file['url'] }}" 
                                   class="mt-1 p-2 border border-gray-300 rounded w-full">
                            <div class="flex gap-2 mt-2">
                                <button type="button" class="cancel-edit bg-gray-400 hover:bg-gray-500 text-white px-2 py-1 rounded">✖ キャンセル</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 追加ボタン -->
            <button type="button" id="add-file" class="bg-[rgba(0,0,128,0.59)] hover:bg-[rgba(0,0,128,0.8)] text-white px-3 py-1 rounded mt-2">+ 追加</button>
        </div>

        <button type="submit" class="bg-[rgba(0,0,128,0.59)] hover:bg-[rgba(0,0,128,0.8)] text-white px-4 py-2 rounded mt-4">
            保存する
        </button>
    </form>
</div>

<!-- JavaScriptで編集・追加・削除機能 -->
<script>
    document.addEventListener('click', function(e) {
        let entry = e.target.closest('.file-entry');

        // 「✎ URL」ボタンが押された場合（編集モード）
        if (e.target.classList.contains('edit-file')) {
            let viewContainer = entry.querySelector('.file-view');
            let editContainer = entry.querySelector('.file-edit');

            viewContainer.classList.add('hidden'); // ファイル名＋編集ボタンを非表示
            editContainer.classList.remove('hidden'); // URL入力フィールドを表示
            editContainer.querySelector('input').focus(); // 自動フォーカス
        }

        // 「✖ キャンセル」ボタンが押された場合（元に戻す）
        if (e.target.classList.contains('cancel-edit')) {
            let viewContainer = entry.querySelector('.file-view');
            let editContainer = entry.querySelector('.file-edit');

            viewContainer.classList.remove('hidden'); // 元のファイル名+編集ボタンを復活
            editContainer.classList.add('hidden'); // 入力フィールドを非表示
        }

        // 「✕ 削除」ボタンが押された場合（リストから削除）
        if (e.target.classList.contains('remove-file')) {
            entry.remove();
        }
    });

    // 新しいURLを追加するボタン
    document.getElementById('add-file').addEventListener('click', function() {
        let container = document.getElementById('file-input-container');
        let index = document.querySelectorAll('.file-entry').length; // 新しい index を決定
        let newInput = document.createElement('div');
        newInput.classList.add('file-entry', 'flex', 'items-center', 'gap-2', 'mb-2');
        newInput.setAttribute('data-index', index);
        newInput.innerHTML = `
            <div class="file-view flex items-center justify-between bg-white shadow-md rounded-lg p-4 border border-gray-200 w-full">
                <span class="text-black font-medium truncate">
                    📂 新しいファイル
                </span>
                <button type="button" class="edit-file bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded"
                        data-index="${index}">
                    ✎ URL
                </button>
                <button type="button" class="remove-file bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                    ✕ 削除
                </button>
            </div>

            <div class="file-edit hidden w-full">
                <input type="text" name="editable_files[]" value="" 
                       class="mt-1 p-2 border border-gray-300 rounded w-full">
                <div class="flex gap-2 mt-2">
                    <button type="button" class="cancel-edit bg-gray-400 hover:bg-gray-500 text-white px-2 py-1 rounded">✖ キャンセル</button>
                </div>
            </div>
        `;
        container.appendChild(newInput);
    });
</script>

@endsection
