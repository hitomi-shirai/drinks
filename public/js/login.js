document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const loginButton = document.querySelector('.btn-login');

    loginButton.addEventListener('click', function (e) {
        // 入力チェック
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!email || !password) {
            alert('メールアドレスとパスワードを入力してください');
            e.preventDefault(); // 送信を止める
            return;
        }

        // 確認ポップアップ
        const isConfirmed = window.confirm('本当にログインしますか？');

        if (!isConfirmed) {
            e.preventDefault(); // 送信を止める
        }
    });
});
