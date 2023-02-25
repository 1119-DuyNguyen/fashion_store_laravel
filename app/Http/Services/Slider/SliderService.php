<?php


namespace App\Http\Services\Slider;


use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;

class SliderService
{
    /**
     * @param Request $request
     * 
     * @return [type]
     */
    public function insert($request)
    {

        $thumbName = Helper::getFileUpload($request, "thumb");

        if ($thumbName === false)
            return false;
        try {
            #$request->except('_token');
            $sliderData = $request->except('_token');
            $sliderData['thumb'] = $thumbName;
            //tự bảo vệ
            //https://laravel.com/docs/9.x/eloquent#inserts
            // you will need to specify either a fillable or guarded property on your model class.

            Slider::create($sliderData);
            Session::flash('success', 'Thêm Slider mới thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Thêm Slider LỖI');
            Log::info($err->getMessage());

            return false;
        }

        return true;
    }

    public function get()
    {
        return Slider::orderByDesc('id')->paginate(4);
    }

    /**
     * @param Request $request
     * @param mixed $id
     * 
     * @return [type]
     */
    public function update($request, $id)
    {
        $thumbName = Helper::getFileUpload($request, "thumb");
        try {
            $slider = Slider::findOrFail($id);
            $data = $request->all();
            // đã có file upload thumbName

            if (!isset($data['thumbDefault'])) {
                $data['thumbDefault'] = "";
            }
            $data['thumb'] =
                ($thumbName === false)
                ? $data['thumbDefault']
                : $thumbName;
            $slider->fill($request->input());
            $slider->save();
            Session::flash('success', 'Cập nhật Slider thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Cập nhật slider Lỗi');
            Log::info($err->getMessage());

            return false;
        }

        return true;
    }

    public function destroy($request)
    {
        $slider = Slider::where('id', $request->input('id'))->first();
        if ($slider) {
            $path = str_replace('storage', 'public', $slider->thumb);
            Storage::delete($path);
            $slider->delete();
            return true;
        }

        return false;
    }

    public function show()
    {
        return Slider::where('active', 1)->orderByDesc('sort_by')->get();
    }
}
