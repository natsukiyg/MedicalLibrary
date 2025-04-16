<x-app-layout>
    @section('content')
    <div class="bg-white flex justify-center items-center min-h-[calc(100vh-160px)]">
        <div class="w-full max-w-md bg-white p-6 rounded border border-medical-base">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('メールアドレス')" />
                    <x-text-input id="email" class="block mt-1 w-full text-medical-neutral"
                                  type="email"
                                  name="email"
                                  :value="old('email')"
                                  required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('パスワード')" />
                    <x-text-input id="password" class="block mt-1 w-full text-medical-neutral"
                                  type="password"
                                  name="password"
                                  required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                
                @if (Route::has('password.request'))
                    <div class="mt-2 text-right">
                        <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                            パスワードをお忘れですか？
                        </a>
                    </div>
                @endif

                <div class="mt-4">
                    <button type="submit" class="w-full bg-medical-accent hover:bg-medical-accent/50 text-white font-semibold py-2 px-4 rounded">
                        ログイン
                    </button>
                </div>
            </form>

            <!-- Social Login Buttons -->
<!--             <div class="mt-4">
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded mb-2">
                    <i class="fab fa-facebook mr-2"></i>Facebookでログイン
                </button>
                <button class="w-full bg-white border border-gray-300 hover:bg-gray-100 text-medical-neutral font-semibold py-2 px-4 rounded">
                    <i class="fab fa-google mr-2"></i>Googleでログイン
                </button>
            </div> -->

            <!-- Register Link -->
            <div class="mt-6 text-center text-sm text-gray-600">
                アカウントが未登録の方はこちら
            </div>
            <div class="mt-2 text-center">
                <a href="{{ route('register') }}" class="w-full block text-center bg-medical-neutral hover:bg-medical-neutral/50 text-white font-semibold py-2 px-4 rounded">
                    新規登録
                </a>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>
