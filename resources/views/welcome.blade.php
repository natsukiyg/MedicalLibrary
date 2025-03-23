<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medical Library</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-black font-sans antialiased">
    <div class="min-h-screen flex flex-col">

        <!-- ヘッダー -->
        @include('layouts.header')

        <!-- メイン -->
        <main class="flex flex-col items-center justify-center flex-grow px-4 text-center">
            <h2 class="text-3xl font-bold mt-8">ようこそ！<br>Medical Libraryへ</h2>
            <p class="text-base text-gray-700 mt-4">
                このアプリは看護師のための<br>ナレッジシェアアプリです。
            </p>

            <!-- グレー画像ブロック -->
            <div class="flex gap-6 mt-8">
                <img src="{{ asset('images/nurse1.jpg') }}" alt="トップ画像" class="h-40 object-contain rounded">
                <img src="{{ asset('images/opeNurse1.jpg') }}" alt="トップ画像" class="h-40 object-contain rounded">
                <img src="{{ asset('images/nurse2.jpg') }}" alt="トップ画像" class="h-40 object-contain rounded">
            </div>
        </main>
        @include('layouts.footer')
    </div>
</body>
</html>