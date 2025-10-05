<x-admin.app-layout>
  <div class="relative overflow-x-auto p-4 sm:ml-64">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-800">イベント詳細</h1>
        <p class="text-gray-700 dark:text-gray-400 mt-1">「{{ $event->name }}」の詳細情報を表示しています</p>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('admin.event.edit', $event->id) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
          <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          編集
        </a>
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
            <span class="ms-1 text-sm font-medium text-gray-800 md:ms-2 dark:text-gray-400">詳細</span>
          </div>
        </li>
      </ol>
    </nav>

    <!-- Event details -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
      <div class="p-6">

      <!-- Basic Information -->
      <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
          <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          基本情報
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">イベント名</label>
            <p class="text-gray-900 dark:text-white text-lg">{{ $event->name }}</p>
          </div>
          @if($event->description)
          <div class="md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">説明</label>
            <p class="text-gray-900 dark:text-white">{{ $event->description }}</p>
          </div>
          @endif
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">割引タイプ</label>
            <p class="text-gray-900 dark:text-white">
              @if($event->discount_type === 'rate')
                割引率（%）
              @else
                割引額（¥）
              @endif
            </p>
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">割引内容</label>
            <p class="text-gray-900 dark:text-white text-lg font-semibold">
              @if($event->discount_type === 'rate')
                {{ $event->discount_rate }}%OFF
              @else
                ¥{{ number_format($event->discount_amount) }}OFF
              @endif
            </p>
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">開始日時</label>
            <p class="text-gray-900 dark:text-white">{{ $event->start_date->format('Y年m月d日 H:i') }}</p>
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">終了日時</label>
            <p class="text-gray-900 dark:text-white">{{ $event->end_date->format('Y年m月d日 H:i') }}</p>
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">状態</label>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
              {{ $event->is_active ? '有効' : '無効' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Thumbnail Image -->
      @if($event->image)
      <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
          <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          サムネイル画像
        </h2>
        <div class="max-w-md">
          <img src="{{ asset('storage/' . $event->image->file_path) }}" alt="{{ $event->image->original_filename }}" class="w-full h-auto rounded-lg border border-gray-200 dark:border-gray-700">
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $event->image->original_filename }}</p>
        </div>
      </div>
      @endif

      <!-- Target Products -->
      @if($event->products->count() > 0)
      <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
          <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
          対象商品
        </h2>
        <div class="flex flex-wrap gap-2">
          @foreach($event->products as $product)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
              {{ $product->name }}
            </span>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Meta Information -->
      <div class="border-t pt-6 dark:border-gray-600">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
          <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          メタ情報
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
          <div>
            <label class="block mb-1 font-medium text-gray-900 dark:text-white">作成日時</label>
            <p class="text-gray-600 dark:text-gray-400">{{ $event->created_at->format('Y年m月d日 H:i') }}</p>
          </div>
          <div>
            <label class="block mb-1 font-medium text-gray-900 dark:text-white">更新日時</label>
            <p class="text-gray-600 dark:text-gray-400">{{ $event->updated_at->format('Y年m月d日 H:i') }}</p>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</x-admin.app-layout>
