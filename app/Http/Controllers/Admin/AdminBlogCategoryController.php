<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Spatie\Activitylog\Models\Activity;

class AdminBlogCategoryController extends Controller
{
    public function index()
    {
        $trashCount = BlogCategory::onlyTrashed()->count();

        $blog_categories = BlogCategory::get();
        return view('admin.blog_category.index', compact('blog_categories','trashCount'));
    }

    public function create()
    {
        return view('admin.blog_category.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|alpha_dash|unique:blog_categories',
        ]);

        $blog_category = new BlogCategory();
        $blog_category->name = $request->name;
        $blog_category->slug = $request->slug;
        $blog_category->save();

        // Ambil data baru
        $newData = $blog_category->getAttributes();

        // Format log
        $logData = [
            'attributes' => $newData,
            'old' => 'Null' ,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'created',
            'subject_type' => 'App\Models\BlogCategory',
            'subject_id' => $blog_category->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Blog Category '  .$blog_category->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_blog_category_index')->with('success', 'Blog Category berhasil ditambahkan');
    }

    public function edit($id)
    {
        $blog_category = BlogCategory::where('id', $id)->first();
        return view('admin.blog_category.edit', compact('blog_category'));
    }

    public function edit_submit(Request $request, $id)
    {

        $blog_category = BlogCategory::where('id', $id)->first();

        $request->validate([
            'name' => 'required',
            'slug' => 'required|alpha_dash|unique:blog_categories,slug,'. $blog_category->id,
        ]);

        $categories = BlogCategory::findOrFail($id);

        // Simpan data lama
        $oldData = $categories->getAttributes();

        $blog_category->name = $request->name;
        $blog_category->slug = $request->slug;
        $blog_category->update();

        // Ambil data baru
        $newData = $blog_category->getAttributes();

        // Format log
        $logData = [
            'attributes' => $newData,
            'old' => $oldData,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'updated',
            'subject_type' => 'App\Models\BlogCategory',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Blog Category '  .$blog_category->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_blog_category_index')->with('success', 'Blog Category berhasil di perbarui');
    }

    public function delete($id)
    {

        $categories = BlogCategory::findOrFail($id);

        // Simpan data lama
        $oldData = $categories->getAttributes();

        $total = Post::where('blog_category_id', $id)->count();
        if ($total > 0) {
            return redirect()->back()->with('error', 'Blog Caategory is already used in post, so it can not be deleted');
        }

        $blog_category = BlogCategory::where('id', $id)->first();
        $blog_category->delete();

        // Format log
        $logData = [
            'attributes' => 'Null',
            'old' => $oldData,
        ];

        // Simpan log menggunakan insert
        Activity::insert([
            'causer_id' => Auth::guard('admin')->user()->id,
            'causer_type' => 'App\Models\Admin',
            'log_name' => 'default',
            'event' => 'deleted',
            'subject_type' => 'App\Models\BlogCategory',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Blog Category '  .$blog_category->name,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_blog_category_index')->with('success', 'Blog Category berhasil di hapus');
    }
}
