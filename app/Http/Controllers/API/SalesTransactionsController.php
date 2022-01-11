<?php

namespace App\Http\Controllers\API;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\SalesTransaction;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\SalesTransactionItem;
use Carbon\Carbon;
use Exception;

class SalesTransactionsController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        // $code = $request->input('code');
        $status = $request->input('status');

        if ($id) {
            $transaction = SalesTransaction::with(['items.product'])->find($id);

            if ($transaction) {
                return ResponseFormatter::success(
                    $transaction,
                    'Data Penjualan berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data penjualan tidak ada',
                    404
                );
            }

            return dd($transaction);
        }
        // $customer = Customer::find($request->customers_id)->id;


        $transaction = SalesTransaction::with(['items.product']);

        if ($status) {
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Data list penjualan berhasil diambil'
        );
    }

    public function transaction(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array',
                'items.*.id' => 'exists:products,id',
                'total_price' => 'required',
                'code' => 'required',
                'status' => 'required|in:TRUE,FALSE',
                'paid_off' => 'required|in:TRUE,FALSE'
            ]);

            $customer = Customer::find($request->customers_id)->id;
            $transaction = SalesTransaction::create([
                'customers_id' => $customer,
                'code' => $request->code,
                'sale_date' => Carbon::now(),
                'total_price' => $request->total_price,
                'status' => $request->status,
                'paid_off' => $request->paid_off,
            ]);

            foreach ($request->items as $product) {
                SalesTransactionItem::create([
                    'customers_id' => $customer,
                    'products_id' => $product['id'],
                    'sales_transaction_id' => $transaction->id,
                    'quantity' => $product['quantity'],
                ]);
            }

            //    return dd($tranx  saction);

            return ResponseFormatter::success(
                $transaction->load('items.product'),
                'Transaksi penjualan berhasil'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    null,
                    'message' => $error,
                ],
                'Gagal melakukan transaksi'
            );
        }
    }
}
