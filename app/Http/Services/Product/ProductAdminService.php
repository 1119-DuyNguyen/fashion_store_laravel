<?php


namespace App\Http\Services\Product;


use App\Helpers\Helper;
use App\Models\Menu;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductAdminService
{

    public function getMenu()
    {
        return Menu::where('active', 1)->get();
    }

    protected function isValidPrice($request)
    {
        if (
            $request->input('price') != 0 && $request->input('price_sale') != 0
            && $request->input('price_sale') >= $request->input('price')
        ) {
            Session::flash('error', 'Giá giảm phải nhỏ hơn giá gốc');
            return false;
        }

        if ($request->input('price_sale') != 0 && (int) $request->input('price') == 0) {
            Session::flash('error', 'Vui lòng nhập giá gốc');
            return false;
        }

        return true;
    }

    public function insert($request)
    {
        $isValidPrice = $this->isValidPrice($request);
        $thumbName = Helper::getFileUpload($request, "thumb");

        if ($isValidPrice === false || $thumbName === false)
            return false;

        try {

            $productData = $request->except('_token');
            $productData['thumb'] = $thumbName;
            //tự bảo vệ
            //https://laravel.com/docs/9.x/eloquent#inserts
            // you will need to specify either a fillable or guarded property on your model class.
            Product::create($productData);

            Session::flash('success', 'Thêm Sản phẩm thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Thêm Sản phẩm lỗi');
            //Session::flash('error', $err->getMessage());
            \Log::info($err->getMessage());
            return false;
        }

        return true;
    }

    public function get()
    {
        return Product::with('menu')
            ->orderByDesc('id')->paginate(4);
    }

    public function update(Request $request, $id)
    {

        try {
            $product = Product::findOrFail($id);
            $isValidPrice = $this->isValidPrice($request);
            $thumbName = Helper::getFileUpload($request, "thumb");

            if ($isValidPrice === false)
                return false;
            /**
             * @var Product product
             */


            $data = $request->all();
            // đã có file upload thumbName

            if (!isset($data['thumbDefault'])) {
                $data['thumbDefault'] = "";
            }
            $data['thumb'] =
                ($thumbName === false)
                ? $data['thumbDefault']
                : $thumbName;

            // validate the input here, 
            //use Request to do the job or whatever you like


            $product->update($data);



            //tự bảo vệ
            //https://laravel.com/docs/9.x/eloquent#inserts
            // you will need to specify either a fillable or guarded property on your model class.
            Session::flash('success', 'Cập nhật thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Có lỗi vui lòng thử lại');
            \Log::info($err->getMessage());
            return false;
        }

        return true;
    }

    public function delete($request)
    {
        $product = Product::where('id', $request->input('id'))->first();
        if ($product) {
            $path = str_replace('storage', 'public', $product->thumb);
            Storage::delete($path);
            $product->delete();
            return true;
        }

        return false;
    }
}
