<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Error;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $sort = $request->query();
            $products = Product::with('thumbnails', 'categories', 'brands', 'classification', 'classify')->where('active',1);
            foreach ($sort as $index => $item) {
                if ($index == 'sort') {
                    $arr = explode(".", $item);
                    $products = $products->orderBy($arr[0], $arr[1]);
                }
                if ($index == 'brand' && $item != null) {
                    $brands = Brand::where('name', $item)->get('id');
                    $products = $products->whereIn('brand_id', $brands);
                }
                if ($index == 'category' && $item != null) {
                    $categories = Category::where('name', $item)->get('id');
                    $products = $products->whereIn('category_id', $categories);

                }
                if ($index == 'search' && $item != null){
                    $products = $products->where('name', 'like','%'. $item.'%');
                }
            }
            $products = $products->paginate(20);
            return $products;
        } catch (Exception $exception) {
            throw new Error('Query Failed!', 500);
        }
    }

    /**
     * @throws Exception
     */
    public function show(string $id)
    {
        try {
            return Product::with('thumbnails', 'categories', 'brands', 'classification')->find($id);
        } catch (Exception $ex) {
            throw new Exception("Something wrong!", 500);
        }
    }
}
