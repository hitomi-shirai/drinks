<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 商品一覧を取るための準備
        $query = Product::query();

        // 商品名で検索された場合（部分一致）
        if (!empty($request->search)) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        // メーカーが選ばれている場合
        if (!empty($request->company_id)) {
            $query->where('company_id', $request->company_id);
        }

        // 結果をページ分け（5件ずつ）＋会社情報も一緒に取得
        $products = $query->with('company')->paginate(5)->appends($request->all());

        // メーカー一覧（セレクトボックス用）
        $companies = Company::all();

        // 画面に送る
        return view('products.index', compact('products', 'companies'));    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request -> validate ([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
    ]);

    $request->merge([
        'stock' => mb_convert_kana($request->stock, 'n'), // 全角→半角に変換
        'price' => mb_convert_kana($request->price, 'n'),
    ]);  
    
    DB::beginTransaction();
    try {
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->company_id = $request->company_id;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->comment = $request->comment;
        

        if($request -> hasFile('img_path')){ 
            $file = $request->file('img_path');
            $path = $file->store('products', 'public'); // storage/app/public/products に保存される
            $product->img_path = 'storage/' . $path; 
        }

        $product -> save();

        DB::commit();
        return redirect()->route('products.create')->with('success', '商品を登録しました');
    }catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', '商品登録に失敗しました: ' . $e->getMessage());
    }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $companies = Company::all();
        return view('products.show', ['product' => $product] , compact('companies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //編集画面の表示
    public function edit($id)
    {
        $product = Product::find($id); 
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id); // IDから商品を探す  

        $request -> validate ([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
        ]);

        $request->merge([
            'stock' => mb_convert_kana($request->stock, 'n'), // 全角→半角に変換
            'price' => mb_convert_kana($request->price, 'n'),
        ]);    
    
    DB::beginTransaction();
    try {
            $product = Product::findOrFail($id);  

            //入力された値で上書きをする
            $product -> product_name = $request -> input('product_name');
            $product -> company_id = $request -> input('company_id');
            $product -> price = $request -> input('price');
            $product -> stock = $request -> input('stock');
            $product -> comment = $request -> input('comment');

            if($request -> hasFile('img_path')){ 
                $filename = $request -> img_path -> getClientOriginalName();
                $filePath = $request -> img_path -> storeAs('products', $filename, 'public');
                $product -> img_path = 'storage/' . $filePath;
            }
        
            $product -> save();

            DB::commit();
            return redirect() -> route('products.edit', $product -> id)->with('success', '商品を更新しました');
    }catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', '商品更新に失敗しました: ' . $e->getMessage());
    } 
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
    DB::beginTransaction();
    try{
        $product -> delete();
        DB::commit();

    // AjaxリクエストならJSONを返す
    if (request()->ajax()) {
        return response()->json(['success' => true]);
    }        

        return redirect()->route('products.index')->with('success', '商品を削除しました');        
    }catch (\Exception $e) {
        DB::rollBack();

        if (request()->ajax()) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }        
        return back()->with('error', '商品削除に失敗しました: ' . $e->getMessage());
    }
    
    }




    public function searchAjax(Request $request)
    {
        $query = Product::with('company');
    
        // 商品名検索
        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }
    
        // メーカー検索
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // 価格範囲
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        
        // 在庫範囲
        if ($request->filled('stock_min')) {
            $query->where('stock', '>=', $request->stock_min);
        }
        if ($request->filled('stock_max')) {
            $query->where('stock', '<=', $request->stock_max);
        } 
        
        // ソート（安全のためホワイトリストで確認）
        $allowed = ['id', 'product_name', 'price', 'stock', 'company_id'];
        $sortColumn = $request->input('sort_column', 'id');
        $sortOrder = $request->input('sort_order', 'desc') === 'asc' ? 'asc' : 'desc';

        if (in_array($sortColumn, $allowed)) {
            $query->orderBy($sortColumn, $sortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }
        
        // ページネーション　５件
        $products = $query->paginate(5);


        // products->items() はモデル配列（会社情報含む）なので、配列に変換して返すと安全
        $items = array_map(function($p) {
            return [
                'id' => $p->id,
                'product_name' => $p->product_name,
                'price' => $p->price,
                'stock' => $p->stock,
                'img_path' => $p->img_path,
                'company' => $p->company ? ['company_name' => $p->company->company_name] : null,
            ];
        }, $products->items());

        return response()->json([
            'data' => $items, // 5件分のデータ
            'pagination' => [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'total' => $products->total(),
            ]
        ]);    
    }
}
