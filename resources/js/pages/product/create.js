import TemporaryUploader from '../../modules/temporary-uploader';


/**
* サムネイルの一時画像アップロードの非同期処理
*
*/
document.querySelector('[data-js="upload-temporary"]')?.addEventListener('change', async (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const url = '/admin/product/upload-temp';
  const uploader = new TemporaryUploader(url, file);

  try {
    const result = await uploader.upload();
    const container = document.querySelector('#js-uploaded-temporary');
    TemporaryUploader.imgAppend(result, container);
    console.log('アップロード成功:', result.url);
  } catch (err) {
    console.error('アップロード失敗:', err);
  }
});


/**
* 複数画像の一時画像アップロードの非同期処理
*
*/
document.querySelector('[data-js="upload-multiple-temporary"]')?.addEventListener('change', async (e) => {
  const files = e.target.files;
  if (!files) return;
  const url = '/admin/product/upload-temp';
  const results = await Promise.all(
    Array.from(files).map(async (file) => {
      const uploader = new TemporaryUploader(url, file);
      try {
        return await uploader.upload();
      } catch (err) {
        console.log('アップロード失敗:', err);
        return null;
      }
    })
  )

  const successfulResults = results.filter(Boolean);
  if (!successfulResults) return;
    const container = document.querySelector('#js-uploaded-multiple-temporary');
    successfulResults.forEach((result) => {
      TemporaryUploader.imgAppend(result, container);
  })
});


