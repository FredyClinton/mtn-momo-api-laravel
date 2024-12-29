<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\EditPostRequest;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(Request $request)
    {

        $query = Post::query();
        $perPage = 2;
        $page = $request->input('page', 1);
        $search = $request->input('search');

        if ($search) {
            $query->whereRaw("title LIKE '%" . $search . "$%'");
        }
        $total = $query->count();

        $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();


        try {
            //  $posts = Post::all();
            return  response()->json([

                'status_code' => 200,
                'status_message' => $total . ' post(s) ont été recupéré(s) ',
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'items' => $result
            ]);
        } catch (Exception $e) {

            return response()->json($e);
        }
    }

    // createdPostRequest est une classe de validation associée à Post
    public function store(CreatePostRequest $request)
    {

        try {
            $post = new  Post();

            $post->title = $request->title;
            $post->description = $request->description;

            $post->save();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Le post a été ajouté',
                'data' => $post
            ]);
        } catch (Exception $e) {

            return response()->json($e);
        }
    }

    public function update(EditPostRequest $request, $id)
    {
        try {
            $post = Post::find($id);
            if ($post) {
                $post->title = $request->title;
                $post->description = $request->description;
                $post->save();
            }
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Le post a été mis a jour',
                'data' => $post
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function delete(Post $post)
    {
        try {

            $post->delete();
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Le post a été supprimé avec succès',
                'data' => $post
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
