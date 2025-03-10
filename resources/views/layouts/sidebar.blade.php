<!-- resources/views/layouts/sidebar.blade.php -->
<nav class="flex flex-col h-full p-4" style="background-color: rgba(0, 0, 128, 0.3);">
    <!-- 登録情報エリア -->
    <div class="mb-6 text-black dark:text-gray-200 p-2">
        <p>登録施設：{{ $hospitalName ?? '〇〇病院' }}</p>
        <p>登録部署：{{ $departmentName ?? '〇〇' }}</p>
        <p>権限：{{ $user_role ?? '〇〇〇〇' }}</p>
        
        <!-- 施設/部署/権限変更ボタン -->
        <button class="block w-full rounded-lg px-4 py-2 mt-4 transition-colors duration-200 bg-[rgba(0,142,20,0.59)] hover:bg-[rgba(0,142,20,0.8)] text-white">
            施設/部署/権限変更
        </button>
        <!-- 登録情報変更ボタン -->
        <button class="block w-full rounded-lg px-4 py-2 mt-4 transition-colors duration-200 bg-[rgba(0,142,20,0.59)] hover:bg-[rgba(0,142,20,0.8)] text-white">
            登録情報変更
        </button>
    </div>

    <!-- 下部のボタン群 -->
    <div class="mt-auto pt-4 space-y-2 px-2">
        @can('operator')
            <a href="{{ route('operator.dashboard') }}"
               class="block w-full text-center rounded-lg px-4 py-2 transition-colors duration-200 bg-[rgba(0,0,128,0.59)] hover:bg-[rgba(0,0,128,0.8)] text-white">
                運営者ツール
            </a>
        @endcan

        @can('admin')
            <a href="{{ route('admin.dashboard') }}"
               class="block w-full text-center rounded-lg px-4 py-2 transition-colors duration-200 bg-[rgba(0,0,128,0.59)] hover:bg-[rgba(0,0,128,0.8)] text-white">
                管理者ツール
            </a>
        @endcan

        <a href="{{ route('dashboard') }}"
           class="block w-full text-center rounded-lg px-4 py-2 transition-colors duration-200 bg-[rgba(0,0,128,0.59)] hover:bg-[rgba(0,0,128,0.8)] text-white">
            ダッシュボード
        </a>
    </div>
</nav>
