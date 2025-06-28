import TemporaryUploader from '../../modules/temporary-uploader';


document.querySelector('[data-js="upload-temporary"]')?.addEventListener('change', async (e) => {
  const url = '/admin/product/upload-temp';
  const file = e.target.files[0];
  if (!file) return;

  const uploader = new TemporaryUploader(url, file);

  try {
    const result = await uploader.upload();
    const temporaryImgElement =  document.querySelector('[data-js="uploaded-temporary"]');
    temporaryImgElement.src = result.url;
    temporaryImgElement.style.display = "block";
    console.log(temporaryImgElement);
    console.log('アップロード成功:', result.url);
  } catch (err) {
    console.error('アップロード失敗:', err);
  }
});