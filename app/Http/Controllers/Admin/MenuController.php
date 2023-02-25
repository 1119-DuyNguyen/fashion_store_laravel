<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFormRequest;
use App\Http\Services\Menu\MenuService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class MenuController extends Controller
{
    //
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function create()
    {

        return view('admin.menu.add', [
            'title' => 'Thêm Danh Mục Mới',
            'menus' => $this->menuService->getParent()
            // 'menus' => []
        ]);
    }

    public function store(CreateFormRequest $request)
    {

        $this->menuService->create($request);

        return redirect()->back();
    }

    public function index()
    {
        return view('admin.menu.list', [
            'title' => 'Danh Sách Danh Mục Mới Nhất',
            'menus' => $this->menuService->getAll()
        ]);
    }
    // show để edit, Menu với tham số truyền vào tự động kiểm tra có tồn tại
    public function show(\App\Models\Menu $menu)
    {
        return view('admin.menu.edit', [
            'title' => 'Chỉnh Sửa Danh Mục: ' . $menu->name,
            'menu' => $menu,
            'menus' => $this->menuService->getParent()
        ]);
    }

    public function update(\App\Models\Menu $menu, CreateFormRequest $request)
    {
        $this->menuService->update($request, $menu);

        return redirect('/admin/menus/list');
    }

    public function destroy(Request $request) : JsonResponse
    {

        $result = $this->menuService->destroy($request);
        if ($result) {

            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công danh mục'
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'có lỗi gì đó'
        ]);
    }
}