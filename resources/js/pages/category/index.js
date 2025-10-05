// カテゴリー管理 - 非同期CRUD処理

document.addEventListener('DOMContentLoaded', () => {
  // CSRF トークン取得
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // メッセージ表示関数
  function showMessage(type, message) {
    const messageElement = document.getElementById(`${type}-message`);
    const messageText = document.getElementById(`${type}-message-text`);
    messageText.textContent = message;
    messageElement.classList.remove('hidden');

    // 5秒後に自動で非表示
    setTimeout(() => {
      messageElement.classList.add('hidden');
    }, 5000);
  }

  // エラーメッセージクリア
  function clearErrors() {
    document.querySelectorAll('[id$="-error"]').forEach(el => {
      el.classList.add('hidden');
      el.textContent = '';
    });
  }

  // テーブル行を更新
  function updateTableRow(category) {
    const row = document.querySelector(`tr[data-category-id="${category.id}"]`);
    if (row) {
      row.querySelector('.text-gray-900').textContent = category.name;
    }
  }

  // テーブル行を追加
  function addTableRow(category) {
    const tbody = document.querySelector('#categories-table tbody');
    const newRow = document.createElement('tr');
    newRow.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';
    newRow.setAttribute('data-category-id', category.id);

    const createdAt = new Date(category.created_at);
    const formattedDate = `${createdAt.getFullYear()}/${String(createdAt.getMonth() + 1).padStart(2, '0')}/${String(createdAt.getDate()).padStart(2, '0')} ${String(createdAt.getHours()).padStart(2, '0')}:${String(createdAt.getMinutes()).padStart(2, '0')}`;

    newRow.innerHTML = `
      <td class="px-6 py-4">
        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${category.name}</div>
      </td>
      <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">${formattedDate}</td>
      <td class="px-6 py-4 text-right text-sm font-medium">
        <div class="flex items-center justify-end space-x-2">
          <button type="button" data-action="edit" data-category-id="${category.id}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
          </button>
          <button type="button" data-action="delete" data-category-id="${category.id}" data-category-name="${category.name}" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
          </button>
        </div>
      </td>
    `;

    tbody.insertBefore(newRow, tbody.firstChild);
  }

  // テーブル行を削除
  function removeTableRow(categoryId) {
    const row = document.querySelector(`tr[data-category-id="${categoryId}"]`);
    if (row) {
      row.remove();
    }
  }

  // カテゴリー作成処理
  const createForm = document.getElementById('create-form');
  createForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();

    const formData = new FormData(createForm);
    const data = Object.fromEntries(formData);

    try {
      const response = await fetch('/admin/category/store', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (response.ok && result.success) {
        // フォームをリセット
        createForm.reset();

        // テーブルに行を追加
        addTableRow(result.category);

        // 成功メッセージ表示
        showMessage('success', result.message);
      } else {
        // エラー表示
        if (result.errors) {
          Object.keys(result.errors).forEach(key => {
            const errorElement = document.getElementById(`create-${key}-error`);
            if (errorElement) {
              errorElement.textContent = result.errors[key][0];
              errorElement.classList.remove('hidden');
            }
          });
        } else {
          showMessage('error', result.message || 'カテゴリーの作成に失敗しました');
        }
      }
    } catch (error) {
      console.error('Error:', error);
      showMessage('error', 'カテゴリーの作成に失敗しました');
    }
  });

  // 編集ボタンクリック時の処理
  document.addEventListener('click', async (e) => {
    const editButton = e.target.closest('[data-action="edit"]');
    if (editButton) {
      const categoryId = editButton.dataset.categoryId;

      try {
        const response = await fetch(`/admin/category/show/${categoryId}`, {
          headers: {
            'X-CSRF-TOKEN': csrfToken,
          },
        });

        const result = await response.json();

        if (response.ok && result.success) {
          // フォームに値を設定
          document.getElementById('edit-category-id').value = result.category.id;
          document.getElementById('edit-name').value = result.category.name;

          // モーダルを開く
          const button = document.getElementById('edit-modal-trigger');
          if (button) button.click();
        } else {
          showMessage('error', result.message || 'カテゴリー情報の取得に失敗しました');
        }
      } catch (error) {
        console.error('Error:', error);
        showMessage('error', 'カテゴリー情報の取得に失敗しました');
      }
    }
  });

  // カテゴリー更新処理
  const editForm = document.getElementById('edit-form');
  editForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();

    const categoryId = document.getElementById('edit-category-id').value;
    const formData = new FormData(editForm);
    const data = Object.fromEntries(formData);

    try {
      const response = await fetch(`/admin/category/update/${categoryId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (response.ok && result.success) {
        // テーブルの行を更新
        updateTableRow(result.category);

        // 成功メッセージ表示
        showMessage('success', result.message);
      } else {
        // エラー表示
        if (result.errors) {
          Object.keys(result.errors).forEach(key => {
            const errorElement = document.getElementById(`edit-${key}-error`);
            if (errorElement) {
              errorElement.textContent = result.errors[key][0];
              errorElement.classList.remove('hidden');
            }
          });
        } else {
          showMessage('error', result.message || 'カテゴリーの更新に失敗しました');
        }
      }
    } catch (error) {
      console.error('Error:', error);
      showMessage('error', 'カテゴリーの更新に失敗しました');
    }
  });

  // 削除ボタンクリック時の処理
  document.addEventListener('click', (e) => {
    const deleteButton = e.target.closest('[data-action="delete"]');
    if (deleteButton) {
      const categoryId = deleteButton.dataset.categoryId;
      const categoryName = deleteButton.dataset.categoryName;

      // モーダルに情報を設定
      document.getElementById('delete-category-id').value = categoryId;
      document.getElementById('delete-category-name').textContent = categoryName;

      // モーダルを開く
      const button = document.getElementById('delete-modal-trigger');
      if (button) button.click();
    }
  });

  // カテゴリー削除処理
  const deleteForm = document.getElementById('delete-form');
  deleteForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const categoryId = document.getElementById('delete-category-id').value;

    try {
      const response = await fetch(`/admin/category/destroy/${categoryId}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
      });

      const result = await response.json();

      if (response.ok && result.success) {
        // テーブルから行を削除
        removeTableRow(categoryId);

        // 成功メッセージ表示
        showMessage('success', result.message);
      } else {
        // エラーメッセージ表示
        showMessage('error', result.message || 'カテゴリーの削除に失敗しました');
      }
    } catch (error) {
      console.error('Error:', error);
      showMessage('error', 'カテゴリーの削除に失敗しました');
    }
  });
});
