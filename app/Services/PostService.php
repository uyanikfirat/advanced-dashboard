<?php

namespace App\Services;

use Exception;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Gallery;
use App\Models\PostTag;
use App\Models\Category;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Services\BaseService;
use App\Http\Requests\AdRemovalRequest;

class PostService extends BaseService
{

    public function create(AdRemovalRequest $request)
    {
        $createdPost = Post::create($request->except('categories', 'tags', 'thumbnail'))->fresh();

        if ($request->has('thumbnail') && $request->thumbnail !== null) {
            $url = base64_image_resize($createdPost->slug, $request->thumbnail, 'paylasim', 500);
            $createdPost->thumbnail = $url;
        }

        if ($request->has('categories')) {
            PostCategory::where('post_id', $createdPost->id)->whereNotIn('category_id', $request->categories)->delete();
            foreach ($request->categories as $category) {
                $postCategory =  PostCategory::where('category_id', $category)->where('post_id', $createdPost->id)->first();
                if(!$postCategory){
                    PostCategory::create([
                        'post_id' => $createdPost->id,
                        'category_id' => $category
                    ]);
                }
            }
        }

        if ($request->has('tags')) {
            PostTag::where('post_id', $createdPost->id)->whereNotIn('tag_id', $request->tags)->delete();
            foreach ($request->tags as $tag) {
                if(is_int($tag)){
                    $tag = Tag::where('id', $tag)->first();
                    PostTag::where('post_id', $createdPost->id)->where('tag_id', $tag->id)->firstOrCreate([
                        'post_id' => $createdPost->id,
                        'tag_id' => $tag->id
                    ]);
                }
            }
        }

        return $createdPost;
    }

    public function update(AdRemovalRequest $request, $id)
    {
        $postData = Post::where('id', $id)->firstOrFail();

        $postData->fill($request->except('categories', 'tags', 'user', 'galleries','category', 'thumbnail'));

        if ($request->has('thumbnail') && $request->thumbnail !== null) {
            $url = base64_image_resize($postData->slug, $request->thumbnail, 'paylasim', 500);
            $postData->thumbnail = $url;
        }

        if ($request->has('categories') && !empty($request->category_id)) {
            dd($request->category_id);
            PostCategory::where('post_id', $id)->whereNotIn('category_id', $request->categories)->delete();
            foreach ($request->categories as $category) {
                $postCategory =  PostCategory::where('category_id', $category)->where('post_id', $postData->id)->first();
                if(!$postCategory){
                    PostCategory::create([
                        'post_id' => $postData->id,
                        'category_id' => $category
                    ]);
                }
            }
        }

        if ($request->has('tags')) {
            PostTag::where('post_id', $id)->whereNotIn('tag_id', $request->tags)->delete();
            foreach ($request->tags as $tag) {
                if(is_int($tag)){
                    $tag = Tag::where('id', $tag)->first();
                    PostTag::where('post_id', $id)->where('tag_id', $tag->id)->firstOrCreate([
                        'post_id' => $postData->id,
                        'tag_id' => $tag->id
                    ]);
                }
            }
        }

        $postData->save();
        return $postData;
    }

    public function show(Request $request, $id)
    {
       $post =  Post::where('id', $id)
        ->with('tags')
        ->with('categories')
        ->first();

         $post->tags->transform(function ($tag) {
            return $tag->tag->id;
          });
  
          $post->categories->transform(function ($cat) {
            return $cat->category->id;
          });

          return $post;
    }


    public function delete(Request $request, $id, User $user)
    {
        $postData = Post::where('id', $id)->firstOrFail();
        try {
            $postData->delete();
            return $postData;
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'errors' => $e,
            ], 500);
        }
    }

    public function createThumbnails(Post $post)
    {

        $slug = url_image_resize($post->title, $post->thumbnail, 80);
        return $this->thumbnailSaveToGallery($post, $slug);
    }

    public function thumbnailSaveToGallery(Post $post, String $slug)
    {
        Gallery::firstOrCreate([
            'user_id' => $post->user_id,
            'post_id' => $post->id,
            'file_name' => $post->title,
            'slug' => $slug
        ]);

        return true;
    }
}
