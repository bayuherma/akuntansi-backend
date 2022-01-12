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
    public function store(Request $request)
    {
        try {
            $image = new ProductGallery();
            
            $image->products_id = Product::find($request->products_id)->id;
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images');
                $image->url = $path;
            }
            $image->save();


            // $image = $request->file('image');

            // if ($request->hasFile('image')) {
            //     foreach ($image as $file) {
            //         $path = $file->store('public/gallery');

            //         ProductGallery::create([
            //             'products_id' => $product->id,
            //             'url' => $path
            //         ]);
            //     }
            // }

            // return dd($image);


            return ResponseFormatter::success(
                [
                    $path,
                ],
                'Berhasil tambah gallery'
            );
        } catch (Exception $error) {
            // return dd($image);
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
