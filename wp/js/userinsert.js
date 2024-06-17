import { insert, dataType } from "./APIaccessor.js";

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('registerBtn').addEventListener('click', async () => {

    const name = document.getElementById('name').value;
    const affiliation = document.getElementById('affiliation').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;


    const data = { name, affiliation, email, phone };

    try {      const response = await insert(dataType.userlist, data);

      if (response.success) {
        alert('登録が成功しました');
      } else {
        alert('登録に失敗しました');
      }
    } catch (error) {
      alert('エラーが発生しました: ' + error.message);
    }
  });
});
