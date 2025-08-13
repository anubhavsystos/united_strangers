<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index($type)
    {
        try {
            $page_data['type'] = $type;
            $page_data['categories'] = $this->category->where('type', $type)->get();
            return view('admin.categories.index', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function create_category($type)
    {
        try {
            $page_data['type'] = $type;
            $page_data['parents'] = $this->category->where('parent', 0)->where('type', $type)->get();
            return view('admin.categories.create', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function store_category(Request $request, $type)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        try {
            $data = [
                'name' => sanitize($request->name),
                'parent' => sanitize($request->parent),
                'type' => $type,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $this->category->insert($data);

            Session::flash('success', get_phrase('Category added successful!'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit_category($id)
    {
        try {
            $category_details = $this->category->findOrFail($id);

            $page_data['category_details'] = $category_details;
            $page_data['parents'] = $this->category->where('parent', 0)
                ->where('type', $category_details->type)->get();

            return view('admin.categories.edit', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function update_category(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        try {
            $category_details = $this->category->findOrFail($id);

            $data = [
                'name' => sanitize($request->name),
                'parent' => sanitize($request->parent),
                'type' => sanitize($category_details->type),
                'updated_at' => Carbon::now(),
            ];

            $this->category->where('id', $id)->update($data);

            Session::flash('success', get_phrase('Category update successful!'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function delete_category($id)
    {
        try {
            $this->category->where('id', $id)->delete();
            Session::flash('success', get_phrase('Category deleted successful!'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
}
