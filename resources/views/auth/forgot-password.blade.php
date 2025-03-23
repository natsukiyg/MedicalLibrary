<x-app-layout>
    @section('content')
    <div class="flex justify-center items-center min-h-[calc(100vh-160px)]">
        <div class="w-full max-w-md bg-white px-6 pt-6 pb-4 rounded shadow">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ __('パスワードをお忘れですか？ 登録済みのメールアドレスを入力してください。パスワード再設定用のリンクをお送りします。') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-2">
                    <x-input-label for="email" :value="__('メールアドレス')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full bg-[rgba(0,0,128,0.59)] hover:bg-[rgba(0,0,128,0.8)] text-white font-semibold py-2 px-4 rounded">
                        パスワード再設定リンクを送信
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endsection
</x-app-layout>
