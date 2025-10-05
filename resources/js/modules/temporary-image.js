/**
 * TemporaryImage クラス
 * 
 * 　一時画像ファイルを Laravel に非同期で通信するためのクラスです。
 * CSRFトークン付きの fetch を用いて、安全に一時保存を行います。
 */
export default class TemporaryImage {

  url;
  image;
  csrfToken;

  /**
   * コンストラクタ
   *
   * @param {string} url - アップロード先のエンドポイントURL
   * @param {File} image - アップロードする画像ファイルオブジェクト
   */
  constructor(url, image) {
    this.url = url; // routeのurlを取得
    this.image = image;
    this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  }
  

  /************************************************************************************
   *
   * Old値復元
   *
   *************************************************************************************/


  /**
   * 単一画像のold値を復元する
   *
   * @param {string} singleImageId 単一画像のinput要素のID
   * @param {string} url 取得先のエンドポイントURL
   * @returns {Promise<void>}
   */
  static async loadOldSingleImage(singleImageId, url) {
    const oldThumbnailInput = document.getElementById(singleImageId);
    if (!oldThumbnailInput || !oldThumbnailInput.value) return;

    try {
      const result = await this.getImageByUlid(oldThumbnailInput.value, url);
      const cloneContainer = this.createCloneList(false);
      this.setTemporaryData(cloneContainer, result);
      this.ulAppend(false, cloneContainer);
    } catch (error) {
      console.error('サムネイル画像の復元に失敗:', error);
    }
  }


  /**
   * 複数画像のold値を復元する
   *
   * @param {string} multipleImageClass 複数画像のinput要素のクラス名
   * @param {string} url 取得先のエンドポイントURL
   * @returns {Promise<void>}
   */
  static async loadOldMultipleImages(multipleImageClass, url) {
    const oldOtherThumbnailInputs = document.querySelectorAll(`.${multipleImageClass}`);
    if (!oldOtherThumbnailInputs.length) return;

    for (const input of oldOtherThumbnailInputs) {
      if (!input.value) continue;

      try {
        const result = await this.getImageByUlid(input.value, url);
        const cloneContainer = this.createCloneList(true);
        this.setTemporaryData(cloneContainer, result);
        this.ulAppend(true, cloneContainer);
      } catch (error) {
        console.error('複数画像の復元に失敗:', error);
      }
    }
  }


  /**
   * ULIDから画像情報を取得します。
   *
   * @param {string} ulid 取得したい画像のULID
   * @param {string} url 取得先のエンドポイントURL
   * @returns {Promise<Object>} 画像情報のJSONレスポンス（例: { url: string, name: string, ulid: string }）
   * @throws {Error} 取得に失敗した場合
   */
  static async getImageByUlid(ulid, url) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const res = await fetch(`${url}${ulid}`, {
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    });

    if (!res.ok) throw new Error('画像情報の取得に失敗');
    return await res.json();
  }


  /**************************************************************************************
   * 
   * アップロード
   * 
   ***************************************************************************************/


  /**
   * 単一画像をアップロードして画面に表示する
   *
   * @param {File} image アップロードする画像ファイル
   * @param {string} url アップロード先のエンドポイントURL
   * @returns {Promise<Object>} アップロード結果
   */
  static async uploadAndDisplay(image, url, isLoading = true) {
    const loadingElement = this.showLoading(false);

    try {
      const result = await this.upload(image, url);
      this.updateUI(result);
      return result;
    } catch (error) {
      console.error('アップロード失敗:', error);
      throw error;
    } finally {
      this.hideLoading(loadingElement);
    }
  }


  /**
   * 画像ファイルをアップロードする
   *
   * @param {File} image アップロードする画像ファイル
   * @param {string} url アップロード先のエンドポイントURL
   * @param {boolean} showLoading ローディング表示を行うかどうか (デフォルト: false)
   * @returns {Promise<Object>} アップロード結果
   */
  static async upload(image, url, isShowGlobalLoading = false) {
    const uploader = new TemporaryImage(url, image);

    if (isShowGlobalLoading) {
      const loadingElement = this.showGlobalLoading();
      try {
        const result = await uploader.upload();
        return result;
      } finally {
        this.hideLoading(loadingElement);
      }
    } else {
      return await uploader.upload();
    }
  }


  /**
   * 複数ファイルをアップロードして画面に表示する
   *
   * @param {FileList} files アップロードするファイルリスト
   * @param {string} url アップロード先のエンドポイントURL
   * @returns {Promise<Array>} アップロード結果の配列
   */
  static async uploadMultipleAndDisplay(files, url, isShowLoading = true) {
    const loadingElement = this.showLoading(true);

    try {
      const results = await this.uploadMultipleFiles(files, url);

      // 成功したアップロードのUIを更新
      results.forEach(result => {
        if (result) {
          this.updateUI(result, true);
        }
      });

      return results;
    } catch (error) {
      console.error('複数ファイルアップロード失敗:', error);
      throw error;
    } finally {
      this.hideLoading(loadingElement);
    }
  }


  /**
   * 複数ファイルを並列アップロードする
   *
   * @param {FileList} files アップロードするファイルリスト
   * @param {string} url アップロード先のエンドポイントURL
   * @returns {Promise<Array>} アップロード結果の配列
   */
  static async uploadMultipleFiles(files, url) {
    return await Promise.all(
      Array.from(files).map(async (image) => {
        try {
          return await this.upload(image, url);
        } catch (error) {
          console.error('アップロード失敗:', error);
          return null;
        }
      })
    );
  }


  /**
   * アップロード結果を使ってUIを更新する
   *
   * @param {Object} result アップロード結果
   * @param {boolean} isMultiple 複数アップロードかどうか
   * @returns {void}
   */
  static updateUI(result, isMultiple = false) {
    // 単一アップロードの場合は既存要素を削除
    if (!isMultiple) {
      document.querySelector('.js-uploaded-temporary')?.remove();
    }

    // UI要素を作成・設定・追加
    const cloneContainer = this.createCloneList(isMultiple);
    this.setTemporaryData(cloneContainer, result);
    this.ulAppend(isMultiple, cloneContainer);
  }


   /**
   * 非表示設定されているリスト要素のクローンを作成
   *
   * @param {boolean} isMultiple
   * @returns {HTMLElement}
   */
   static createCloneList(isMultiple) {
    const multiple = isMultiple ? "-multiple" : "";
    const cloneContainer = document.querySelector(`[data-js="upload${multiple}-temporary"]`).cloneNode(true);
    cloneContainer.classList.add(`js-uploaded${multiple}-temporary`);
    cloneContainer.style.display = "block";

    return cloneContainer;
  }


  /**
   * アップロードを行なった一時画像のデータをセット
   *
   * @param {HTMLElement} cloneContainer
   * @param {object} result
   * @returns {HTMLElement}
   */
  static setTemporaryData(cloneContainer, result) {
    cloneContainer.querySelector('img').src = result.url;
    cloneContainer.querySelector('p').textContent = result.name;
    const input = cloneContainer.querySelector('input');
    input.value = result.ulid;
    input.disabled = false;

    return cloneContainer;
  }


  /**
   * ul要素にリスト要素を追加する
   *
   * @param {boolean} isMultiple
   * @param {HTMLElement} cloneContainer
   * @returns {void}
   */
  static ulAppend(isMultiple, cloneContainer) {
    const multiple = isMultiple ? "-multiple" : "";
    const ul = document.querySelector(`#js-uploaded${multiple}-temporary-list`);
    ul.append(cloneContainer);
  }


  /**
   * 一時ファイルをアップロードします。
   *
   * @returns {Promise<Object>} アップロード結果のJSONレスポンス（例: { url: string, path: string }）
   * @throws {Error} アップロードに失敗した場合
   */
  async upload() {
    const formData = this.appendFormData(this.image);
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
   * FormDataオブジェクトに画像ファイルを追加します。
   *
   * @param {File} image - アップロード対象の画像ファイル
   * @returns {FormData} アップロードに使用するFormDataオブジェクト
   */
  appendFormData(image) {
    const formData = new FormData();
    formData.append('image', image);
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


  /**************************************************************************************
   *
   * ローディング
   *
   ***************************************************************************************/


  /**
   * ローディングアニメーションを表示する
   *
   * @param {boolean} isMultiple 複数アップロードかどうか
   * @returns {HTMLElement} ローディング要素
   */
  static showLoading(isMultiple) {
    const multiple = isMultiple ? "-multiple" : "";
    const targetContainer = document.querySelector(`#js-uploaded${multiple}-temporary-list`);

    if (!targetContainer) {
      console.warn('ローディング表示対象のコンテナが見つかりません');
      return null;
    }

    // ローディング要素を作成
    const loadingElement = document.createElement('div');
    loadingElement.className = 'loading-overlay flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg';
    loadingElement.innerHTML = `
      <div class="flex items-center space-x-3">
        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-sm text-gray-600 dark:text-gray-300">アップロード中...</span>
      </div>
    `;

    targetContainer.appendChild(loadingElement);
    return loadingElement;
  }


  /**
   * グローバルローディングアニメーションを表示する（Quillエディタ用）
   *
   * @returns {HTMLElement} ローディング要素
   */
  static showGlobalLoading() {
    // 既存のグローバルローディングがあれば削除
    const existingLoading = document.querySelector('.global-loading-overlay');
    if (existingLoading) {
      existingLoading.remove();
    }

    // グローバルローディング要素を作成
    const loadingElement = document.createElement('div');
    loadingElement.className = 'global-loading-overlay fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    loadingElement.innerHTML = `
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3">
        <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-sm text-gray-800 dark:text-gray-200">画像をアップロード中...</span>
      </div>
    `;

    document.body.appendChild(loadingElement);
    return loadingElement;
  }


  /**
   * ローディングアニメーションを非表示にする
   *
   * @param {HTMLElement} loadingElement ローディング要素
   * @returns {void}
   */
  static hideLoading(loadingElement) {
    if (loadingElement && loadingElement.parentNode) {
      loadingElement.parentNode.removeChild(loadingElement);
    }
  }
}