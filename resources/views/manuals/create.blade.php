@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <h1 class="text-2xl font-bold text-medical-neutral mb-6">新規マニュアル作成</h1>

    <form action="{{ route('manuals.store') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <strong>エラーがあります：</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 診療科選択 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-medical-neutral">診療科</label>
            <select name="specialty_id" id="specialty" required
                    class="mt-1 p-2 border border-gray-300 rounded w-full text-medical-neutral">
                <option value="">選択してください</option>
                @foreach ($specialties as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- 分類選択 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-medical-neutral">分類</label>
            <select name="classification_id" id="classification" required disabled
                    class="mt-1 p-2 border border-gray-300 rounded w-full text-medical-neutral">
                <option value="">診療科を選択してください</option>
            </select>
        </div>

        <!-- 術式選択 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-medical-neutral">術式</label>
            <select name="procedure_id" id="procedure" required disabled
                    class="mt-1 p-2 border border-gray-300 rounded w-full text-medical-neutral">
                <option value="">分類を選択してください</option>
            </select>
        </div>

        <!-- タイトル入力 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-medical-neutral">タイトル</label>
            <input type="text" name="title" value="{{ old('title') }}" required
                   class="mt-1 p-2 border border-gray-300 rounded w-full text-medical-neutral">
        </div>

        <!-- 編集可能なファイルのURL -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-medical-neutral">編集可能ファイルURL</label>
            <div id="editable-file-container"></div>
            <button type="button" id="add-editable-file"
                    class="bg-medical-accent/70 hover:bg-medical-accent text-white px-3 py-1 rounded mt-2">+ ファイル追加</button>
        </div>

        <button type="submit"
                class="bg-medical-accent hover:bg-medical-neutral text-white px-4 py-2 rounded mt-4">
            保存する
        </button>
    </form>
</div>

<script>
    const baseUrl = "{{ url('') }}";

    // 診療科選択時に分類を取得
    document.getElementById('specialty').addEventListener('change', function() {
        let specialtyId = this.value;
        let classificationSelect = document.getElementById('classification');
        let procedureSelect = document.getElementById('procedure');

        classificationSelect.innerHTML = '<option value="">ロード中...</option>';
        classificationSelect.disabled = true;
        procedureSelect.innerHTML = '<option value="">分類を選択してください</option>';
        procedureSelect.disabled = true;

        if (specialtyId) {
            fetch(`${baseUrl}/classifications/${specialtyId}`)
                .then(response => response.json())
                .then(data => {
                    classificationSelect.innerHTML = '<option value="">選択してください</option>';
                    data.forEach(classification => {
                        classificationSelect.innerHTML += `<option value="${classification.id}">${classification.name}</option>`;
                    });
                    classificationSelect.disabled = false;
                });
        }
    });

    // 分類選択時に術式を取得
    document.getElementById('classification').addEventListener('change', function() {
        let classificationId = this.value;
        let procedureSelect = document.getElementById('procedure');

        procedureSelect.innerHTML = '<option value="">ロード中...</option>';
        procedureSelect.disabled = true;

        if (classificationId) {
            fetch(`${baseUrl}/procedures/${classificationId}`)
                .then(response => response.json())
                .then(data => {
                    procedureSelect.innerHTML = '<option value="">選択してください</option>';
                    data.forEach(procedure => {
                        procedureSelect.innerHTML += `<option value="${procedure.id}">${procedure.name}</option>`;
                    });
                    procedureSelect.disabled = false;
                });
        }
    });

    // 術式選択時に該当procedure_idのマニュアルタイトルを取得し、自動入力
    document.getElementById('procedure').addEventListener('change', function () {
        const procedureId = this.value;
        if (!procedureId) return;

        fetch(`${baseUrl}/manuals/title-by-procedure/${procedureId}`)
            .then(response => response.json())
            .then(data => {
                const titleInput = document.querySelector('input[name="title"]');
                if (titleInput) {
                    titleInput.value = ''; // 先にクリアしておく
                    if (data && data.title) {
                        titleInput.value = data.title;
                    }
                }
            })
            .catch(error => {
                console.error('タイトル取得エラー:', error);
            });
    });

    // ファイル追加機能
    function addFileInput(containerId) {
        const container = document.getElementById(containerId);
        const index = container.children.length;

        const newInput = document.createElement('div');
        newInput.classList.add('mb-4', 'p-2', 'border', 'border-gray-200', 'rounded');

        newInput.innerHTML = `
            <label class="block text-sm font-medium text-gray-700">ファイル名</label>
            <input type="text" name="editable_files[${index}][name]" placeholder="ファイル名を入力"
                   class="mt-1 p-2 border border-gray-300 rounded w-full mb-2">

            <label class="block text-sm font-medium text-gray-700">編集用URL</label>
            <input type="text" name="editable_files[${index}][url]" placeholder="編集用URLを入力"
                   class="mt-1 p-2 border border-gray-300 rounded w-full mb-2">

            <label class="block text-sm font-medium text-gray-700">閲覧用URL</label>
            <input type="text" name="editable_files[${index}][view_url]" placeholder="閲覧用URLを入力"
                   class="mt-1 p-2 border border-gray-300 rounded w-full">

            <button type="button" class="remove-file bg-medical-neutral hover:bg-medical-neutral/50 text-white px-2 py-1 rounded mt-2">✕</button>
        `;

        container.appendChild(newInput);
    }

    document.getElementById('add-editable-file').addEventListener('click', function () {
        addFileInput('editable-file-container');
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-file')) {
            e.target.parentElement.remove();
        }
    });
</script>

@endsection
