<x-admin.app-layout>
<div class="relative overflow-x-auto p-4 sm:ml-64 ">
  <div style="justify-content: space-between;" class="flex">
    <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-gray-900">
      商品一覧
    </h1>
    @if (session('success'))
    <div id="toast-success" class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
      <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
          </svg>
          <span class="sr-only">Check icon</span>
      </div>
      <div class="ms-3 text-sm font-normal">{{ session('success') }}</div>
      <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
          <span class="sr-only">Close</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
      </button>
    </div>
    @endif
  </div>
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
          <span class="ms-1 text-sm font-medium text-gray-700 md:ms-2 dark:text-gray-900">Product</span>
        </div>
      </li>
    </ol>
  </nav>
  <!-- 検索フォーム -->
  <form class="x-auto mt-6 mb-6">
    <div class="mb-4">
      <label for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">ID</label>
      <input type="text" id="id" name="id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
    </div>
    <div class="mb-4">
      <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">商品名</label>
      <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
    </div>
    <div class="mb-4">
      <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">紹介文</label>
      <input type="text" id="description" name="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
    </div>
    <div class="mb-4">
      <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">商品コード</label>
      <input type="text" id="code" name="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
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
    <div class="flex mt-6 mb-6">
      <div class="flex items-center me-4">
          <input id="pick_up_true" type="checkbox" value="1" name="is_pick_up" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="pick_up_true" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-900">おすすめを絞り込み</label>
      </div>
    </div>
    <div style="justify-content: space-between;" class="flex">
      <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">検索</button>
      <a href="{{ route('admin.product.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">新規作成</a>
    </div>
  </form>
  @if($errors->any())
    <div class="alert alert-danger">
        <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
    </div>
  @endif
  <!--  一括操作プルダウン -->
  <form class="max-w-sm  mb-6">
  <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">チェック項目を一括操作</label>
  <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @foreach($bulkActions as $key => $value)
    <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
  </select>
</form>
  <!-- 一覧 -->
  <div style="overflow-x: scroll;">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" style="width: 100%; border-collapse: collapse; white-space: nowrap;">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-6 py-3">
            </th>
            @foreach($headings as $heading)
            <th scope="col" class="px-6 py-3">
              {{ $heading }}
            </th>
            @endforeach
          </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
          <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
              <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                <div class="flex items-center mb-4">
                  <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
              </th>
              <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $product->id }}
              </th>
              <td class="px-6 py-4">
                {{ $product->name }}
              </td>
              <td class="px-6 py-4">
                {{ $product->description }}
              </td>
              <td class="px-6 py-4">
                {{ $product->code }}
              </td>
              <td class="px-6 py-4">
                @forelse($product->categories as $category)
                {{ $category->name }},
                @empty
                無し
                @endforelse
              </td>
              <td class="px-6 py-4">
                {{ $product->is_public }}
              </td>
              <td class="px-6 py-4">
                {{ $product->created_at }}
              </td>
              <td class="px-6 py-4">
                <div class="flex gap-2">
                  <a href="{{ route('admin.product.edit', $product->id) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">編集</a>
                  <form method="POST" action="{{ route('admin.product.destroy', $product->id) }}" onsubmit="return confirm('この商品を削除しますか？この操作は取り消せません。')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">削除</button>
                  </form>
                </div>
              </td>
          </tr>
        @endforeach
      </tbody>
  </table>
  </div>
</div>
</x-admin.app-layout>