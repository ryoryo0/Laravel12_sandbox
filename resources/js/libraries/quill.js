import Quill from 'quill';
import 'quill/dist/quill.snow.css';

export function initQuill() {
  const editor = document.querySelector('#editor');
  // editorIDが存在しない場合は早期　リターン
  if (!editor) return;

  // ツールバーのオプションを設定
  const toolbarOptions = [
    [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    ['blockquote', 'code-block'],
  
    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
    [{ 'direction': 'rtl' }],                         // text direction

  
    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    [{ 'font': [] }],
    [{ 'align': [] }],

    ['clean'],   
    ['image']     
  ];

  // Quill Editorをセット
  const quill = new Quill(editor, {
      theme: 'snow', // Themaをセット
      placeholder: '本文を入力してください...', // placeholderをセット
      modules: {
        toolbar: toolbarOptions //　ツールバーオプションをセット
      },
  });

  // テキストの変更時にdetailにvalueをセット
  const hidden = document.getElementById('detail');
  if (hidden) {
      quill.on('text-change', () => {
          hidden.value = JSON.stringify(quill.getContents());
      });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  initQuill();
});