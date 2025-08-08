@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="container">
    <h1 class="mb-4">商品新規登録画面</h1>  

    <div class = "product-form-box">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
         @csrf
            
            <div class = "mb-3">
                <label for = "product_name">商品名</label>
                <input type = "text" id = "product_name" name = "product_name" required>
            </div> 
            
            <div class = "mb-3">
                <label for = "company_id">メーカー名</label>
                    <select id = "company_id" name = "company_id" required>
                        <option value = "" disabled selected></option>
                       @foreach($companies as $company)
                        <option value ="{{$company->id }}">{{ $company->company_name }}</option>
                       @endforeach
                    </select>
            </div>  
            
            <div class = "mb-3">
                <label for = "price">価格</label>
                <input type = "text" id = "price" name = "price" required >
            </div>
            
            <div class = "mb-3">
                <label for = "stock">在庫数</label>
                <input type = "text" id = "stock" name = "stock" required >
            </div>
            
            <div class = "mb-3">
                <label for = "comment">コメント</label>
                <textarea id = "comment" name = "comment"></textarea>
            </div>  
            
            <div class = "mb-3">
                <label for = "img_path">商品画像</label>
                <input type = "file" id = "img_path" name = "img_path">
            </div>
            
            <button type = "submit" class = "btn btn-primary">
                新規登録</button>
            <a href = "{{ route('products.index') }}" class = "btn btn-secondary">戻る</a>
    
        </form>   
    </div>
       
</div>

<script src="{{ asset('js/create.js') }}"></script>
@vite('resources/js/create.js')
@endsection

