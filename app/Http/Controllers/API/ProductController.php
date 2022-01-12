<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $categories = $request->input('categories');
        $tags = $request->input('tags');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if ($id) {
            $product = Product::with(['category', 'unit', 'galleries'])->find($id);

            if ($product) {
                return ResponseFormatter::success($product, 'Data produk berhasil diambil');
            } else {
                return ResponseFormatter::error(null, 'Data produk tidak ada', 404);
            }

        // return dd($product);

        }

        $product = Product::with(['category', 'unit', 'galleries']);

        if ($name) {
            $product->where('name', 'like', '%' . $name . '%');
        }
        if ($tags) {
            $product->where('tags', 'like', '%' . $tags . '%');
        }
        if ($price_from) {
            $product->where('price', '>=', $price_from);
        }
        if ($price_to) {
            $product->where('price', '<=', $price_to);
        }
        if ($categories) {
            $product->where('categories', $categories);
        }


        // return dd($product);
        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data produk berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' =>'required|string|max:255',
                'name' =>'required|string|max:255',
                'selling_price' => 'required',
                'stock' => 'required',
                'categories_id' => 'required',
                'units_id' => 'required'
            ]);

            $product = Product::create([
                'code' => $request->code,
                'name' => $request->name,
                'purchase_price' => $request->purchase_price,
                'selling_price' => $request->selling_price,
                'packaging' => $request->packaging,
                'margin' => $request->margin,
                'discount' => $request->discount,
                'stock' => $request->stock,
                'tags' => $request->tags,
                'categories_id' => ProductCategory::find($request->categories_id)->id,
                'units_id' => ProductUnit::find($request->units_id)->id,
            ]);

            return ResponseFormatter::success([
                'product' => $product,
            ], 'Berhasil tambah produk');


        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $error,
                ],
                'Gagal tambah produk',
                500
            );
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' =>'required|string|max:255',
                'selling_price' => 'required',
                'stock' => 'required',
                'categories_id' => 'required',
                'units_id' => 'required'
            ]);

            $product = Product::find($id);
            $data = $request->all();
            $product->update($data);

            return ResponseFormatter::success([
                'product' => $product,
            ], 'Berhasil update produk');


        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $error,
                ],
                'Gagal update produk',
                500
            );
        }
    }
}
