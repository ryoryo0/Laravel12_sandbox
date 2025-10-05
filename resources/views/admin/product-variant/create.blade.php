<x-admin.app-layout>
  <div class="relative overflow-x-auto p-4 sm:ml-64">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-grey-800">在庫新規登録</h1>
        <p class="text-gray-700 dark:text-gray-400 mt-1">新しい在庫情報を入力してください</p>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('admin.product-variant.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
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
            <a href="{{ route('admin.product-variant.index') }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">在庫管理</a>
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
      <form method="POST" action="{{ route('admin.product-variant.store') }}" class="p-6">
        @csrf

        <!-- 商品選択セクション -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            商品選択
          </h2>

          <div class="mb-6">
            <label for="product_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">商品 <span class="text-red-500">*</span></label>
            <select name="product_id" id="product_id" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('product_id') border-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" required>
              <option value="">商品を選択してください</option>
              @foreach($products as $id => $name)
                <option value="{{ $id }}" @selected(old('product_id') == $id)>{{ $name }}</option>
              @endforeach
            </select>
            @error('product_id')
              <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <!-- バリエーション情報セクション -->
        <div class="mb-8">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center">
              <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
              バリエーション情報
            </h2>
            <button type="button" id="add-variant" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300">
              <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              追加
            </button>
          </div>

          <div id="variants-container">
            <!-- 初期バリエーション -->
            <div class="variant-item bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300">バリエーション #1</h3>
                <button type="button" class="remove-variant text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 hidden">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">カラー <span class="text-red-500">*</span></label>
                  <input type="text" name="variants[0][color]" value="{{ old('variants.0.color') }}" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="例：レッド" required>
                </div>

                <div>
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">サイズ <span class="text-red-500">*</span></label>
                  <input type="text" name="variants[0][size]" value="{{ old('variants.0.size') }}" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="例：M" required>
                </div>

                <div>
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">在庫数 <span class="text-red-500">*</span></label>
                  <input type="number" name="variants[0][stock]" value="{{ old('variants.0.stock', 0) }}" min="0" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="0" required>
                </div>

                <div>
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">価格 <span class="text-red-500">*</span></label>
                  <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">¥</span>
                    <input type="number" name="variants[0][price]" value="{{ old('variants.0.price', 0) }}" min="0" step="0.01" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 pl-8 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="0.00" required>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
          <a href="{{ route('admin.product-variant.index') }}" class="px-6 py-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
            キャンセル
          </a>
          <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            登録する
          </button>
        </div>
      </form>
    </div>
  </div>

  @push('scripts')
  <script>
    $(document).ready(function() {
      $('#product_id').select2({
        placeholder: '商品を選択してください',
        allowClear: true,
        width: '100%'
      });

      let variantIndex = 1;

      // バリエーション追加
      $('#add-variant').on('click', function() {
        const variantHtml = `
          <div class="variant-item bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300">バリエーション #${variantIndex + 1}</h3>
              <button type="button" class="remove-variant text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">カラー <span class="text-red-500">*</span></label>
                <input type="text" name="variants[${variantIndex}][color]" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="例：レッド" required>
              </div>
              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">サイズ <span class="text-red-500">*</span></label>
                <input type="text" name="variants[${variantIndex}][size]" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="例：M" required>
              </div>
              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">在庫数 <span class="text-red-500">*</span></label>
                <input type="number" name="variants[${variantIndex}][stock]" value="0" min="0" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="0" required>
              </div>
              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">価格 <span class="text-red-500">*</span></label>
                <div class="relative">
                  <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">¥</span>
                  <input type="number" name="variants[${variantIndex}][price]" value="0" min="0" step="0.01" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 pl-8 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="0.00" required>
                </div>
              </div>
            </div>
          </div>
        `;

        $('#variants-container').append(variantHtml);
        variantIndex++;
        updateRemoveButtons();
      });

      // バリエーション削除
      $(document).on('click', '.remove-variant', function() {
        $(this).closest('.variant-item').remove();
        updateVariantNumbers();
        updateRemoveButtons();
      });

      // 削除ボタンの表示/非表示を更新
      function updateRemoveButtons() {
        const variantCount = $('.variant-item').length;
        if (variantCount > 1) {
          $('.remove-variant').removeClass('hidden');
        } else {
          $('.remove-variant').addClass('hidden');
        }
      }

      // バリエーション番号を更新
      function updateVariantNumbers() {
        $('.variant-item').each(function(index) {
          $(this).find('h3').text('バリエーション #' + (index + 1));
        });
      }
    });
  </script>
  @endpush
</x-admin.app-layout>
