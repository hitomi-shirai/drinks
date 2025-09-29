$(function () {
    // CSRF トークン（必要なら）
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 現在のソート情報とページ
    let currentSortColumn = $('.sortable[data-column="id"]').data('column') || 'id';
    let currentSortOrder = $('.sortable[data-column="id"]').data('order') || 'desc';
    let currentPage = 1;

    // Ajaxで検索・ソート・ページングを取得して描画する関数
    function fetchProducts(extra = {}) {
        // extra で上書き（sort_column, sort_order, page）
        if (extra.sort_column) currentSortColumn = extra.sort_column;
        if (extra.sort_order) currentSortOrder = extra.sort_order;
        if (extra.page) currentPage = extra.page;

        $.ajax({
            url: '/products/search',
            type: 'GET',
            data: {
                search: $('#search-input').val(),
                company_id: $('#company-select').val(),
                price_min: $('#price-min').val(),
                price_max: $('#price-max').val(),
                stock_min: $('#stock-min').val(),
                stock_max: $('#stock-max').val(),
                sort_column: currentSortColumn,
                sort_order: currentSortOrder,
                page: currentPage
            },
            dataType: 'json'
        })
        .done(function (response) {
            // 1) テーブルを置き換え
            $('#product-list').empty();
            response.data.forEach(function (product) {
                const imgPath = product.img_path ? `/${product.img_path}` : '/images/no-image.png';
                let row = `
                    <tr>
                        <td>${product.id}</td>
                        <td><img src="${imgPath}" alt="商品画像" width="100"></td>
                        <td>${product.product_name}</td>
                        <td>${product.price}</td>
                        <td>${product.stock}</td>
                        <td>${product.company ? product.company.company_name : ''}</td>
                        <td class="actions">
                            <a href="/products/${product.id}" class="btn btn-primary btn-detail">詳細</a>
                            <form action="/products/${product.id}" method="POST" class="delete-form" style="display:inline;">
                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-delete">削除</button>
                            </form>
                        </td>
                    </tr>
                `;
                $('#product-list').append(row);
            });

            // 2) ページネーション描画
            let paginationHtml = '';

            // 前へ
            if (response.pagination.current_page > 1) {
                paginationHtml += `<a href="#" class="page-link" data-page="${response.pagination.current_page - 1}">«</a> `;
            }

            for (let i = 1; i <= response.pagination.last_page; i++) {
                if (i === response.pagination.current_page) {
                    paginationHtml += `<span class="active">${i}</span> `;
                } else {
                    paginationHtml += `<a href="#" class="page-link" data-page="${i}">${i}</a> `;
                }
            }

            // 次へ
            if (response.pagination.current_page < response.pagination.last_page) {
                paginationHtml += `<a href="#" class="page-link" data-page="${response.pagination.current_page + 1}">»</a>`;
            }

            $('#pagination').html(paginationHtml);

            // テーブルヘッダーの見た目（矢印など）更新 - 単純にdata-order属性をDOMに反映
            $('.sortable').each(function () {
                const col = $(this).data('column');
                if (col === currentSortColumn) {
                    $(this).attr('data-order', currentSortOrder);
                    // 任意：矢印表示を付けたいならここで追加する
                } else {
                    $(this).attr('data-order', 'asc'); // 他は初期に戻す
                }
            });
        })
        .fail(function (xhr) {
            alert('検索に失敗しました');
            console.error('検索エラー:', xhr.responseText);
        });
    }

    // 検索フォーム送信
    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        currentPage = 1;
        fetchProducts();
    });

    // ソートヘッダークリック（イベント委譲）
    $(document).on('click', '.sortable', function (e) {
        e.preventDefault();
        const $th = $(this);
        const column = $th.data('column');
        // read current order from attribute (fall back to asc)
        const orderAttr = $th.attr('data-order') || $th.data('order') || 'asc';
        const newOrder = orderAttr === 'asc' ? 'desc' : 'asc';

        // 更新してフェッチ
        currentPage = 1;
        fetchProducts({ sort_column: column, sort_order: newOrder });

        // 見た目用に属性も更新
        $('.sortable').attr('data-order', 'asc'); // 他をリセット
        $th.attr('data-order', newOrder);
    });

    // ページリンククリック（イベント委譲）
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'), 10) || 1;
        fetchProducts({ page: page });
    });

});
