<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Exception;

class BrandController extends Controller
{
    /**
     * @throws Exception
     */
    public function index()
    {
        try {
            return Brand::paginate(10);
        } catch (Exception $th) {
            throw new Exception("Something wrong!", 500);
        }
   }
}
