<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class AdminBrandController extends Controller
{
    /**
     * @throws Exception
     */
    public function store(Request $request)
    {
        try {

            $brand = new Brand();
            $brand->name = $request->input("name");
            $brand->save();
            return $brand;
        } catch (Exception $th) {
            throw new Exception("Something wrong!", 500);
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, string $id)
    {
        try {
            $brand = Brand::find($id);
            $brand->name = $request->input("name");
            $brand->save();
            return $brand;
        } catch (Exception $th) {
            throw new Exception("Something wrong!", 500);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(string $id)
    {
        try {
            $brand = Brand::find($id);
            $isProduct = Product::where("brand_id", $id)->exists();
            if ($isProduct) {
                throw new Exception("can not delete brand because product exist!", 500);
            }
            $brand->delete();
            return $brand->id;
        } catch (\Exception $th) {
            throw new Exception("Something wrong!", 500);
        }
    }
}
