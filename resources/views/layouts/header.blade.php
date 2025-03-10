<header class="bg-white dark:bg-gray-800 shadow p-4 flex justify-between items-center">
    <div class="flex flex-col">
        <!-- サブタイトル -->
        <span class="text-black dark:text-gray-200 text-sm">
            看護師のためのナレッジシェア
        </span>
        <!-- メインタイトル -->
        <span class="text-xl font-bold text-gray-800 dark:text-gray-100">
            Medical Library
        </span>
    </div>

    <div class="flex items-center gap-4">
        @guest
            <!-- 未ログイン時：ログインボタン -->
            <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                ログイン
            </a>
        @else
            <!-- ログイン時：アイコン丸＋ユーザー名＋ログアウトボタン -->
            <div class="w-8 h-8 bg-gray-300 rounded-full overflow-hidden flex items-center justify-center">
                <!-- ここにアイコン画像がある場合は <img> タグを入れる -->
                <!-- <img src="{{ Auth::user()->avatar_url }}" alt="User Icon" class="w-8 h-8" /> -->
            </div>
            <span class="text-gray-700 dark:text-gray-200">
                {{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-[rgba(0,0,128,0.59)] text-white rounded hover:bg-[rgba(0,0,128,0.8)]">
                    ログアウト
                </button>
            </form>
        @endguest
    </div>
</header>
