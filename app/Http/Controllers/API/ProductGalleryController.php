<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductGallery;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Exception;

class ProductGalleryController extends Controller
{
    public function store(Request $request, Product $product)
    {
        try {
            $files = $request->file('files');

            if ($request->hasFile('files')) {
                foreach ($files as $file) {
                    $path = $file->store('public/gallery');

                    ProductGallery::create([
                        'products_id' => $product->id,
                        'url' => $path
                    ]);
                }
            }

            return ResponseFormatter::success(
                [
                    $path,
                ],
                'Berhasil tambah gallery'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $error,
                ],
                'Gagal tambah gallery',
                500
            );
        }
    }
}
