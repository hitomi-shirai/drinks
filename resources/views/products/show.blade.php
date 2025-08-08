@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/show.css') }}">

<div class="container">
    <h1 class="mb-4">商品情報詳細画面</h1>

    <div class = "product-form-box">
        <form method = "POST">
           
          @csrf

             <div class = "mb-3">
                <label for = "ID">ID </label>
                <p>{{ $product->id }}</p>
             </div>

            <div class = "mb-3">
                <label for = "img_path">商品画像</label>
                @if ($product->img_path)
                <img src="{{ asset($product->img_path) }}" alt="商品画像" width="100">
            @else
                <p>画像なし</p>
            @endif
            </div>
    
            
            <div class = "mb-3">
                <label for = "product_name">商品名</label>
                <p>{{ $product->product_name }}</p>
            </div> 
            
            <div class = "mb-3">
                <label for = "company_id">メーカー名</label>
                    <p>{{ $product->company->company_name }}</p>                
            </div>  

            <div class = "mb-3">
                <label for = "price">価格</label>
                <p>{{ $product->price }}</p>
            </div>
            
            <div class = "mb-3">
                <label for = "stock">在庫数</label>
                <p>{{ $product->stock }}</p>
            </div>
            
            <div class = "mb-3">
                <label for = "comment">コメント</label>
                <p>{{ $product->comment }}</p>
            </div>  
            
            <a href = "{{ route('products.edit', $product->id) }}" class = "btn btn-primary">編集</a>
            <a href = "{{ route('products.index') }}" class = "btn btn-secondary">戻る</a>
    
        </form>   
    </div>
       
</div>
@endsection

