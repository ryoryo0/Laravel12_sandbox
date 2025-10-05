<x-admin.app-layout>
  <div class="relative overflow-x-auto p-4 sm:ml-64">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-grey-800">イベント編集</h1>
        <p class="text-gray-700 dark:text-gray-400 mt-1">イベント情報を編集してください</p>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('admin.event.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
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
            <a href="{{ route('admin.event.index') }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">イベント管理</a>
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
      <form method="POST" action="{{ route('admin.event.update', $event->id) }}" class="p-6">
        @csrf
        @method('PUT')

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
              <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">イベント名 <span class="text-red-500">*</span></label>
              <input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="イベント名を入力してください" required>
            </div>

            <div class="md:col-span-2">
              <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">説明文</label>
              <textarea name="description" id="description" rows="3" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="イベントの説明を入力してください">{{ old('description', $event->description) }}</textarea>
            </div>

            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">割引タイプ <span class="text-red-500">*</span></label>
              <div class="flex gap-4">
                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                  <input type="radio" name="discount_type" value="rate" @checked(old('discount_type', $event->discount_type) === 'rate') class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" required>
                  <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">割引率（%）</span>
                </label>
                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                  <input type="radio" name="discount_type" value="amount" @checked(old('discount_type', $event->discount_type) === 'amount') class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" required>
                  <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">割引額（¥）</span>
                </label>
              </div>
            </div>

            <div></div>

            <div>
              <label for="discount_rate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">割引率（%）</label>
              <input type="number" name="discount_rate" id="discount_rate" value="{{ old('discount_rate', $event->discount_rate) }}" min="0" max="100" step="0.01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="0.00">
            </div>

            <div>
              <label for="discount_amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">割引額（¥）</label>
              <input type="number" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', $event->discount_amount) }}" min="0" step="0.01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="0">
            </div>

            <div>
              <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">開始日時 <span class="text-red-500">*</span></label>
              <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
            </div>

            <div>
              <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">終了日時 <span class="text-red-500">*</span></label>
              <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
            </div>

            <div class="md:col-span-2">
              <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $event->is_active)) class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">有効</span>
              </label>
            </div>
          </div>
        </div>

        <!-- 対象商品セクション -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            対象商品
          </h2>
          <select name="product_ids[]" id="product_ids" multiple class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            @foreach($products as $id => $name)
              <option value="{{ $id }}" @selected(in_array($id, old('product_ids', $event->products->pluck('id')->toArray())))>{{ $name }}</option>
            @endforeach
          </select>
          <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Ctrl/Cmdキーを押しながら複数選択できます</p>
        </div>

        <!-- 送信ボタン -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
          <a href="{{ route('admin.event.index') }}" class="px-6 py-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 transition-colors">
            キャンセル
          </a>
          <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            更新する
          </button>
        </div>
      </form>
    </div>
  </div>
</x-admin.app-layout>
