@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <!-- ページタイトル -->
    <h1 class="text-4xl font-bold mb-6">登録情報</h1>

    <!-- 更新成功メッセージ -->
    @if (session('status'))
        <div class="mb-4 text-green-600 font-semibold">
            {{ session('status') }}
        </div>
    @endif

    <!-- プロフィール編集フォーム -->
    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <!-- 氏名 -->
        <div>
            <label for="name" class="block mb-1 text-gray-700 font-semibold">氏名:</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name', $user->name) }}"
                   class="border border-gray-300 rounded w-1/2 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- 性別 -->
        <div>
            <label class="block mb-1 text-gray-700 font-semibold">性別:</label>
            @php
                $gender = old('gender', $user->gender ?? 'non');
            @endphp
            <div class="flex items-center gap-4 mt-2">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="gender" value="male" class="mr-2 focus:ring-0" @if($gender === 'male') checked @endif>
                    男性
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="gender" value="female" class="mr-2 focus:ring-0" @if($gender === 'female') checked @endif>
                    女性
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="gender" value="non" class="mr-2 focus:ring-0" @if($gender === 'non') checked @endif>
                    回答しない
                </label>
            </div>
            @error('gender')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- 生年月日 -->
        <div>
            <label for="birthday" class="block mb-1 text-gray-700 font-semibold">生年月日:</label>
            <input type="date" id="birthday" name="birthday"
                   value="{{ old('birthday', $user->birthday) }}"
                   class="border border-gray-300 rounded w-1/2 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
            @error('birthday')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- 住所 -->
        <div>
            <label for="address" class="block mb-1 text-gray-700 font-semibold">都道府県:</label>
            <select name="address" id="address"
                    class="border border-gray-300 rounded w-1/2 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                <option value="">選択してください</option>
                <option value="北海道" {{ old('address', $user->address ?? '') == '北海道' ? 'selected' : '' }}>北海道</option>
                <option value="青森県" {{ old('address', $user->address ?? '') == '青森県' ? 'selected' : '' }}>青森県</option>
                <option value="岩手県" {{ old('address', $user->address ?? '') == '岩手県' ? 'selected' : '' }}>岩手県</option>
                <option value="宮城県" {{ old('address', $user->address ?? '') == '宮城県' ? 'selected' : '' }}>宮城県</option>
                <option value="秋田県" {{ old('address', $user->address ?? '') == '秋田県' ? 'selected' : '' }}>秋田県</option>
                <option value="山形県" {{ old('address', $user->address ?? '') == '山形県' ? 'selected' : '' }}>山形県</option>
                <option value="福島県" {{ old('address', $user->address ?? '') == '福島県' ? 'selected' : '' }}>福島県</option>
                <option value="茨城県" {{ old('address', $user->address ?? '') == '茨城県' ? 'selected' : '' }}>茨城県</option>
                <option value="栃木県" {{ old('address', $user->address ?? '') == '栃木県' ? 'selected' : '' }}>栃木県</option>
                <option value="群馬県" {{ old('address', $user->address ?? '') == '群馬県' ? 'selected' : '' }}>群馬県</option>
                <option value="埼玉県" {{ old('address', $user->address ?? '') == '埼玉県' ? 'selected' : '' }}>埼玉県</option>
                <option value="千葉県" {{ old('address', $user->address ?? '') == '千葉県' ? 'selected' : '' }}>千葉県</option>
                <option value="東京都" {{ old('address', $user->address ?? '') == '東京都' ? 'selected' : '' }}>東京都</option>
                <option value="神奈川県" {{ old('address', $user->address ?? '') == '神奈川県' ? 'selected' : '' }}>神奈川県</option>
                <option value="新潟県" {{ old('address', $user->address ?? '') == '新潟県' ? 'selected' : '' }}>新潟県</option>
                <option value="富山県" {{ old('address', $user->address ?? '') == '富山県' ? 'selected' : '' }}>富山県</option>
                <option value="石川県" {{ old('address', $user->address ?? '') == '石川県' ? 'selected' : '' }}>石川県</option>
                <option value="福井県" {{ old('address', $user->address ?? '') == '福井県' ? 'selected' : '' }}>福井県</option>
                <option value="山梨県" {{ old('address', $user->address ?? '') == '山梨県' ? 'selected' : '' }}>山梨県</option>
                <option value="長野県" {{ old('address', $user->address ?? '') == '長野県' ? 'selected' : '' }}>長野県</option>
                <option value="岐阜県" {{ old('address', $user->address ?? '') == '岐阜県' ? 'selected' : '' }}>岐阜県</option>
                <option value="静岡県" {{ old('address', $user->address ?? '') == '静岡県' ? 'selected' : '' }}>静岡県</option>
                <option value="愛知県" {{ old('address', $user->address ?? '') == '愛知県' ? 'selected' : '' }}>愛知県</option>
                <option value="三重県" {{ old('address', $user->address ?? '') == '三重県' ? 'selected' : '' }}>三重県</option>
                <option value="滋賀県" {{ old('address', $user->address ?? '') == '滋賀県' ? 'selected' : '' }}>滋賀県</option>
                <option value="京都府" {{ old('address', $user->address ?? '') == '京都府' ? 'selected' : '' }}>京都府</option>
                <option value="大阪府" {{ old('address', $user->address ?? '') == '大阪府' ? 'selected' : '' }}>大阪府</option>
                <option value="兵庫県" {{ old('address', $user->address ?? '') == '兵庫県' ? 'selected' : '' }}>兵庫県</option>
                <option value="奈良県" {{ old('address', $user->address ?? '') == '奈良県' ? 'selected' : '' }}>奈良県</option>
                <option value="和歌山県" {{ old('address', $user->address ?? '') == '和歌山県' ? 'selected' : '' }}>和歌山県</option>
                <option value="鳥取県" {{ old('address', $user->address ?? '') == '鳥取県' ? 'selected' : '' }}>鳥取県</option>
                <option value="島根県" {{ old('address', $user->address ?? '') == '島根県' ? 'selected' : '' }}>島根県</option>
                <option value="岡山県" {{ old('address', $user->address ?? '') == '岡山県' ? 'selected' : '' }}>岡山県</option>
                <option value="広島県" {{ old('address', $user->address ?? '') == '広島県' ? 'selected' : '' }}>広島県</option>
                <option value="山口県" {{ old('address', $user->address ?? '') == '山口県' ? 'selected' : '' }}>山口県</option>
                <option value="徳島県" {{ old('address', $user->address ?? '') == '徳島県' ? 'selected' : '' }}>徳島県</option>
                <option value="香川県" {{ old('address', $user->address ?? '') == '香川県' ? 'selected' : '' }}>香川県</option>
                <option value="愛媛県" {{ old('address', $user->address ?? '') == '愛媛県' ? 'selected' : '' }}>愛媛県</option>
                <option value="高知県" {{ old('address', $user->address ?? '') == '高知県' ? 'selected' : '' }}>高知県</option>
                <option value="福岡県" {{ old('address', $user->address ?? '') == '福岡県' ? 'selected' : '' }}>福岡県</option>
                <option value="佐賀県" {{ old('address', $user->address ?? '') == '佐賀県' ? 'selected' : '' }}>佐賀県</option>
                <option value="長崎県" {{ old('address', $user->address ?? '') == '長崎県' ? 'selected' : '' }}>長崎県</option>
                <option value="熊本県" {{ old('address', $user->address ?? '') == '熊本県' ? 'selected' : '' }}>熊本県</option>
                <option value="大分県" {{ old('address', $user->address ?? '') == '大分県' ? 'selected' : '' }}>大分県</option>
                <option value="宮崎県" {{ old('address', $user->address ?? '') == '宮崎県' ? 'selected' : '' }}>宮崎県</option>
                <option value="鹿児島県" {{ old('address', $user->address ?? '') == '鹿児島県' ? 'selected' : '' }}>鹿児島県</option>
                <option value="沖縄県" {{ old('address', $user->address ?? '') == '沖縄県' ? 'selected' : '' }}>沖縄県</option>
            </select>
            @error('address')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- メールアドレス -->
        <div>
            <label for="email" class="block mb-1 text-gray-700 font-semibold">メールアドレス:</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email', $user->email) }}"
                   class="border border-gray-300 rounded w-1/2 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                   required>
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- パスワード -->
        <div>
            <label for="password" class="block mb-1 text-gray-700 font-semibold">パスワード:</label>
            <input type="password" id="password" name="password"
                   class="border border-gray-300 rounded w-1/2 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                   placeholder="変更する場合のみ入力してください">
            @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror

        <!-- 更新ボタン -->
        <div class="mt-6">
            <button type="submit"
                    class="block rounded-lg cursor-pointer px-4 py-2 bg-[rgba(0,0,128,0.59)] hover:bg-[rgba(0,0,128,0.8)] transition-colors duration-200 text-white">
                更新
            </button>
        </div>
    </form>
</div>
@endsection
