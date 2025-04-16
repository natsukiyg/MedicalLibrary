@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto p-4 bg-white rounded border border-medical-base/50">
        <h1 class="text-2xl text-medical-neutral font-bold mb-4">マニュアル削除の確認</h1>
        <p>本当にこのマニュアルを削除してもよろしいですか？</p>
        
        <form action="{{ route('manuals.destroy', $manual->id) }}" method="POST" class="mt-4">
            @csrf
            @method('DELETE')
            <div class="flex space-x-4">
                <button type="submit" class="px-4 py-2 bg-medical-neutral hover:bg-medical-neutral/50 text-white rounded">削除する</button>
                <a href="{{ route('manuals.show', $manual->id) }}" class="px-4 py-2 bg-medical-base/50 text-medical-neutral rounded">キャンセル</a>
            </div>
        </form>
    </div>
@endsection
