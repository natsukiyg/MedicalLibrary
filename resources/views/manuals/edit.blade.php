@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <h1 class="text-2xl font-bold text-medical-neutral mb-6">マニュアル編集</h1>

    <form action="{{ route('manuals.update', $manual->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- タイトル編集 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">タイトル</label>
            <input type="text" name="title" value="{{ old('title', $manual->title) }}" class="mt-1 p-2 border border-gray-300 text-medical-neutral rounded w-full">
        </div>

        <!-- ファイルの閲覧・編集用URL -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">ファイルの閲覧・編集用URL</label>

            <div id="file-input-container">
                @php
                    $editableFiles = json_decode($manual->editable_files, true) ?? [];
                @endphp

                @foreach ($editableFiles as $index => $file)
                <div class="file-entry flex items-center gap-2 mb-2" data-index="{{ $index }}">
                    <div class="file-view flex justify-between items-center bg-white shadow-md rounded-lg p-4 border border-gray-200 w-full">
                        <span class="text-medical-neutral font-medium flex-1">
                            📂  {{ $file['name'] ?? '不明なファイル' }}
                        </span>
                            <a href="{{ $file['url'] ?? '#' }}" target="_blank"
                               class="file-link text-center rounded px-3 py-1.5 text-sm text-medical-neutral bg-medical-accent/30 hover:bg-medical-accent/10 transition-colors duration-200"
                               data-index="{{ $index }}">
                               ✎ 編集
                            </a>

                            <button type="button" class="edit-file bg-medical-accent hover:bg-medical-accent/50 text-white px-3 py-1 rounded"
                                    data-index="{{ $index }}">
                                 URL
                            </button>

                            <button type="button" class="remove-file bg-medical-neutral hover:bg-medical-neutral/50 text-white px-3 py-1 rounded">
                                ✕ 削除
                            </button>
                        </div>
                    </div>

                    <div class="file-edit hidden w-full">
                        <input type="hidden" name="editable_files[{{ $index }}][name]" value="{{ $file['name'] ?? '' }}">
                        <input type="text" name="editable_files[{{ $index }}][url]" value="{{ $file['url'] ?? '' }}" 
                               class="mt-1 p-2 border border-gray-300 rounded w-full">
                        @php
                            $matchingViewUrl = '';
                            foreach ($manual->files_array ?? [] as $viewFile) {
                                if (($viewFile['name'] ?? '') === ($file['name'] ?? '')) {
                                    $matchingViewUrl = $viewFile['url'] ?? '';
                                    break;
                                }
                            }
                        @endphp
                        <input type="text" name="editable_files[{{ $index }}][view_url]" value="{{ $matchingViewUrl }}" 
                               class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="閲覧用URL（任意）">
                        <div class="flex gap-2 mt-2">
                            <button type="button" class="cancel-edit bg-gray-400 hover:bg-gray-500 text-white px-2 py-1 rounded">✖ キャンセル</button>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="mt-6 flex flex-col items-start gap-2 text-sm">
                    <button type="button" id="add-file" class="bg-medical-accent hover:bg-medical-neutral text-white px-2 py-1 rounded">
                        + 追加
                    </button>

                    <button type="submit" class="bg-medical-accent hover:bg-medical-neutral text-white px-2 py-1 rounded">
                        保存する
                    </button>

                    <a href="{{ route('manuals.show', $manual->id) }}" class="text-center bg-medical-base/30 hover:bg-medical-base text-medical-neutral px-2 py-1 rounded">
                        ← 戻る
                    </a>
                </div>
            </div>
        </div>
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
            const isNew = entry.querySelector('input[name$="[url]"]').value === '';
            if (isNew) {
                entry.remove();
            } else {
                let viewContainer = entry.querySelector('.file-view');
                let editContainer = entry.querySelector('.file-edit');

                viewContainer.classList.remove('hidden'); // 元のファイル名+編集ボタンを復活
                editContainer.classList.add('hidden'); // 入力フィールドを非表示
            }
        }

        // 「✕ 削除」ボタンが押された場合（リストから削除）
        if (e.target.classList.contains('remove-file')) {
            entry.remove();
        }
    });

    // 新しいURLを追加するボタン
    document.getElementById('add-file').addEventListener('click', function() {
        const container = document.getElementById('file-input-container');
        const index = document.querySelectorAll('.file-entry').length; // 新しい index を決定

        const wrapper = document.createElement('div');
        wrapper.classList.add('file-entry', 'flex', 'flex-col', 'gap-2', 'mb-2');
        wrapper.setAttribute('data-index', index);

        wrapper.innerHTML = `
            <div class="file-view hidden flex items-center justify-between bg-white shadow-md rounded-lg p-4 border border-gray-200 w-full">
                <span class="text-medical-neutral font-medium truncate">
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

            <div class="file-edit w-full">
                <input type="text" name="editable_files[${index}][name]" value=""
                       class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="ファイル名">
                <input type="text" name="editable_files[${index}][url]" value=""
                       class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="編集用URL">
                <input type="text" name="editable_files[${index}][view_url]" value=""
                       class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="閲覧用URL">
                <div class="flex gap-2 mt-2">
                    <button type="button" class="cancel-edit bg-gray-400 hover:bg-gray-500 text-white px-2 py-1 rounded">✖ キャンセル</button>
                </div>
            </div>
        `;

        container.appendChild(wrapper);
    });
</script>

@endsection
