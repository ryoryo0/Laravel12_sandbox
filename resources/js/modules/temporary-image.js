/**
 * TemporaryImage クラス
 * 
 * ファイルを Laravel に非同期でアップロードするためのクラスです。
 * CSRFトークン付きの fetch を用いて、安全に一時保存を行います。
 */
export default class TemporaryImage {

  url;
  file;
  csrfToken;

  /**
   * コンストラクタ
   *
   * @param {string} url - アップロード先のエンドポイントURL
   * @param {File} file - アップロードするファイルオブジェクト
   */
  constructor(url, file) {
    this.url = url; // routeのurlを取得
    this.file = file;
    this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  }
  


  /**
   * 一時ファイルをアップロードします。
   *
   * @returns {Promise<Object>} アップロード結果のJSONレスポンス（例: { url: string, path: string }）
   * @throws {Error} アップロードに失敗した場合
   */
  async upload() {
    const formData = this.appendFormData(this.file);
    const res = await fetch(this.url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': this.csrfToken
      },
      body: formData
    });

    if (!res.ok) throw new Error('アップロード失敗');
    return await res.json();
  }


   /**
   * FormDataオブジェクトにファイルを追加します。
   *
   * @param {File} file - アップロード対象のファイル
   * @returns {FormData} アップロードに使用するFormDataオブジェクト
   */
  appendFormData(file) {
    const formData = new FormData();
    formData.append('image', file);
    return formData;
  }


  /**
   * 特定要素の中に画像要素を追加します。
   *
   * @param {object} result 画像データのオブジェクト
   * @param {Element} container 指定する要素の属性
   * @returns {void}
   */
  static imgAppend(result, container) {
    const img = document.createElement('img');
    img.src = result.url;
    img.classList.add("mt-6", "mb-6");
    container.append(img);
  }


  /**
   * ULIDから画像情報を取得します。
   *
   * @param {string} ulid 取得したい画像のULID
   * @returns {Promise<Object>} 画像情報のJSONレスポンス（例: { url: string, name: string, ulid: string }）
   * @throws {Error} 取得に失敗した場合
   */
  static async getImageByUlid(ulid) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const res = await fetch(`/admin/product/temporary-image/${ulid}`, {
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    });

    if (!res.ok) throw new Error('画像情報の取得に失敗');
    return await res.json();
  }
}