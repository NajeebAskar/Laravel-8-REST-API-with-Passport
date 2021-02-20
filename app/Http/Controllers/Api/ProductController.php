<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductStoreRequest;
use App\Http\Requests\Api\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class ProductController extends BaseApiController
{

    public function index()
    {
        $products = Product::with('user:id,name')->get();

        return response()->json(['products' => $products]);

    }

    public function store(ProductStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        auth()->user()->products()->save($product);
        return $this->sendResponse($product, 'Product created successfully.');

    }


    public function show(Product $product)
    {
        if (!$product) {
            return $this->sendError('not found.');

        }
        return $this->sendResponse($product, 'success.');
    }


    public function update(ProductUpdateRequest $request, Product $product)
    {

        if (auth()->user()->id !== $product->user_id) {
            return $this->sendError('Action Forbidden.');
        }

        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['price'] = $request->price;

        $update_product = $product->update($data);
        if (!$update_product) {
            return $this->sendError('there is  Error.');
        }
        return $this->sendResponse($product, 'Product Updated.');
    }


    public function destroy(Product $product)
    {
        $deleted_product = $product->delete();
        if ($deleted_product) {
            return $this->sendResponse($product, 'Product deleted successfully.');
        }
        return $this->sendError('there is  Error.');
    }
}
