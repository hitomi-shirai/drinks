document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const result = window.confirm('本当に削除しますか？');
            if (!result) {
                e.preventDefault(); // 削除をキャンセル
            }
        });
    });
});
