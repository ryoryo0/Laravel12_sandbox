import initQuill from '../../libraries/quill';
import TemporaryImage from '../../modules/temporary-image';


/** 
 * 商品登録の一時画像アップロードを管理するクラス
 * サーバーに画像をアップロードして、成功時にはUIの追加を実行する責務をもつ
 * 
 * @class createManager 
 */
class CreateManager {
  /**
   * UploadManager のインスタンスを生成し、初期化処理を行う
   * イベントリスナーを登録する
   */
  constructor() {
    this.init();
    this.initQuillEditor(); // quillの初期化
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
    // 複数アップロード
    document.querySelector('[data-js="upload-multiple-temporary-inout"]')
    ?.addEventListener('change', (e) => this.handleMultipleUpload(e));
  }


  /** 
   * 単一ファイルのアップロード処理を実行する
   * 
   * @param {Event} e
   * @returns {Promise<void>}
   */
  async handleSingleUpload(e) {
    const file = e.target.files[0];
    if (!file) return;

    try {
      const result = await TemporaryImage.uploadAndDisplay(file, false, '/admin/temporary/upload');
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
   * Quillエディタを初期化する
   *
   * @returns {Quill} Quillインスタンス
   */
  initQuillEditor() {
    // 画像アップロード処理を定義(一時画像アップロードモジュールのメソッド活用)
    const imageUploadHandler = async (file) => {
      return await TemporaryImage.upload(file, '/admin/temporary/upload');
    };

    // Quillエディタを初期化
    return initQuill('#editor', 'detail_json', imageUploadHandler);
  }


  /**
   * old値から画像を復元して表示する
   *
   * @returns {Promise<void>}
   */
  async loadOldImages() {
    await TemporaryImage.loadOldSingleImage('old-thumbnail', '/admin/temporary/show/');
    await TemporaryImage.loadOldMultipleImages('old-other-thumbnail', '/admin/temporary/show/');
  }
}

new CreateManager();