@extends('layouts.app')
@vite('resources/js/delete.js')
@section('content')

<link rel="stylesheet" href="{{ asset('css/index.css') }}">

<div class="container">
    <h1>商品一覧画面</h1>

    <!-- 検索バー -->
     <div class="search-bar mb-3">
        <form method = "get">   
            @csrf
        <input type = "text" name = "search" placeholder = "検索キーワード" value="{{ request('search')}}">
        <select name = "company_id" id = "company_id">
             <option value = "" disabled selected>メーカー名</option>
            @foreach($companies as $company)
            <option value ="{{$company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                {{ $company->company_name }}
            </option>
            @endforeach
        </select>
        <button  type="submit" class="btn-kensaku">検索</button>
        </form>
     </div>
    <!-- 商品一覧テーブル -->
    <table class="product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>メーカー名</th>
                <th> <a href="{{ route('products.create') }}" class="btn btn-primary mb-3 new-registration-button">新規登録</a>  
                </th>
                <th></th>
            </tr>
        </thead>

        <tbody>
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

    <!-- ページネーション -->
    <div class="pagination">
        {{ $products->links('vendor.pagination.default') }}
    </div>
</div>

@endsection