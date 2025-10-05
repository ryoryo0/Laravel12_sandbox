<x-admin.app-layout>
  <div class="relative overflow-x-auto p-4 sm:ml-64">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-grey-800">商品新規登録</h1>
        <p class="text-gray-700 dark:text-gray-400 mt-1">新しい商品の情報を入力してください</p>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('admin.product.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
          一覧に戻る
        </a>
      </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
        <li class="inline-flex items-center">
          <a href="{{ route('admin.home') }}" class="inline-flex items-center text-sm font-medium text-gray-900 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
            </svg>
            ホーム
          </a>
        </li>
        <li>
          <div class="flex items-center">
            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <a href="{{ route('admin.product.index') }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">商品管理</a>
          </div>
        </li>
        <li>
          <div class="flex items-center">
            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">新規登録</span>
          </div>
        </li>
      </ol>
    </nav>   
    <!-- Main Content Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
      <form method="POST" action="{{ route('admin.product.store') }}" class="p-6">
        @csrf

        <!-- Old値復元用のhidden input -->
        @if(old('thumbnail'))
          <input type="hidden" id="old-thumbnail" value="{{ old('thumbnail') }}">
        @endif
        @if(old('other_thumbnail'))
          @foreach(old('other_thumbnail') as $ulid)
            @if($ulid)
              <input type="hidden" class="old-other-thumbnail" value="{{ $ulid }}">
            @endif
          @endforeach
        @endif

        <!-- 基本情報セクション -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            基本情報
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">商品名 <span class="text-red-500">*</span></label>
              <input type="text" name="name" id="name" value="{{ old('name') }}" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('name') border-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="商品名を入力してください" required>
              @error('name')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="md:col-span-2">
              <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">商品説明 <span class="text-red-500">*</span></label>
              <textarea name="description" id="description" rows="3" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('description') border-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="商品の説明を入力してください" required>{{ old('description') }}</textarea>
              @error('description')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">商品コード <span class="text-red-500">*</span></label>
              <input type="text" name="code" id="code" value="{{ old('code') }}" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('code') border-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="例：PROD-001" required>
              @error('code')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>
        <!-- 画像セクション -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            商品画像
          </h2>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- サムネイル画像 -->
            <div>
              <label class="block mb-3 text-sm font-medium text-gray-900 dark:text-white">メイン画像（サムネイル）</label>
              <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                  <input class="hidden" id="single-file_input" type="file" data-js="upload-temporary-input">
                  <label for="single-file_input" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800 transition-colors cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    商品画像を選択
                  </label>
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">推奨サイズ: 800x600px以上</p>
                <!-- アップロード済み画像表示エリア -->
                <div id="js-uploaded-temporary-list" class="mt-4">
                  <!-- 隠しテンプレート -->
                  <div data-js="upload-temporary" style="display: none;" class="border border-gray-200 rounded-lg p-4 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    <div class="flex items-center space-x-4">
                      <img class="w-16 h-16 object-cover rounded-lg" src="" alt="">
                      <div class="flex-grow">
                        <p class="text-sm font-medium text-gray-900 dark:text-white"></p>
                        <input type="hidden" name="thumbnail" value="">
                        <input type="hidden" id="old-thumbnail" value="{{ old('thumbnail') }}">
                      </div>
                      <div class="flex flex-col gap-2">
                        <button type="button" data-js="delete-image-btn" class="text-white bg-red-700 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-3 py-1">
                          削除
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
      <div>
        <!-- その他の画像 -->
        <div>
          <label class="block mb-3 text-sm font-medium text-gray-900 dark:text-white">その他の画像 <span class="text-sm text-gray-500">(最大4枚)</span></label>
          <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
              <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            
            <div class="items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
              <input class="hidden" id="mulch-file_input" type="file" data-js="upload-multiple-temporary-input" multiple>
              <label for="mulch-file_input" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg focus:ring-4 focus:ring-green-300 focus:outline-none dark:focus:ring-green-800 transition-colors cursor-pointer">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              追加画像を選択
              </label>
            </div>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">複数選択可能・JPG, PNG対応</p>
            <!-- アップロード済み複数画像表示エリア -->
            <div id="js-uploaded-multiple-temporary-list" class="mt-4 space-y-4">
              <!-- 隠しテンプレート -->
              <div data-js="upload-multiple-temporary" style="display: none;" class="border border-gray-200 rounded-lg p-4 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                <div class="flex items-center space-x-4">
                  <img class="w-16 h-16 object-cover rounded-lg" src="" alt="">
                  <div class="flex-grow">
                    <p class="text-sm font-medium text-gray-900 dark:text-white"></p>
                    <input type="hidden" name="other_thumbnail[]" value="">
                  </div>
                  <div class="flex flex-col gap-2">
                    <button type="button" data-js="permanent-delete-btn" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-3 py-1">
                      保留
                    </button>
                    <button type="button" data-js="delete-image-btn" class="text-white bg-red-700 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-3 py-1">
                      削除
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
      </div>
      </div>
      <div>

        <!-- カテゴリー選択 -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            カテゴリー <span class="text-red-500">*</span>
          </h2>
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($categories as $key => $value)
              <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                <input type="checkbox" name="category_ids[]" value="{{ $key }}" @checked(is_array(old('category_ids')) && in_array($key, old('category_ids'))) class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $value }}</span>
              </label>
            @endforeach
          </div>
          @error('category_ids')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <!-- 公開設定 -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            公開設定
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-900 dark:text-white mb-3">公開状態 <span class="text-red-500">*</span></label>
              <div class="space-y-3">
                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                  <input type="radio" name="is_public" value="1" @checked(old('is_public', '0') == '1') class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                  <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">公開</span>
                  <span class="ml-auto text-xs text-green-600">サイトに表示されます</span>
                </label>
                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                  <input type="radio" name="is_public" value="0" @checked(old('is_public', '0') == '0') class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                  <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">非公開</span>
                  <span class="ml-auto text-xs text-gray-500">下書き状態</span>
                </label>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-900 dark:text-white mb-3">特別設定</label>
              <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                <input type="checkbox" name="is_pick_up" value="1" @checked(old('is_pick_up')) class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">おすすめ商品</span>
                <span class="ml-auto text-xs text-blue-600">注目表示</span>
              </label>
              <input type="hidden" name="is_pick_up" value="0">
            </div>
          </div>
        </div>

        <!-- 詳細内容 -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            詳細内容
          </h2>
          <div class="border border-gray-200 rounded-lg bg-white">
            <div id="editor" class="min-h-[200px]"></div>
          </div>
          <input type="hidden" value="{{ old('detail_json', '') }}" id="detail_json" name="detail_json">
          <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">商品の詳細な説明や特徴を記載してください</p>
        </div>

        <!-- 送信ボタン -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
          <a href="{{ route('admin.product.index') }}" class="px-6 py-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 transition-colors">
            キャンセル
          </a>
          <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            商品を登録
          </button>
        </div>
      </form>
    </div>
  </div>
  @push('scripts')
    @vite(['resources/js/pages/product/create.js'])
  @endpush
</x-admin.app-layout>