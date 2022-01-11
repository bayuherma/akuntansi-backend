<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Exception;

class ProductCategoryController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $show_product = $request->input('show_product');

        if ($id) {
            $category = ProductCategory::with(['products'])->find($id);

            if ($category) {
                return ResponseFormatter::success(
                    $category, 
                    'Data Kategori berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null, 
                    'Data Kategori tidak ada', 404
                );
            }
        }

        $category = ProductCategory::query();

        if ($name) {
            $category->where('name', 'like', '%' . $name . '%');
        }

        if ($show_product) {
            $category->with('products');
        }

        return ResponseFormatter::success(
            $category->paginate($limit),
            'Data kategori berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' =>'required|string|max:255',
            ]);

            $category = ProductCategory::create([
                'name' => $request->name,
            ]);

            return ResponseFormatter::success([
                'category' => $category,
            ], 'Berhasil tambah kategori');


        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $error,
                ],
                'Gagal tambah kategori',
                500
            );
        }
    }
}
