$(function () {
    // CSRF トークンを Ajax に自動設定
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 削除ボタンのクリック処理
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');

        if (!confirm('本当に削除しますか？')) {
            return;
        }

        $.ajax({
            url: '/products/' + id,
            type: 'DELETE',
            success: function (response) {
                if (response.success) {
                    // 成功したら行を削除
                    $('#product-row-' + id).remove();
                } else {
                    alert('削除に失敗しました');
                }
            },
            error: function (xhr) {
                alert('通信エラー: ' + xhr.responseText);
            }
        });
    });
});
