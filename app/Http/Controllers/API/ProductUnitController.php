<?php

namespace App\Http\Controllers\API;

use App\Models\ProductUnit;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

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
}
