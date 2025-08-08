// resources/views/js/product-create.js

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const submitButton = document.querySelector('button[type="submit"]');

    submitButton.addEventListener('click', function (event) {
        // 入力チェック
        const productName = document.getElementById('product_name').value.trim();
        const companyId = document.getElementById('company_id').value;
        const price = document.getElementById('price').value.trim();
        const stock = document.getElementById('stock').value.trim();

        let errorMessage = '';

        if (!productName) errorMessage += '商品名を入力してください。\n';
        if (!companyId) errorMessage += 'メーカー名を選択してください。\n';
        if (!price) errorMessage += '価格を入力してください。\n';
        if (!stock) errorMessage += '在庫数を入力してください。\n';

        if (errorMessage) {
            alert(errorMessage); // 日本語エラーメッセージ表示
            event.preventDefault(); // 送信を止める
            return;
        }

        // 登録確認ポップアップ
        const isConfirmed = window.confirm('本当に登録しますか？');
        if (!isConfirmed) {
            event.preventDefault(); // 送信を止める
        }
    });
});
