<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use App\Models\Media;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view('post.manage');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function followers(): Application|Factory|View
    {
        return view('post.followers');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $users = User::all();
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostRequest $request
     *
     * @return void
     */
    public function store(CreatePostRequest $request): void
    {
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     *
     * @return void
     */
    public function show(Post $post): void
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     *
     * @return Application|Factory|View
     */
    public function edit(Post $post): View|Factory|Application
    {
        return view('post.edit', ['post' => $post]);
    }

    public function getPost($id)
    {
        $post = Post::where('id', $id)->with(['postImages'])->get()->first();
        $users = User::all();
        return view('post.edit', ['post' => $post, 'users' => $users]);
    }


    public function deleteImage($id)
    {
        $post = Media::where('id', $id)->delete();
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     *
     * @return void
     */
    public function update(Request $request)
    {

        $post = Post::find($request->id);

        // Update the fields
        $post->title = $request->title;
        $post->body = $request->body;
        $post->location = $request->location;

        // Save the changes
        $post->save();

        if(count($request->tags) !== 0){
            foreach($request->tags as $tag){
                Tag::create([
                    'post_id' => $post->id,
                    'uid' => $tag,
                ]);
            }
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('post-photos', 'public');

            $isImage = preg_match('/^.*\.(png|jpg|gif)$/i', $path);

            Media::create([
                'post_id' => $post->id,
                'uid' => '/public/storage/'.$path,
                'is_image' => $isImage,
            ]);
        }

        return redirect('/post/edit/'.$request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     *
     * @return void
     */
    public function destroy(Post $post): void
    {
    }
}
