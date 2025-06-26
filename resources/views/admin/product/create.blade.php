<x-admin.app-layout>
  <div class="relative overflow-x-auto p-4 sm:ml-64 ">
  <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-gray-900">商品新規登録</h1>
  <nav class="flex mb-6" style="justify-content: flex-end" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
      <li class="inline-flex items-center">
        <a href="#" class="inline-flex items-center text-sm font-medium text-gray-900 hover:text-blue-600 dark:text-gray-900">
          <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
          </svg>
          Home
        </a>
      </li>
      <li>
        <div class="flex items-center">
          <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
          </svg>
          <a href="#" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-900">Product</a>
        </div>
      </li>
    </ol>
  </nav>

    <form class="mx-auto">
      <div class="mb-4">
        <label for="base-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">名前</label>
        <input type="text" name="name" id="base-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-blue-500 dark:focus:border-blue-500">
      </div>
      <div class="mb-4">
        <label for="base-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">説明文</label>
        <input type="text" name="description" id="base-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-900 dark:focus:ring-blue-500 dark:focus:border-blue-500">
      </div>
      <div class="mb-4">
        <label for="base-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">商品コード</label>
        <input type="text" name="code" id="base-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
      </div>
      <div class="mb-4">
          <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900" for="file_input">サムネイル画像</label>
          <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file" data-js="img">
      </div>
      <div class="mb-4">
          <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900" for="file_input">その他商品画像</label>
          <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file" multiple>
      </div>

      <div class="flex mt-6 mb-6">
        @foreach ($categories as $key => $value)
          <div class="flex items-center me-4">
              <input id="{{ $value }}" type="checkbox" name="category_ids[]" value="{{ $key }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              <label for="{{ $value }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-900">{{ $value }}</label>
          </div>
        @endforeach
      </div>

      <div class="flex mt-6 mb-6">
        <div class="flex items-center me-4">
            <input id="public_true" type="radio" value="1" name="is_public" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="public_true" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-900">公開</label>
        </div>
        <div class="flex items-center me-4">
            <input id="public_false" type="radio" value="0" name="is_public" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="public_false" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-900">非公開</label>
        </div>
      </div>

      <div class="mt-6 mb-6">
        <div class="flex items-center me-4" id="editor"></div>
       <input type="hidden" value="" id="detail"> 
      </div>
    </form>
  </div>
  @push('scripts')
    @vite(['resources/js/pages/product/create.js'])
  @endpush
</x-admin.app-layout>