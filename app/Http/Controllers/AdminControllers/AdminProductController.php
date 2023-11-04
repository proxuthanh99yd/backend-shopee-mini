<?php

namespace App\Http\Controllers\AdminControllers;

use App\Helper\CreateSlug;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ClassificationGroup;
use App\Models\Classify;
use App\Models\Product;
use App\Models\Thumbnail;
use Error;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request, string $filter)
    {
        try {
            if ($request->query('query')) {
                switch ($filter) {
                    case 'all':
                        return Product::where('name', 'like', '%'.$request->query('query').'%')->paginate(10);
                    case 'hidden':
                        return Product::where('name', 'like', '%'.$request->query('query').'%')->where('active', 0)->paginate(10);
                    case 'out_of_stock':
                        $classify = Classify::where("stock", 0)->get("classification_group_id");
                        $classification = ClassificationGroup::whereIn("id", $classify)->get("product_id");
                        $arr = [];
                        for ($i = 0; $i < count($classification); $i++) {
                            $arr[$i] = $classification[$i]->product_id;
                        }
                        return Product::where('name', 'like', '%'.$request->query('query').'%')->whereIn('id', $arr)->paginate(10);
                    case 'active':
                        return Product::where('name', 'like', '%'.$request->query('query').'%')->where('active', 1)->paginate(10);
                    default:
                        throw new Exception("Something wrong", 500);
                }
            } else {
                switch ($filter) {
                    case 'all':
                        return Product::paginate(20);
                    case 'hidden':
                        return Product::where('active', 0)->paginate(20);
                    case 'out_of_stock':
                        $classify = Classify::where("stock", 0)->get("classification_group_id");
                        $classification = ClassificationGroup::whereIn("id", $classify)->get("product_id");
                        return Product::whereIn('id', $classification)->paginate(20);
                    case 'active':
                        return Product::where('active', 1)->paginate(20);
                    default:
                        throw new Exception("Something wrong", 500);
                }
            }
            //            return Product::with('thumbnails', 'categories', 'brands', 'classification', 'classify')->paginate(10);
        } catch (\Exception $th) {
            //            throw new Exception("Something wrong", 500);
            return $th;
        }
    }

    /**
     * @throws Exception
     */
    public function show(string $id)
    {
        try {
            return response()->json([
                'product' => Product::with('thumbnails', 'categories', 'brands', 'classification')->find($id),
                'categories' => Category::all(),
                'brands' => Brand::all()
                ]);
        } catch (\Exception $ex) {
            throw new Exception("Something wrong!", 500);
        }
    }

    /**
     * @throws Exception
     */
    public function store(Request $request)
    {
        try {
            $path = storage_path('app/public/images/');
            !is_dir($path) &&
            mkdir($path, 0777, true);

            $name = $request->input("name");
            $description = $request->input("description");
            $image = $request->file("image");
            $active = $request->input("active");
            $discount = $request->input("discount");
            $category_id = $request->input("category_id");
            $brand_id = $request->input("brand_id");
            $thumbnails = $request->file("thumbnail");
            $variationName = $request->input('variation_name');
            $variation = $request->input('variation');
            $price = $request->input('price');
            $stock = $request->input('stock');

            $product = new Product();
            $product->name = $name;
            $product->description = $description;
            $product->active = $active;
            $product->discount = $discount;
            $product->category_id = $category_id;
            $product->brand_id = $brand_id;


            $imageName = CreateSlug::create_slug($name) . '-' . time() . '.' . $image->extension();
            $product->image = $imageName;
            $image->move($path, $imageName);

            $product->save();

            $classificationGroups = new ClassificationGroup();
            $classificationGroups->name = $variationName;
            $classificationGroups->product_id = $product->id;
            $classificationGroups->save();
            for ($i = 0; $i < count($variation); $i++) {
                $classify = new Classify();
                $classify->name = $variation[$i + 1];
                $classify->price = $price[$i + 1];
                $classify->stock = $stock[$i + 1];
                $classify->classification_group_id = $classificationGroups->id;
                $classify->save();
            }

            foreach ($thumbnails as $thumbImg) {
                $thumb = new Thumbnail();
                $thumbName = 'thumb-' . uniqid() . '.' . $thumbImg->extension();
                $thumb->product_id = $product->id;
                $thumb->name = $thumbName;
                $thumbImg->move($path, $thumbName);
                $thumb->save();
            }

            if ($product->id) {
                return $product;
            }

            throw new Error("Create product failed!");
        } catch (\Exception $ex) {
            throw new Exception("Something wrong!");
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, string $id)
    {
        try {
            $path = storage_path('app/public/images/');
            !is_dir($path) &&
            mkdir($path, 0777, true);

            $name = $request->input("name");
            $description = $request->input("description");
            $image = $request->file("image");
            $active = $request->input("active");
            $discount = $request->input("discount");
            $category_id = $request->input("category_id");
            $brand_id = $request->input("brand_id");
            $thumbnail_id = $request->input("thumbnail_id");
            $thumbnails = $request->input("thumbnail");
            $thumbnailImage = $request->file("thumbnail");
            $variationName = $request->input('variation_name');
            $variation = $request->input('variation');
            $variation_id = $request->input('variation_id');
            $price = $request->input('price');
            $stock = $request->input('stock');
            $product = Product::find($id);
            $product->name = $name;
            $product->description = $description;
            $product->active = $active;
            $product->discount = $discount;
            $product->category_id = $category_id;
            $product->brand_id = $brand_id;

            if ($image) {
                Storage::delete('public/images/' . $product->image);
                $imageName = CreateSlug::create_slug($name) . '-' . time() . '.' . $image->extension();
                $product->image = $imageName;
                $image->move($path, $imageName);
            }
            $product->save();

            $classificationGroup = ClassificationGroup::find($product->id);
            $classificationGroup->name = $variationName;
            $classificationGroup->save();

            //Delete variation
            $variation_ids = Classify::where('classification_group_id', $classificationGroup->id)->get();
            foreach ($variation_ids as $var) {
                if ($variation_id) {
                    if (!in_array($var->id, $variation_id)) {
                        $findVar = Classify::find($var->id);
                        $findVar->delete();
                    }
                } else {
                    $findVar = Classify::find($var->id);
                    $findVar->delete();
                }
            }

            //Edit variation and add new variation
            $countVar = 0;
            if ($variation) {
                $countVar += count($variation);
            }
            for ($i = 0; $i < $countVar; $i++) {
                if (isset($variation_id[$i + 1])) {
                    $findVar = Classify::find($variation_id[$i + 1]);
                    $findVar->name = $variation[$i + 1];
                    $findVar->price = $price[$i + 1];
                    $findVar->stock = $stock[$i + 1];
                    $findVar->save();

                } else {
                    $newClassify = new Classify();
                    $newClassify->name = $variation[$i + 1];
                    $newClassify->price = $price[$i + 1];
                    $newClassify->stock = $stock[$i + 1];
                    $newClassify->classification_group_id = $classificationGroup->id;
                    $newClassify->save();
                }
            }


            //Delete image
            $thumbnail_ids = Thumbnail::where('product_id', $id)->get();
            foreach ($thumbnail_ids as $thumb) {
                if ($thumbnail_id) {
                    if (!in_array($thumb->id, $thumbnail_id)) {
                        $findThumb = Thumbnail::find($thumb->id);
                        Storage::delete('public/images/' . $findThumb->name);
                        $findThumb->delete();
                    }
                } else {
                    $findThumb = Thumbnail::find($thumb->id);
                    Storage::delete('public/images/' . $findThumb->name);
                    $findThumb->delete();
                }
            }

            //Edit image and add new image
            $countThumb = 0;
            if ($thumbnails) {
                $countThumb += count($thumbnails);
            }
            if ($thumbnailImage) {
                $countThumb += count($thumbnailImage);
            }
            for ($i = 0; $i < $countThumb; $i++) {
                if (isset($thumbnail_id[$i + 1])) {
                    if (isset($thumbnailImage[$i + 1]) && file_exists($thumbnailImage[$i + 1])) {
                        $thumbnail = Thumbnail::find($thumbnail_id[$i + 1]);
                        Storage::delete('public/images/' . $thumbnail->name);
                        $thumbnailName = 'thumb-' . uniqid() . '.' . $thumbnailImage[$i + 1]->extension();
                        $thumbnail->name = $thumbnailName;
                        $thumbnailImage[$i + 1]->move($path, $thumbnailName);
                        $thumbnail->save();
                    }
                } else {
                    if (isset($thumbnailImage[$i + 1]) && file_exists($thumbnailImage[$i + 1])) {
                        $thumb = new Thumbnail();
                        $thumbName = 'thumb-' . uniqid() . '.' . $thumbnailImage[$i + 1]->extension();
                        $thumb->product_id = $product->id;
                        $thumb->name = $thumbName;
                        $thumbnailImage[$i + 1]->move($path, $thumbName);
                        $thumb->save();
                    }
                }
            }

            return Product::with('thumbnails', 'categories', 'brands', 'classification')->find($id);
        } catch (Exception $ex) {
            throw new Exception("Something wrong!", 500);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);
            $thumbnails = Thumbnail::where('product_id', $id)->get();

            if ($product->image) {
                Storage::delete('public/images/' . $product->image);
            }
            if ($thumbnails) {
                foreach ($thumbnails as $thumbImg) {
                    Storage::delete('public/images/' . $thumbImg->name);
                }
            }
            Thumbnail::where('product_id', $id)->delete();
            $product->delete();
            return $id;
        } catch (Exception $ex) {
            throw new Exception("Something wrong!", 500);
        }
    }

    public function active(string $id)
    {
        try {
            $product = Product::find($id);
            if ($product->active == 1) {
                $product->active = 0;
            } else {
                $product->active = 1;
            }
            $product->save();
            return $product;
        } catch (Exception $exception) {
            throw new Error("change status failed!", 500);
        }
    }

    public function categoriesBrands(): JsonResponse
    {
        try {
            return response()->json([
                'categories' => Category::all(),
                'brands' => Brand::all()
            ]);
        } catch (Exception $exception) {
            throw new Error("change status failed!s", 500);
        }
    }
}
