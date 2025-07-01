<x-admin.app-layout>
<div class="relative overflow-x-auto p-4 sm:ml-64 ">
  <!-- 検索フォーム -->
  <form class=" x-auto mb-6">
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
  <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
                {{ $product->category_id }}
              </td>
              <td class="px-6 py-4">
                {{ $product->is_public }}
              </td>
              <td class="px-6 py-4">
                {{ $product->created_at }}
              </td>
          </tr>
        @endforeach
      </tbody>
  </table>
</div>
</x-admin.app-layout>