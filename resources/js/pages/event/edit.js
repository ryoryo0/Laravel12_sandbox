import TemporaryImage from '../../modules/temporary-image';
import CommonImageHandler from '../../modules/common';


/**
 * イベント編集の一時画像アップロードを管理するクラス
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
    this.loadOldImages();
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
    // 削除ボタンのイベントリスナー（共通モジュールを使用）
    CommonImageHandler.initDeleteButtons();
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

    const hasOldValues = (oldThumbnail && oldThumbnail.value);

    if (hasOldValues) {
      console.log('old値が存在するため、既存画像の読み込みをスキップします');
      return;
    }

    await this.loadExistingThumbnail();
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
      const imageData = await TemporaryImage.getImageByUlid(ulid, '/admin/event/image/');
      if (imageData) {
        TemporaryImage.updateUI(imageData, false);
      }
    } catch (error) {
      console.error('サムネイル画像の読み込みに失敗:', error);
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
      const result = await TemporaryImage.uploadAndDisplay(image, '/admin/temporary/upload', false);
      console.log('アップロード成功:', result.url);
    } catch (err) {
      console.error('アップロード失敗:', err);
    }
  }


  /**
   * old値から画像を復元して表示する
   *
   * @returns {Promise<void>}
   */
  async loadOldImages() {
    await TemporaryImage.loadOldSingleImage('old-thumbnail', '/admin/temporary/image/');
  }
}

new EditManager();
