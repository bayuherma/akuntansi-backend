<?php

namespace App\Http\Controllers\API;

use App\Models\ProductUnit;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Exception;

class ProductUnitController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $show_product = $request->input('show_product');

        if ($id) {
            $unit = ProductUnit::with(['products'])->find($id);

            if ($unit) {
                return ResponseFormatter::success(
                    $unit,
                    'Data satuan berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data satuan tidak ada',
                    404
                );
            }
        }

        $unit = ProductUnit::query();

        if ($name) {
            $unit->where('name', 'like', '%' . $name . '%');
        }

        if ($show_product) {
            $unit->with('products');
        }

        return ResponseFormatter::success(
            $unit->paginate($limit),
            'Data satuan berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' =>'required|string|max:255',
            ]);

            $unit = ProductUnit::create([
                'name' => $request->name,
            ]);

            return ResponseFormatter::success([
                'unit' => $unit,
            ], 'Berhasil tambah satuan');


        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $error,
                ],
                'Gagal tambah satuan',
                500
            );
        }
    }
}
