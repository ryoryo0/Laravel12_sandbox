import TemporaryUploader from '../../modules/temporary-uploader';


/** 
 * 商品登録の一時画像アップロードを管理するクラス
 * サーバーに画像をアップロードして、成功時にはUIの追加を実行する責務をもつ
 * 
 * @class createUploadManager 
 */
class CreateUploadManager {
  /**
   * UploadManager のインスタンスを生成し、初期化処理を行う
   * イベントリスナーを登録する
   */
  constructor() {
    this.init();
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
    const url = '/admin/product/upload-temp';
    const uploader = new TemporaryUploader(url, file);

    try {
      const result = await uploader.upload();
      document.querySelector('.js-uploaded-temporary')?.remove();
      const isMultiple = false;
      const cloneContainer = this.createCloneList(isMultiple);  
      this.setTemporaryData(cloneContainer, result);
      this.ulAppend(isMultiple, cloneContainer);
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

    const url = '/admin/product/upload-temp';
    await Promise.all(
      Array.from(files).map(async (file) => {
        const uploader = new TemporaryUploader(url, file);
        try {
          const result = await uploader.upload();
          const isMultiple = true;
          const cloneContainer = this.createCloneList(isMultiple);  
          this.setTemporaryData(cloneContainer, result);
          this.ulAppend(isMultiple, cloneContainer);
          return result;

        } catch (err) {
          console.log('アップロード失敗:', err);
          return null;
        }
      })
    )
  }   


  /** 
   *  非表示設定されているリスト要素のクローンを作成
   * 
   * @param {boolean} isMultiple
   * @returns {HTMLElement}
   */
  createCloneList (isMultiple) {
    const multiple = isMultiple ? "-multiple" : "";
    const cloneContainer = document.querySelector(`[data-js="upload${multiple}-temporary"]`).cloneNode(true);
    cloneContainer.classList.add(`js-uploaded${multiple}-temporary`);
    cloneContainer.style.display = "block"; // 表示するように設定

    return cloneContainer;
  }


  /** 
   * アップロードを行なった一時画像のデータをセット
   * 
   * @param {HTMLElement} cloneContainer
   * @param {object} result 
   * @returns {HTMLElement}
   */
  setTemporaryData (cloneContainer, result) {
    cloneContainer.querySelector('img').src = result.url;
    cloneContainer.querySelector('p').textContent = result.name;
    cloneContainer.querySelector('input').value = result.ulid;
    return cloneContainer;
  }


  /** 
   * ul要素にリスト要素を追加する
   * 
   * @param {boolean} isMultiple
   * @param {HTMLElement} cloneContainer
   * @returns {void}
   */
  ulAppend (isMultiple, cloneContainer) {
    const multiple = isMultiple ? "-multiple" : "";
    const ul = document.querySelector(`#js-uploaded${multiple}-temporary-list`);
    ul.append(cloneContainer);
  }
}

new CreateUploadManager();