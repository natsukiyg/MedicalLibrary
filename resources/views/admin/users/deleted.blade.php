@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <h1 class="text-2xl font-bold mb-6">削除ユーザーリスト</h1>
    <table class="min-w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">氏名</th>
                <th class="border border-gray-300 px-4 py-2">メールアドレス</th>
                <th class="border border-gray-300 px-4 py-2">削除日時</th>
                <th class="border border-gray-300 px-4 py-2">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->deleted_at }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <!-- 例: 復元ボタン -->
                    <a href="{{ route('admin.users.restore', $user->id) }}"
                       class="text-blue-600 hover:underline">復元する</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
