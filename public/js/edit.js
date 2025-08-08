document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editForm');
    console.log("form:", form);
    const productName = document.getElementById('product_name');
    const companyId = document.getElementById('company_id');
    const price = document.getElementById('price');
    const stock = document.getElementById('stock');

    form.addEventListener('submit', function (e) {
        // 入力チェック（空欄の場合）
        if (!productName.value.trim() || !companyId.value || !price.value.trim() || !stock.value.trim()) {
            alert('必須項目が入力されていません。すべて入力してください。');
            e.preventDefault(); // フォーム送信を止める
            return;
        }

        // 更新確認ポップアップ
        const confirmed = window.confirm('本当に更新しますか？');
        if (!confirmed) {
            e.preventDefault(); // 送信キャンセル
        }
    });
});
