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
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <!-- ヘッダー -->
        @include('layouts.header')

        <div class="min-h-screen flex">
            <!-- サイドバー (ログイン時のみ表示) -->
            @auth
                <aside class="w-64 bg-white dark:bg-gray-800">
                    @include('layouts.sidebar')
                </aside>
            @endauth

            <!-- メインコンテンツ -->
            <main class="flex-1">
                <!-- Breeze の @isset($header) 部分などが不要なら削除してOK -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <div class="p-4">
                    <!-- ページ固有の内容を表示 -->
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- フッター -->
        @include('layouts.footer')
    </body>
</html>
