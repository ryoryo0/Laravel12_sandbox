/**
 * TemporaryUploader クラス
 * 
 * ファイルを Laravel に非同期でアップロードするためのクラスです。
 * CSRFトークン付きの fetch を用いて、安全に一時保存を行います。
 */
export default class TemporaryUploader {

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
}