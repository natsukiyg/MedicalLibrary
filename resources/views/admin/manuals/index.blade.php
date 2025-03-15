@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <h1 class="text-2xl font-bold mb-6">マニュアル管理</h1>
    <table class="min-w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">タイトル</th>
                <th class="border border-gray-300 px-4 py-2">診療科</th>
                <th class="border border-gray-300 px-4 py-2">分類</th>
                <th class="border border-gray-300 px-4 py-2">術式</th>
                <th class="border border-gray-300 px-4 py-2">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($manuals as $manual)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $manual->id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $manual->title }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $manual->specialty->name ?? '' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $manual->classification->name ?? '' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $manual->procedure->name ?? '' }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route('admin.manuals.edit', $manual->id) }}"
                       class="text-blue-600 hover:underline">編集</a>
                    |
                    <a href="{{ route('admin.manuals.delete', $manual->id) }}"
                       class="text-red-600 hover:underline">削除</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
