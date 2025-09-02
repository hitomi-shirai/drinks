document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const submitButton = document.querySelector(".btn-register");

    submitButton.addEventListener("submit", function (event) {
        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const passwordConfirmation = document.getElementById("password_confirmation").value.trim();

        // バリデーション（空欄チェック）
        if (!name || !email || !password || !passwordConfirmation) {
            alert("すべての項目を入力してください。");
            event.preventDefault(); // フォーム送信を止める
            return;
        }

        // パスワード確認チェック（オプション）
        if (password !== passwordConfirmation) {
            alert("パスワードと確認用パスワードが一致しません。");
            event.preventDefault();
            return;
        }

        // 確認ポップアップ
        const result = window.confirm("本当に登録しますか？");
        if (!result) {
            event.preventDefault(); // キャンセルなら送信しない
        }
    });
});
