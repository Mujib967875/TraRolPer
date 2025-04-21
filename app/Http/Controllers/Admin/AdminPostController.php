<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Post;
use Spatie\Activitylog\Models\Activity;
class AdminPostController extends Controller
{
    public function index()
    {
        $trashCount = Post::onlyTrashed()->count();

        $posts = Post::with('blog_category')->orderBy('id', 'desc')->get();
        return view('admin.post.index', compact('posts', 'trashCount'));
    }

    public function create()
    {
        $categories = BlogCategory::get();
        return view('admin.post.create', compact('categories'));
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'title' =>'required',
            'slug' =>'required|alpha_dash|unique:posts',
            'short_description' =>'required',
            'description' =>'required',
            'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'post_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads/'), $final_name);

        $obj = new Post();
        $obj->photo = $final_name;
        $obj->title = $request->title;
        $obj->slug = $request->slug;
        $obj->description = $request->description;
        $obj->short_description = $request->short_description;
        $obj->blog_category_id = $request->blog_category_id;
        $obj->save();


        // Ambil data baru
        $newData = $obj->getAttributes();

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
            'subject_type' => 'App\Models\Post',
            'subject_id' => $obj->id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menambahkan Blog Post ' .$obj->title,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_post_index')->with('success', 'Post berhasil ditambahkan');
    }

    public function edit($id)
    {
        $categories = BlogCategory::get();
        $post = Post::where('id', $id)->first();
        return view('admin.post.edit', compact('post','categories'));
    }

    public function edit_submit(Request $request, $id)
    {

        $post = Post::where('id', $id)->first();

        $request->validate([
            'title' =>'required',
            'slug' =>'required|alpha_dash|unique:posts,slug,'.$id,
            'short_description' =>'required',
            'description' =>'required',
        ]);

        $posts = Post::findOrFail($id);

        // Simpan data lama
        $oldData = $posts->getAttributes();

        if($request->hasFile('photo')){
            $request->validate([
                'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            unlink(public_path('uploads/').$post->photo);

            $final_name = 'post_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads/'), $final_name);
            $post->photo = $final_name;
        }

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->description = $request->description;
        $post->short_description = $request->short_description;
        $post->blog_category_id = $request->blog_category_id;
        $post->update();

        // Ambil data baru
        $newData = $post->getAttributes();

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
            'subject_type' => 'App\Models\Post',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Mengedit Blog Post ' .$post->title,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_post_index')->with('success', 'Post berhasil di perbarui');
    }

    public function delete($id)
    {

        $posts = Post::findOrFail($id);

        // Simpan data lama
        $oldData = $posts->getAttributes();

        $post = Post::where('id', $id)->first();
        File::move(public_path('uploads/'.$post->photo), public_path('trash/'.$post->photo));
        $post->delete();

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
            'subject_type' => 'App\Models\Post',
            'subject_id' => $id,
            'description' => 'Admin ' .Auth::guard('admin')->user()->name. ' Menghapus Blog Post ' .$post->title,
            'properties' => json_encode($logData), // Simpan dalam format JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin_post_index')->with('success', 'Post berhasil di hapus');
    }
}
