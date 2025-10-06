<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // 既存の外部キーを削除（制約名は sales_product_id_foreign がデフォルト）
            $table->dropForeign('sales_product_id_foreign');
    
            // 外部キーを再定義（カラム自体は作り直さない！）
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
    
            // 新しいカラムを追加
            $table->integer('quantity')->after('product_id');
            $table->integer('price')->after('quantity');
        });
    }
    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            // 追加したカラムを削除
            $table->dropColumn(['quantity', 'price']);
    
            // 外部キー削除して元に戻す
            $table->dropForeign('sales_product_id_foreign');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }
        };
