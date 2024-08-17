<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use Illuminate\Http\Request;
use \App\Models\News;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class NewsController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     *
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');

        $news = News::when($title, function ($query, $title) {
            return $query->title($title);
        });

        $news = match($filter) {
            'last_month' => $news->lastMonth(),
            'last_6months' => $news->last6Months(),
        default => $news->latest()
        };

        $news = $news->with('user')->paginate(10);

        return NewsResource::collection($news);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'required|string',
            'imgNews'=>'file|mimes:png,jpeg|max:10000',
        ]);

        $file =  $request->file('imgNews');
        $path = $file->store('posts-images', 'public');

        $news = News::create([
            'user_id'=>$request->user()->id,
            'title'=>$validatedData['title'],
            'description'=>$validatedData['description'],
            'imgNewsPath'=>$path
        ]);

        $news->load('user');
        return new NewsResource($news);
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        $news->load('user');
        return new NewsResource($news);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        $news->delete();
        return response(status: 204);
    }
}
