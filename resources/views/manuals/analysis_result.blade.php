@extends('layouts.app')

@section('content')
<div class="container">
    <h2>AIの解析結果</h2>
    <div class="bg-white p-4 rounded shadow">
        <pre>{{ $aiReply }}</pre>
    </div>
    <a href="{{ route('manuals.show', ['manual' => $manualId]) }}" class="btn btn-primary mt-3">マニュアル詳細に戻る</a>
</div>
@endsection