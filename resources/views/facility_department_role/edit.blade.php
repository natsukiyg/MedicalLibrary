@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white">
    <!-- タイトル -->
    <h1 class="text-2xl font-bold mb-6">施設 / 部署 / 権限</h1>

    <!-- フォーム (POST or PUT などメソッドは必要に応じて変更) -->
    <!-- 例: /facility-department-role へのPOSTで更新すると仮定 -->
    <form action="{{ route('facility-department-role.update') }}" method="POST" class="space-y-6">
        @csrf
        {{-- @method('PUT') ← もし既存データを更新するならPUTやPATCHを使う --}}

        <!-- 所属施設 -->
        <div>
            <label for="hospital" class="block mb-1 text-gray-700 font-semibold">
                所属施設:
            </label>
            <input type="text" name="hospital" id="hospital"
                   class="border border-gray-300 rounded w-1/2 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                   placeholder="例: ○○病院" />
        </div>

        <!-- 所属部署 -->
        <div>
            <label for="department" class="block mb-1 text-gray-700 font-semibold">
                所属部署:
            </label>
            <input type="text" name="department" id="department"
                   class="border border-gray-300 rounded w-1/2 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                   placeholder="例: 手術室 / ○○病棟" />
        </div>

        <!-- 権限選択 -->
        <div>
            <label class="block mb-1 text-gray-700 font-semibold">
                権限:
            </label>
            <div class="flex items-center gap-6 mt-2">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="role" value="staff" class="mr-2 focus:ring-0" checked>
                    スタッフ
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="role" value="team_member" class="mr-2 focus:ring-0">
                    チームメンバー
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="role" value="admin" class="mr-2 focus:ring-0">
                    管理者
                </label>
            </div>
        </div>

        <!-- 登録ボタン -->
        <div>
            <button type="submit"
                    class="block rounded-lg cursor-pointer px-4 py-2 bg-[rgba(0,0,128,0.59)] hover:bg-[rgba(0,0,128,0.8)] transition-colors duration-200 text-white ">
                登録
            </button>
        </div>
    </form>
</div>
@endsection
