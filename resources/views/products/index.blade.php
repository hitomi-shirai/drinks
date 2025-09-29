@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('css/index.css') }}">

<div class="container">
    <h1>商品一覧画面</h1>

    <!-- 検索バー -->
     <div class="search-bar mb-3">
        <form id = "search-form" method = "get">   
            @csrf
        <input type = "text" name = "search" id = "search-input" placeholder = "検索キーワード" value="{{ request('search')}}">
        <select name = "company_id" id = "company-select">
             <option value = "">すべてのメーカー</option>
            @foreach($companies as $company)
            <option value ="{{$company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                {{ $company->company_name }}
            </option>
            @endforeach
        </select>

        <!-- 価格範囲 -->
        <input type="number" id="price-min" name="price_min" placeholder="価格(下限)">
        <input type="number" id="price-max" name="price_max" placeholder="価格(上限)">

        <!-- 在庫範囲 -->
        <input type="number" id="stock-min" name="stock_min" placeholder="在庫(下限)">
        <input type="number" id="stock-max" name="stock_max" placeholder="在庫(上限)">

        <button  type="submit" class="btn-kensaku">検索</button>
        </form>
     </div>
    <!-- 商品一覧テーブル -->
    <table class="product-table">
        <thead>
            <tr>
                <th class="sortable" data-column="id" data-order="desc">ID</th>
                <th>商品画像</th>
                <th class="sortable" data-column="product_name" data-order="asc">商品名</th>
                <th class="sortable" data-column="price" data-order="asc">価格</th>
                <th class="sortable" data-column="stock" data-order="asc">在庫数</th>
                <th class="sortable" data-column="company_id" data-order="asc">メーカー名</th>
                <th> 
                    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3 new-registration-button">新規登録</a>  
                </th>
                <th></th>
            </tr>
        </thead>

        <!-- 非同期で書き換える場所  --->
        <tbody id = "product-list">
        @foreach ($products as $product)
            <tr>
                <td>{{ $product -> id }}</td>
                <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100">
                </td>
                <td>{{ $product -> product_name }}</td>
                <td>{{ $product -> price }}</td>
                <td>{{ $product -> stock }}</td>
                <td>{{ $product -> company -> company_name }}</td>
                <td class="actions">
                    <a href = "{{ route('products.show', $product -> id) }}" class="btn btn-primary btn-detail">詳細</a>
                    <form action = "{{ route('products.destroy', $product -> id) }}" method="POST" class="delete-form" >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-delete">削除</button>
                    </form>
                </td>  
            </tr>
        @endforeach
        </tbody>
    </table>

 <!-- ページネーション（JSで更新） -->
 <div id="pagination" class="pagination">
        {{-- 最初の描画はサーバー側のlinksを表示しておく（オプション） --}}
        {{ $products->links('vendor.pagination.default') }}
    </div>
</div>

{{-- jQuery をここで先に読み込む（必ず Vite に含める search.js より先） --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

{{-- Viteでバンドルしたスクリプトをここに読み込む（delete.js と search.js） --}}
@vite(['resources/js/delete.js', 'resources/js/search.js'])

@endsection