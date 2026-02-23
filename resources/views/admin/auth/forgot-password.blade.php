<x-admin.guest-layout>
    <h2 class="text-2xl font-bold text-gray-800 mb-4">パスワードをお忘れですか？</h2>

    <p class="mb-4 text-sm text-gray-600">
        メールアドレスを入力してください。パスワード再設定用のリンクをお送りします。
    </p>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.password.email') }}">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                required autofocus
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('admin.login') }}"
                class="text-sm text-indigo-600 hover:text-indigo-900 underline">
                ログインに戻る
            </a>

            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                再設定メールを送信
            </button>
        </div>
    </form>
</x-admin.guest-layout>
