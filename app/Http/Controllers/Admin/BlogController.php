<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Blog_category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BlogController extends Controller
{
    protected $blog;
    protected $blog_category;

    public function __construct(Blog $blog, Blog_category $blog_category)
    {
        $this->blog = $blog;
        $this->blog_category = $blog_category;
    }

    public function index($type)
    {
        try {
            $page_data['type'] = $type;
            $status = ($type == 'all') ? 1 : 0;
            $page_data['blogs'] = $this->blog->where('status', $status)->get();
            return view('admin.blog.index', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_create()
    {
        try {
            $page_data['categories'] = $this->blog_category->get();
            return view('admin.blog.blog_create', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:125',
            'category' => 'required|max:125',
            'description' => 'required',
            'keyword' => 'required',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {
            $data = [
                'title' => sanitize($request->title),
                'time' => time(),
                'user_id' => auth()->user()->id,
                'category' => sanitize($request->category),
                'description' => $request->description,
                'keyword' => sanitize($request->keyword),
                'status' => (auth()->user()->role == 1) ? 1 : 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_popular' => $request->is_popular ?? 0,
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/blog-images'), $imageName);
                $data['image'] = $imageName;
            } else {
                $data['image'] = 0;
            }

            $this->blog->insert($data);

            Session::flash('success', get_phrase('Blog created successfully!'));
            return redirect($request->is_agent ? 'agent/blogs' : 'admin/blogs/all');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_edit($id)
    {
        try {
            $page_data['blog'] = $this->blog->findOrFail($id);
            $page_data['categories'] = $this->blog_category->get();
            return view('admin.blog.blog_edit', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:125',
            'category' => 'required|max:125',
            'description' => 'required',
            'keyword' => 'required',
        ]);

        try {
            $blog = $this->blog->findOrFail($id);

            $data = [
                'title' => sanitize($request->title),
                'time' => time(),
                'category' => sanitize($request->category),
                'description' => $request->description,
                'keyword' => sanitize($request->keyword),
                'is_popular' => $request->is_popular ?? 0,
                'updated_at' => Carbon::now(),
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/blog-images'), $imageName);
                $data['image'] = $imageName;

                if ($blog->image && is_file(public_path('uploads/blog-images/' . $blog->image))) {
                    unlink(public_path('uploads/blog-images/' . $blog->image));
                }
            }

            $this->blog->where('id', $id)->update($data);

            Session::flash('success', get_phrase('Blog updated successfully!'));
            return redirect($request->is_agent ? 'agent/blogs' : 'admin/blogs/all');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_status($id, $status)
    {
        try {
            $this->blog->where('id', $id)->update(['status' => $status == 1 ? 0 : 1]);
            Session::flash('success', get_phrase('Status updated successfully!'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_delete($id)
    {
        try {
            $blog = $this->blog->findOrFail($id);

            if ($blog->image && file_exists(public_path('uploads/blog-images/' . $blog->image))) {
                unlink(public_path('uploads/blog-images/' . $blog->image));
            }

            $blog->delete();

            Session::flash('success', get_phrase('Blog deleted successfully!'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_category()
    {
        try {
            $page_data['categories'] = $this->blog_category->get();
            return view('admin.blog.blog_category', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_category_create()
    {
        return view('admin.blog.category_create');
    }

    public function blog_category_store(Request $request)
    {
        $request->validate(['name' => 'required|max:255']);

        try {
            $this->blog_category->insert([
                'name' => sanitize($request->name),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            Session::flash('success', get_phrase('Category added successfully!'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_category_edit($id)
    {
        try {
            $page_data['category'] = $this->blog_category->findOrFail($id);
            return view('admin.blog.category_edit', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_category_update(Request $request, $id)
    {
        $request->validate(['name' => 'required|max:255']);

        try {
            $this->blog_category->where('id', $id)->update([
                'name' => sanitize($request->name),
                'updated_at' => Carbon::now(),
            ]);

            Session::flash('success', get_phrase('Category updated successfully!'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function blog_category_delete($id)
    {
        try {
            $this->blog_category->where('id', $id)->delete();
            Session::flash('success', get_phrase('Category deleted successfully!'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // User Blog Functions
    public function user_blogs()
    {
        try {
            $page_data['active'] = 'blogs';
            $page_data['categories'] = $this->blog_category->get();
            $page_data['blogs'] = $this->blog->where('user_id', user('id'))->get();
            return view('user.agent.blogs.index', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function user_create_blog()
    {
        try {
            $page_data['active'] = 'blogs';
            $page_data['categories'] = $this->blog_category->get();
            return view('user.agent.blogs.create', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function user_store_blog(Request $request)
    {
        echo 'test';
    }

    public function user_blog_delete($id)
    {
        try {
            $blog = $this->blog->findOrFail($id);
            $imagePath = public_path('storage/blog-images/' . $blog->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $blog->delete();

            Session::flash('success', get_phrase('Blog deleted successfully!'));
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function user_blog_edit($id)
    {
        try {
            $page_data['active'] = 'blogs';
            $page_data['blog'] = $this->blog->findOrFail($id);
            $page_data['categories'] = $this->blog_category->get();
            return view('user.agent.blogs.edit', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
}
