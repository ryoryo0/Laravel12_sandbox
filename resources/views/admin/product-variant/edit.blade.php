<x-admin.app-layout>
  <div class="relative overflow-x-auto p-4 sm:ml-64">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-grey-800">在庫編集</h1>
        <p class="text-gray-700 dark:text-gray-400 mt-1">在庫情報を編集してください</p>
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
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">編集</span>
          </div>
        </li>
      </ol>
    </nav>

    <!-- Main Content Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
      <form method="POST" action="{{ route('admin.product-variant.update', $variant->id) }}" class="p-6">
        @csrf
        @method('PUT')

        <!-- 基本情報セクション -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            在庫情報
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <label for="product_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">商品 <span class="text-red-500">*</span></label>
              <select name="product_id" id="product_id" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('product_id') border-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" required>
                <option value="">商品を選択してください</option>
                @foreach($products as $id => $name)
                  <option value="{{ $id }}" @selected(old('product_id', $variant->product_id) == $id)>{{ $name }}</option>
                @endforeach
              </select>
              @error('product_id')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">カラー <span class="text-red-500">*</span></label>
              <input type="text" name="color" id="color" value="{{ old('color', $variant->color) }}" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('color') border-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="例：レッド" required>
              @error('color')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="size" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">サイズ <span class="text-red-500">*</span></label>
              <input type="text" name="size" id="size" value="{{ old('size', $variant->size) }}" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('size') border-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="例：M" required>
              @error('size')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">在庫数 <span class="text-red-500">*</span></label>
              <input type="number" name="stock" id="stock" value="{{ old('stock', $variant->stock) }}" min="0" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('stock') border-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="0" required>
              @error('stock')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">価格 <span class="text-red-500">*</span></label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">¥</span>
                <input type="number" name="price" id="price" value="{{ old('price', $variant->price) }}" min="0" step="0.01" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 pl-8 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('price') border-red-500 focus:border-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="0.00" required>
              </div>
              @error('price')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
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
            更新する
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
    });
  </script>
  @endpush
</x-admin.app-layout>
