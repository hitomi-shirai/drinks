@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/edit.css') }}">

<div class="container">
    <h1 class="mb-4">商品情報編集画面</h1>

    <div class = "product-form-box">
        <form id="editForm" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')

            <div class = "mb-3">
                <label for = "ID">ID</label>
                <p>{{ old('id', $product->id)}}</p>
            </div>

            
            <div class = "mb-3">
                <label for = "product_name">商品名</label>
                <input type = "text" id = "product_name" name = "product_name" required value="{{ old('product_name', $product->product_name)}}" >
            </div> 
            
            <div class = "mb-3">
                <label for = "company_id">メーカー名</label>
                    <select id = "company_id" name = "company_id" required > 
                        <option value = "" disabled selected></option>
                       @foreach($companies as $company)
                        <option value ="{{$company->id }}" {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                       @endforeach
                    </select>
            </div>  
            
            <div class = "mb-3">
                <label for = "price">価格</label>
                <input type = "text" id = "price" name = "price" required value="{{ old('price', $product->price)}}">
            </div>
            
            <div class = "mb-3">
                <label for = "stock">在庫数</label>
                <input type = "text" id = "stock" name = "stock" required value="{{ old('stock', $product->stock)}}">
            </div>
            
            <div class = "mb-3">
                <label for = "comment">コメント</label>
                <textarea id = "comment" name = "comment"> {{ old('comment', $product->comment)}}</textarea>
            </div>  
            
            <div class = "mb-3">
                <label for = "img_path">商品画像</label>
                <div class = "picture">
                    @if ($product->img_path)
                    <div class="mt-2">
                    <img src="{{ asset($product->img_path) }}"  width="100">
                    </div>
                    <p>現在の商品画像</p>
                    @endif  
                    <input type = "file" id = "img_path" name = "img_path" >
                </div>         
            </div>
            
            <button type = "submit" class = "btn btn-primary">更新</button>
            <a href = "{{ route('products.show', $product->id) }}" class = "btn btn-secondary">戻る</a>
    
        </form>   
    </div>
       
</div>
@vite('resources/js/edit.js')
@endsection

