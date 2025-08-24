import Quill from 'quill';
import TemporaryImage from '../modules/temporary-image';
import 'quill/dist/quill.snow.css';



export default function initQuill() {
  const editor = document.querySelector('#editor');
  if (!editor) return;

  // ツールバーのオプション
  const toolbarOptions = [
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
    ['image'] // 画像ボタン
  ];

  // Quill Editorをセット
  const quill = new Quill(editor, {
    theme: 'snow',
    placeholder: '本文を入力してください...',
    modules: {
      toolbar: {
        container: toolbarOptions,
        handlers: {
          image: imageHandler
        }
      }
    }
  });

  // hidden input の取得
  const hiddenJson = document.getElementById('detail_json');

  // テキストの変更時に hidden にセット
  quill.on('text-change', () => {
    if (hiddenJson) hiddenJson.value = JSON.stringify(quill.getContents());
  });

  
  function imageHandler() {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = async () => {
      const file = input.files[0];
      if (!file) return;
      const url = '/admin/product/upload-temp';
      const uploader = new TemporaryImage(url, file);
      try { 
        const result = await uploader.upload();
        const range = quill.getSelection();
        quill.insertEmbed(range.index, 'image', result.url);
      } catch (err) {
        console.error(err);
        alert('quillの画像アップロードに失敗しました');
      }
    };
  }
}