<?php

namespace App\Services\ProductManage;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Str;

class ProductManageService
{
  public function getAllProducts()
  {
    return Product::orderBy('id', 'desc')->get();
  }

  public function getSingleProduct($id)
  {
    return Product::find($id);
  }

  public function saveProductManage(array $data)
  {

    // Process image if provided
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $data['image'] = uploadAndProcessImage($data['image'], 'image/products');
    }

    return Product::create($data);
  }

  public function updateProductManageSingleData($item, array $data)
  {
    // Process image if provided
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $data['image'] = uploadAndProcessImage($data['image'], 'image/products', $item->image);
    }

    $item->update($data);

    return $item;
  }

  public function deleteProductManage($id)
  {
    $item = Product::find($id);
    if ($item) {
      $item->delete();
    }
  }


  public function generateSKU(): string
  {
    $lastProduct = Product::latest('id')->first();
    $nextId = $lastProduct ? $lastProduct->id + 1 : 1;

    return 'PROD-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
  }
}
