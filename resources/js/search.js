$(function () {
    // 下記CSRF トークンを設定
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 検索フォーム送信イベント
    $('#search-form').on('submit', function (e) {
        e.preventDefault(); // フォーム送信を止める

        $.ajax({
            url: '/products/search', 
            type: 'GET',
            data: {
                search: $('#search-input').val(),
                company_id: $('#company-select').val()
            },
            dataType: 'json'
        })
        .done(function (data) {
            // テーブルを空にする
            $('#product-list').empty();

            // データを1件ずつ追加
            data.forEach(function (product) {
                let row = `
                    <tr>
                        <td>${product.id}</td>
                        <td>
                            <img src="/${product.img_path}" alt="商品画像" width="100">
                        </td>
                        <td>${product.product_name}</td>
                        <td>${product.price}</td>
                        <td>${product.stock}</td>
                        <td>${product.company.company_name}</td>
                        <td class="actions">
                            <a href="/products/${product.id}" class="btn btn-primary btn-detail">詳細</a>
                            <form action="/products/${product.id}" method="POST" class="delete-form">
                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-delete">削除</button>
                            </form>
                        </td>
                        <td></td>
                    </tr>
                `;
                $('#product-list').append(row);
            });
        })
        .fail(function (xhr, status, error) {
            alert('検索に失敗しました');
            console.error('エラー内容:', xhr.responseText);
        });
    });
});