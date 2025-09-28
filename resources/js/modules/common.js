/**
 * Common image deletion functionality
 * 画像削除機能の共通ロジック
 */
export default class CommonImageHandler {

  /**
   * 削除ボタンのイベントリスナーを初期化する
   *
   * @returns {void}
   */
  static initDeleteButtons() {
    // 削除ボタンのイベントリスナー
    document.addEventListener('click', (e) => {
      if (e.target.matches('[data-js="delete-image-btn"]')) {
        this.handleImageDelete(e);
      } else if (e.target.matches('[data-js="permanent-delete-btn"]')) {
        this.handlePermanentDelete(e);
      }
    });
  }

  /**
   * 画像削除/復元処理を実行する
   *
   * @param {Event} e
   * @returns {void}
   */
  static handleImageDelete(e) {
    const button = e.target;
    const container = button.closest('[data-js="upload-temporary"], [data-js="upload-multiple-temporary"]');
    const input = container.querySelector('input[type="hidden"]');
    // 操作するcss classを定義
    const deleteClass = ['bg-red-600', 'hover:bg-red-800', 'focus:ring-red-300', 'dark:focus:ring-red-800'];
    const cancelClass = ['bg-blue-600', 'focus:ring-blue-300', 'dark:focus:ring-blue-800'];

    // 現在の状態を確認
    const isDeleted = button.textContent.trim() === '復元';

    if (isDeleted) {
      // 復元処理：削除状態から元に戻す
      if (input) {
        input.disabled = false;
      }

      // UIを元の状態に戻す
      button.textContent = '保留';
      button.disabled = false;
      button.classList.remove(...cancelClass);
      button.classList.add(...deleteClass);
    } else {
      // 削除処理
      if (input) {
        input.disabled = true;
      }

      // UIを削除状態にする
      button.textContent = '復元';
      button.disabled = false; // ボタンを有効にして再度クリック可能にする
      button.classList.remove(...deleteClass);
      button.classList.add(...cancelClass);
    }
  }

  /**
   * 画像削除処理を実行する
   *
   * @param {Event} e
   * @returns {void}
   */
  static handlePermanentDelete(e) {
    const button = e.target;
    const container = button.closest('[data-js="upload-temporary"], [data-js="upload-multiple-temporary"]');

    if (confirm('この画像を完全に削除しますか？この操作は取り消せません。')) {
      // 要素を完全に削除
      container.remove();
    }
  }
}