<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Medical Library') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    />

    <!-- Scripts (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    <!-- 全体を縦にflexで分割 -->
    <div class="min-h-screen flex flex-col">
        <!-- ヘッダー -->
        @include('layouts.header')

        <!-- コンテンツ部分: サイドバー + メイン -->
        <div class="flex flex-1">
            <!-- サイドバー (ログイン時のみ表示) -->
            @auth
                <aside class="w-64 bg-white p-4">
                    @include('layouts.sidebar')
                </aside>
            @endauth

            <!-- メインコンテンツ -->
            <main class="flex-1 p-4">
                <!-- もしBreezeなどで $header を使いたい場合はここに表示 -->
                @isset($header)
                    <header class="bg-white mb-4">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- ページ固有の内容を表示 -->
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>

        <!-- フッター -->
        @include('layouts.footer')
    </div>
</body>
</html>
