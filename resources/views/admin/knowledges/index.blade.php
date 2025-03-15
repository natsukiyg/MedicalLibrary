@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <h1 class="text-2xl font-bold mb-6">ナレッジ管理</h1>
    <table class="min-w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">タイトル</th>
                <th class="border border-gray-300 px-4 py-2">カテゴリ</th>
                <th class="border border-gray-300 px-4 py-2">作成者</th>
                <th class="border border-gray-300 px-4 py-2">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($knowledges as $knowledge)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $knowledge->id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $knowledge->title }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $knowledge->category->name ?? '' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $knowledge->creator->name ?? '' }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route('admin.knowledges.edit', $knowledge->id) }}"
                       class="text-blue-600 hover:underline">編集</a>
                    |
                    <a href="{{ route('admin.knowledges.delete', $knowledge->id) }}"
                       class="text-red-600 hover:underline">削除</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
