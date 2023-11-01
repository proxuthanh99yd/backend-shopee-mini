<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    /**
     * @throws Exception
     */
    public function store(Request $request)
    {
        try {
            $category = new Category();
            $category->name = $request->input("name");
            $category->save();
            return $category;
        } catch (\Exception $th) {
            throw new Exception("Something wrong!", 500);
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = Category::find($id);
            $name = $request->input("name");
            $category->name = $name;
            $category->save();
            return $category;
        } catch (\Exception $th) {
            throw new Exception("Something wrong!", 500);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::find($id);
            $isProduct = Product::where("category_id", $id)->exists();
            if ($isProduct) {
                throw new Exception("Can't delete this category!", 500);
            }
            $category->delete();
            return  $category->id;
        } catch (Exception $th) {
            throw new Exception("Something wrong!", 500);
        }
    }
}
