<x-admin.app-layout>
<div class="relative overflow-x-auto p-4 sm:ml-64">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
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
                  {{ $product->id }}
                </th>
                <td class="px-6 py-4">
                  {{ $product->name }}
                </td>
                <td class="px-6 py-4">
                  {{ $product->description }}
                </td>
                <td class="px-6 py-4">
                  {{ $product->ulid }}
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