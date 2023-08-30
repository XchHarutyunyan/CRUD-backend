<?php

namespace App\Http\Controllers;

use App\DTO\CategoryDTO;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;

class CategoryController extends Controller
{

    public function store(StoreCategoryRequest $request)
    {
        $categoryDTO = new CategoryDTO($request->validated());

        $category = new Category();
        $category->name = $categoryDTO->name;
        $category->save();

        if ($categoryDTO->subcategories) {
            if (count($categoryDTO->subcategories) >= 10) {
                return response()->json(['error' => 'You can only have up to 10 subcategories per category'], 400);
            }

            foreach ($categoryDTO->subcategories as $subcatData) {
                $subcategory = new Subcategory();
                $subcategory->category_id = $category->id;
                $subcategory->name = $subcatData['name'];
                $subcategory->save();
            }
        }

        $category->load('subcategories');

        return response()->json($category, 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->subcategories()->delete();
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully']);
        }
        return response()->json(['message' => 'Category not found'], 404);
    }
}
