import Quill from 'quill';
import 'quill/dist/quill.snow.css';

/**
 * 【前提】
 * 1)まずは以下を参照してnpmコマンドでquillを導入してください。
 * https://github.com/slab/quill
 * 
 * 2)本ライブラリを活用してエディタを登録する際は、セキュア観点からhtmlでなくjsonデータがDBに登録されます。
 * 表示を行う場合は別途整形用の表示ライブラリが必要になるのでぜひご活用ください。
 * 
 * 3)temporary-imageモジュールのuploadFileメソッドを併用することで、画像データパスで登録することが実現できます。
 * sql負荷のかからない高パフォーマンスを実現したい場合は、ぜひご活用ください。
 *  const imageUploadHandler = async (image) => {
 *     return await TemporaryImage.upload(image);
 *   };
 * 
 *  ※上記の定数を第３引数に　指定する必要があります
 * 
 * 
 * 
 * 
 * 
 * 【エディタの導入手順】
 *
 * 1)以下の要素を追加する
 *  <div class="flex items-center me-4" id="editor"></div>
 *  <input type="hidden" value="xxxxxx" id="detail_json" name="xxxxxx">
 * 
 * 2)対象のjsファイルで「import initQuill from '../../libraries/quill';」を記述してライブラリを呼び出す
 * 
 * 3)initQuillメソッドを呼び出せばエディタの操作を出力することが可能です。
 */


/**
 * Quillエディタを初期化する
 *
 * @param {string} selector エディタのセレクタ（デフォルト: '#editor'）
 * @param {string} hiddenInputId hidden inputのID（デフォルト: 'detail_json'）
 * @param {Function} imageUploadHandler 画像アップロード処理関数
 * @param {Object} options Quillのオプション設定
 * @returns {Quill} Quillインスタンス
 */
export default function initQuill(
  selector = '#editor',
  hiddenInputId = 'detail_json',
  imageUploadHandler = null,
  options = {}
) {
  const editor = document.querySelector(selector);
  if (!editor) return null;

  // デフォルトのツールバーオプション
  const defaultToolbarOptions = [
    [{ 'size': ['small', false, 'large', 'huge'] }],
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],
    [{ 'header': 1 }, { 'header': 2 }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],
    [{ 'indent': '-1'}, { 'indent': '+1' }],
    [{ 'direction': 'rtl' }],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'font': [] }],
    [{ 'align': [] }],
    ['clean'],
    ['image'] // 画像ボタンは常に表示
  ];

  // Quillの設定をマージ
  const quillOptions = {
    theme: options.theme || 'snow',
    placeholder: options.placeholder || '本文を入力してください...',
    modules: {
      toolbar: {
        container: options.toolbar || defaultToolbarOptions,
        handlers: {
          image: () => createImageHandler(quill, imageUploadHandler)
        }
      },
      ...options.modules
    },
    ...options
  };

  // Quill Editorをセット
  const quill = new Quill(editor, quillOptions);

  // hidden input の取得
  const hiddenJson = document.getElementById(hiddenInputId);

  // old値がある場合はエディタに復元
  if (hiddenJson && hiddenJson.value) {
    try {
      const oldContents = JSON.parse(hiddenJson.value);
      quill.setContents(oldContents);
    } catch (error) {
      console.warn('Quillコンテンツの復元に失敗:', error);
    }
  }


  // テキストの変更時に hidden にセット
  quill.on('text-change', () => {
    if (hiddenJson) hiddenJson.value = JSON.stringify(quill.getContents());
  });

  return quill;
}


/**
 * 画像アップロードハンドラを作成する
 *
 * @param {Quill} quill Quillインスタンス
 * @param {Function} uploadHandler アップロード処理関数
 */
function createImageHandler(quill, uploadHandler) {
  const input = document.createElement('input');
  input.setAttribute('type', 'file');
  input.setAttribute('accept', 'image/*');
  input.click();

  input.onchange = async () => {
    const image = input.files[0];
    if (!image) return;

    try {
      let imageUrl;

      if (uploadHandler) {
        // カスタムアップロードハンドラがある場合（一時画像アップロード）
        const result = await uploadHandler(image);
        imageUrl = result.url;
      } else {
        // アップロードハンドラがnullの場合、バイナリデータとして処理
        imageUrl = await convertFileToDataURL(image);
      }

      const range = quill.getSelection();
      quill.insertEmbed(range.index, 'image', imageUrl);
    } catch (error) {
      console.error('画像アップロードに失敗:', error);
      alert('画像アップロードに失敗しました');
    }
  };
}

/**
 * 画像ファイルをData URLに変換する
 *
 * @param {File} image 変換する画像ファイル
 * @returns {Promise<string>} Data URL文字列
 */
function convertFileToDataURL(image) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => resolve(reader.result);
    reader.onerror = () => reject(new Error('画像ファイルの読み込みに失敗しました'));
    reader.readAsDataURL(image);
  });
}