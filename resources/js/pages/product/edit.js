import initQuill from '../../libraries/quill';
import TemporaryImage from '../../modules/temporary-image';


/**
 * 商品編集の一時画像アップロードを管理するクラス
 * サーバーに画像をアップロードして、成功時にはUIの追加を実行する責務をもつ
 * 既存の画像データも読み込んで表示する
 *
 * @class EditManager
 */
class EditManager {
  /**
   * EditManager のインスタンスを生成し、初期化処理を行う
   * イベントリスナーを登録し、既存画像の読み込みを実行する
   */
  constructor() {
    this.init();
    this.loadExistingImages();
    this.initQuillEditor(); // quillの初期化（既存データ付き）
    this.loadOldImages();
  }


  /**
   * Quillエディタを初期化する
   *
   * @returns {Quill} Quillインスタンス
   */
  initQuillEditor() {
    // 画像アップロード処理を定義(一時画像アップロードモジュールのメソッド活用)
    const imageUploadHandler = async (image) => {
      return await TemporaryImage.upload(image, '/admin/temporary/upload');
    };

    // Quillエディタを初期化
    return initQuill('#editor', 'detail_json', imageUploadHandler);
  }


  /**
   * イベントリスナーの初期化を実行する
   *
   * @returns {void}
   */
  init () {
    // 単数アップロード
    document.querySelector('[data-js="upload-temporary-input"]')
    ?.addEventListener('change', (e) => this.handleSingleUpload(e));
    // 複数アップロード
    document.querySelector('[data-js="upload-multiple-temporary-inout"]')
    ?.addEventListener('change', (e) => this.handleMultipleUpload(e));
    // 削除ボタンのイベントリスナー
    this.initDeleteButtons();
  }


  /**
   * 既存の画像データを読み込んで表示する
   * old値が存在する場合はスキップ（重複表示を防ぐため）
   *
   * @returns {Promise<void>}
   */
  async loadExistingImages() {
    // old値が存在する場合は既存画像の読み込みをスキップ
    const oldThumbnail = document.getElementById('old-thumbnail');
    const oldOtherThumbnails = document.querySelectorAll('.old-other-thumbnail');

    const hasOldValues = (oldThumbnail && oldThumbnail.value) || oldOtherThumbnails.length > 0;

    if (hasOldValues) {
      console.log('old値が存在するため、既存画像の読み込みをスキップします');
      return;
    }

    await this.loadExistingThumbnail();
    await this.loadExistingOtherImages();
  }


  /**
   * 既存のサムネイル画像を読み込んで表示する
   *
   * @returns {Promise<void>}
   */
  async loadExistingThumbnail() {
    const thumbnailInput = document.querySelector('input[name="thumbnail"]');
    if (!thumbnailInput || !thumbnailInput.value) return;

    const ulid = thumbnailInput.value;

    try {
      const imageData = await TemporaryImage.getImageByUlid(ulid, '/admin/product/image/');
      if (imageData) {
        TemporaryImage.updateUI(imageData, false);
      }
    } catch (error) {
      console.error('サムネイル画像の読み込みに失敗:', error);
    }
  }


  /**
   * 既存のその他画像を読み込んで表示する
   *
   * @returns {Promise<void>}
   */
  async loadExistingOtherImages() {
    const existingImageInputs = document.querySelectorAll('input[name="existing_other_images[]"]');
    console.log(existingImageInputs.length);
    for (const input of existingImageInputs) {
      if (!input.value) continue;
      console.log(input.value);

      const ulid = input.value;

      try {
        const imageData = await TemporaryImage.getImageByUlid(ulid, '/admin/product/image/');
        if (imageData) {
          TemporaryImage.updateUI(imageData, true);
        }
      } catch (error) {
        console.error('その他画像の読み込みに失敗:', error);
      }
    }
  }


  /**
   * 単一ファイルのアップロード処理を実行する
   *
   * @param {Event} e
   * @returns {Promise<void>}
   */
  async handleSingleUpload(e) {
    const image = e.target.files[0];
    if (!image) return;

    try {
      const result = await TemporaryImage.uploadAndDisplay(image, false, '/admin/temporary/upload');
      console.log('アップロード成功:', result.url);
    } catch (err) {
      console.error('アップロード失敗:', err);
    }
  }


  /**
   * 複数ファイルのアップロード処理を実行する
   *
   * @param {Event} e
   * @returns {Promise<void>}
   */
  async handleMultipleUpload(e) {
    const files = e.target.files;
    const filesArray = Array.from(files);
    const currentTemporary = document.querySelectorAll('.js-uploaded-multiple-temporary');

    if (filesArray.length + currentTemporary.length > 4) {
      alert('ファイルのアップロード上限は4つになります。');
      return;
    }

    await TemporaryImage.uploadMultipleAndDisplay(files, '/admin/temporary/upload')
  }


  /**
   * old値から画像を復元して表示する
   *
   * @returns {Promise<void>}
   */
  async loadOldImages() {
    await TemporaryImage.loadOldSingleImage('old-thumbnail', '/admin/product/image/');
    await TemporaryImage.loadOldMultipleImages('old-other-thumbnail', '/admin/product/image/');
  }


  /**
   * 削除ボタンのイベントリスナーを初期化する
   *
   * @returns {void}
   */
  initDeleteButtons() {
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
   * 画像削除/取り消し処理を実行する
   *
   * @param {Event} e
   * @returns {void}
   */
  handleImageDelete(e) {
    const button = e.target;
    const container = button.closest('li');
    const input = container.querySelector('input[type="hidden"]');
    // 操作するcss classを定義
    const deleteClass = ['bg-red-600', 'hover:bg-red-800', 'focus:ring-red-300', 'dark:focus:ring-red-800'];
    const cancelClass = ['bg-blue-600', 'focus:ring-blue-300', 'dark:focus:ring-blue-800'];

    // 現在の状態を確認
    const isDeleted = button.textContent.trim() === '取り消す';

    if (isDeleted) {
      // 取り消し処理：削除状態から元に戻す
      if (input) {
        input.disabled = false;
      }

      // UIを元の状態に戻す
      // container.style.opacity = '1';
      button.textContent = '一時削除';
      button.disabled = false;
      button.classList.remove(...cancelClass);
      button.classList.add(...deleteClass);
    } else {
      // 削除処理
      if (input) {
        input.disabled = true;
      }

      // UIを削除状態にする
      // container.style.opacity = '0.5';
      button.textContent = '取り消す';
      button.disabled = false; // ボタンを有効にして再度クリック可能にする
      button.classList.remove(...deleteClass);
      button.classList.add(...cancelClass);
    }
  }


  /**
   * 画像完全削除処理を実行する
   *
   * @param {Event} e
   * @returns {void}
   */
  handlePermanentDelete(e) {
    const button = e.target;
    const container = button.closest('li');

    if (confirm('この画像を完全に削除しますか？この操作は取り消せません。')) {
      // 要素を完全に削除
      container.remove();
    }
  }
}

new EditManager();