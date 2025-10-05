import TemporaryImage from '../../modules/temporary-image';
import CommonImageHandler from '../../modules/common';


/**
 * イベント登録の一時画像アップロードを管理するクラス
 * サーバーに画像をアップロードして、成功時にはUIの追加を実行する責務をもつ
 *
 * @class CreateManager
 */
class CreateManager {
  /**
   * UploadManager のインスタンスを生成し、初期化処理を行う
   * イベントリスナーを登録する
   */
  constructor() {
    this.init();
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
   * 単一ファイルのアップロード処理を実行する
   *
   * @param {Event} e
   * @returns {Promise<void>}
   */
  async handleSingleUpload(e) {
    const image = e.target.files[0];
    if (!image) return;
    try {
      const result = await TemporaryImage.uploadAndDisplay(image, '/admin/temporary/upload', false );
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

new CreateManager();
