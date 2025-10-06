<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * 購入処理
     */
    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);
        
        // 商品を取得
        $product = Product::findOrFail($validated['product_id']);

        // 在庫チェック
        if ($product->stock < $validated['quantity']) {
            return response()->json([
                'message' => '在庫が不足しています。'
            ], 400);
        }

        // トランザクション開始
        \DB::beginTransaction();

        try {
            // salesテーブルにレコード追加
            $sale = Sale::create([
                'product_id' => $product->id,
                'quantity'   => $validated['quantity'],
                'price'      => $product->price,
            ]);

            // productsテーブルの在庫を減算
            $product->decrement('stock', $validated['quantity']);

            \DB::commit();

            return response()->json([
                'message' => '購入が完了しました',
                'sale' => $sale,
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => '購入処理に失敗しました',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
