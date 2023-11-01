<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Exception;

class CategoryController extends Controller
{
    /**
     * @throws Exception
     */
    public function index()
    {
        try {
            return Category::paginate(10);
        } catch (Exception $th) {
            throw new Exception("Something wrong!", 500);
        }
    }

}
