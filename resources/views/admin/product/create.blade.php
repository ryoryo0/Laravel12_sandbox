<x-admin.app-layout>
  <div class="relative overflow-x-auto p-4 sm:ml-64 ">
  <div style="justify-content: space-between;" class="flex">
    <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-gray-900">
      商品新規登録
    </h1>
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
          <a href="#" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-900">Product</a>
        </div>
      </li>
    </ol>
</nav>   
    <form class="mx-auto" method="POST" action="{{ route('admin.product.store') }}">
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

      <div class="mb-4">
        <label for="base-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">名前</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" id="base-input" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:placeholder-gray-400 dark:text-gray-900 @error('name') border-red-500 focus:border-red-500 @else border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 @enderror">
        @error('name')
          <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
      </div>
      <div class="mb-4">
        <label for="base-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">説明文</label>
        <input type="text" name="description" value="{{ old('description', $product->description ?? '') }}" id="base-input" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:placeholder-gray-400 dark:text-gray-900 @error('description') border-red-500 focus:border-red-500 @else border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 @enderror">
        @error('description')
          <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
      </div>
      <div class="mb-4">
        <label for="base-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">商品コード</label>
        <input type="text" name="code" value="{{ old('code', $product->code ?? '') }}" id="base-input" class="bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:placeholder-gray-400 dark:text-gray-900 @error('code') border-red-500 focus:border-red-500 @else border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 @enderror">
        @error('code')
          <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
      </div>
      <!-- サムネイル画像 -->
      <div class="mb-6 mt-6">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900" for="file_input">サムネイル画像</label>
        <!-- Modal toggle -->
        <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
          画像を追加する
        </button>
        <!-- Main modal -->
        <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                             サムネイル画像
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="relative w-full overflow-y-scroll bg-white border border-gray-100 rounded-lg dark:bg-gray-700 dark:border-gray-600 h-96">
                      <ul id="js-uploaded-temporary-list">
                        <li class="border-b border-gray-100 dark:border-gray-600" data-js="upload-temporary" style="display: none;">
                          <div class="flex  w-full px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <img class="me-3 rounded-full w-11 h-11" src="" alt="Jese Leos Avatar">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400"></p>
                                <input type="hidden" name="thumbnail" value="{{ old('thumbnail', $product->thumbnail ?? '') }}">
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                      <input class="hidden block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:border-gray-600 dark:placeholder-gray-400" id="single-file_input" type="file" data-js="upload-temporary-input">
                      <label for="single-file_input" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        画像を追加する
                      </label>
                    </div>
                </div>
            </div>
        </div>
      <div>

      <!-- その他の画像 -->
      <div class="mb-6 mt-6">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900" for="file_input">その他の画像</label>
        <!-- Modal toggle -->
        <button data-modal-target="default-modal-multiple" data-modal-toggle="default-modal-multiple" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
          画像を追加する
        </button>
        <!-- Main modal -->
        <div id="default-modal-multiple" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            その他の画像
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal-multiple">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="relative w-full overflow-y-scroll bg-white border border-gray-100 rounded-lg dark:bg-gray-700 dark:border-gray-600 h-96">
                      <ul id="js-uploaded-multiple-temporary-list">
                        <li class="border-b border-gray-100 dark:border-gray-600" data-js="upload-multiple-temporary" style="display: none;">
                          <div class="flex  w-full px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <img class="me-3 w-11 h-11 round-full" src="" alt="Jese Leos Avatar">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400"></p>
                                <input type="hidden" name="other_thumbnail[]" value="" disabled>
                            </div>
                            <!-- NOTE::削除ボタンの実装について実装方法の後日検討が必要なことから一時コメントアウト -->
                            <!-- <button data-js="delete-temporary" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center" style="margin-left: auto;">
                                削除
                            </button> -->
                          </div>
                        </li>
                      </ul>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                      <input class="hidden block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:border-gray-600 dark:placeholder-gray-400" id="mulch-file_input" type="file" data-js="upload-multiple-temporary-inout" multiple>
                      <label for="mulch-file_input" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        画像を追加する
                      </label>
                    </div>
                </div>
            </div>
        </div>
      <div>

      <div class="flex mt-6 mb-6">
        @foreach ($categories as $key => $value)
          <div class="flex items-center me-4">
              <input id="{{ $value }}" @checked(is_array(old('category_ids')) && in_array($key, old('category_ids'))) type="checkbox" name="category_ids[]" value="{{ $key }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
              <label for="{{ $value }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-900" >{{ $value }}</label>
          </div>
        @endforeach
      </div>
      @error('category_ids')
          <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
      @enderror

      <div class="flex mt-6 mb-6">
        <div class="flex items-center me-4">
            <input id="public_true" type="radio" @checked(old('is_public', '0') == '1')  value="1" name="is_public" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
            <label for="public_true" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-900">公開</label>
        </div>
        <div class="flex items-center me-4">
            <input id="public_false" type="radio" @checked(old('is_public', '0') == '0')  value="0" name="is_public" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
            <label for="public_false" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-900">非公開</label>
        </div>
      </div>
      <div class="flex items-center">
          <input id="is_pick_up" type="hidden" value="0" name="is_pick_up" checked>
          <input id="is_pick_up" type="checkbox" value="1" name="is_pick_up" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
          <label for="is_pick_up" class="ms-2 text-sm font-medium text-gray-900">おすすめ</label>
      </div>
      <div class="mt-6 mb-6">
        <div class="flex items-center me-4" id="editor"></div>
        <input type="hidden" value="{{ old('detail_json', '') }}" id="detail_json" name="detail_json"> 
      </div>
      <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        保存
      </button>
    </form>
  </div>
  @push('scripts')
    @vite(['resources/js/pages/product/create.js'])
  @endpush
</x-admin.app-layout>