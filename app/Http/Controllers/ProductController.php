<?php

namespace App\Http\Controllers;

use App\DTO\ProductDTO;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private function createProduct($data)
    {
        return Product::create($data);
    }

    private function productResource($product)
    {
        return new ProductResource($product);
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Product::query();

        if (!empty($request->search)) {
            $query->whereRaw("LOWER(name) like ?", '%'.strtolower($request->search).'%');
        }

        $page = $request->page ?? 1;
        $take = $request->per_page ?? 10;
        $count = $query->count();

        if ($page) {
            $skip = $take * ($page - 1);
            $products = $query->take($take)->skip($skip);
        } else {
            $products = $query->take($take)->skip(0);
        }

        return response()->json([
            'data' => ProductResource::collection($products->get()),
            'pagination'=>[
                'count_pages' => ceil($count / $take),
                'count' => $count
            ]
        ], 201);
    }

    public function show($id)
    {
        try {
            $product = new ProductResource(Product::find($id));

            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function store(ProductRequest $request)
    {
        $productDTO = new ProductDTO($request->validated());

        $productData = [
            'name' => $productDTO->name,
            'price' => $productDTO->price,
            'description' => $productDTO->description,
            'available' => $productDTO->available,
        ];

        $product = $this->createProduct($productData);

        if (!empty($productDTO->image)) {
            $product->addMedia($productDTO->image)
                ->toMediaCollection();
        }

        return response()->json($this->productResource($product), 201);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $productDTO = new ProductDTO($request->validated());

        $productData = [
            'name' => $productDTO->name,
            'price' => $productDTO->price,
            'description' => $productDTO->description,
            'available' => $productDTO->available,
        ];

        $product->update($productData);

        $product->clearMediaCollection();
        if (!empty($productDTO->image)) {
            $product->addMedia($productDTO->image)
                ->toMediaCollection();
        }

        return response()->json($this->productResource($product), 200);
    }

    public function destroy($productId)
    {
        try {
            $product = Product::findOrFail($productId);
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
}
