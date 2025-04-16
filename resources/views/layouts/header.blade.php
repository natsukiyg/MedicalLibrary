<header class="bg-white border-b border-medical-neutral/30 backdrop-blur-sm p-4 flex justify-between items-center">
    <div class="flex flex-col">
        <!-- サブタイトル -->
        <span class="text-medical-neutral dark:text-gray-200 text-sm">
            看護師のためのナレッジシェア
        </span>
        <!-- メインタイトル -->
        <span class="text-xl font-bold text-medical-neutral dark:text-gray-100">
            Medical Library
        </span>
    </div>

    <div class="flex items-center gap-4">
        @auth
            <!-- ログイン時：アイコン丸＋ユーザー名＋ログアウトボタン -->
            <div class="w-8 h-8 bg-gray-300 rounded-full overflow-hidden flex items-center justify-center">
                <!-- <img src="{{ Auth::user()->avatar_url }}" alt="User Icon" class="w-8 h-8" /> -->
            </div>
            <span class="text-medical-neutral dark:text-gray-200">
                {{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-medical-accent text-white rounded hover:bg-medical-neutral">
                    ログアウト
                </button>
            </form>
        @else
            <!-- 未ログイン時：ログインボタン -->
            <a href="{{ route('login') }}" class="px-4 py-2 bg-medical-accent text-white rounded hover:bg-medical-neutral">
                ログイン
            </a>
        @endauth
    </div>
</header>
