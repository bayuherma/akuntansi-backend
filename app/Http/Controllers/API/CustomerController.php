<?php

namespace App\Http\Controllers\API;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Exception;

class CustomerController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $code = $request->input('code');

        if ($id) {
            $customer = Customer::find($id);

            if ($customer) {
                return ResponseFormatter::success(
                    $customer,
                    'Data customer berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data customer tidak ada',
                    404
                );
            }
        }

        $customer = Customer::query();

        if ($name) {
            $customer->where('name', 'like', '%' . $name . '%');
        }

        if ($code) {
            $customer->where('code', 'like', '%' . $code . '%');
        }

        return ResponseFormatter::success(
            $customer->paginate($limit),
            'Data kategori berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' =>'required|string|max:255',
                'name' =>'required|string|max:255',
            ]);

            $customer = Customer::create([
                'code' => $request->code,
                'name' => $request->name,
            ]);

            return ResponseFormatter::success([
                'customer' => $customer,
            ], 'Berhasil tambah customer');


        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $error,
                ],
                'Gagal tambah customer',
                500
            );
        }
    }
}
