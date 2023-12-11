<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Http\Resources\TopicResource;
use Illuminate\Http\Response;

class TopicController extends Controller
{
    /**
     * Display a listing of topics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::with('categories')->get();
        return TopicResource::collection($topics);
    }

    /**
     * Store a newly created topic in storage.
     *
     * @param  \App\Http\Requests\StoreTopicRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTopicRequest $request)
    {
        $validatedData = $request->validated();
        $topic = Topic::where('name', $validatedData['name'])->first();

        if($topic) {
            return response()->json(['message' => 'Topic already exists'], Response::HTTP_CONFLICT);
        }

        $topic = Topic::create($validatedData);

        if ($request->has('category_id')) {
            $topic->categories()->attach($request->category_id);
        }

        return new TopicResource($topic);
    }

    /**
     * Display the specified topic.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($topic);
    }

    /**
     * Update the specified topic in storage.
     *
     * @param  \App\Http\Requests\UpdateTopicRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTopicRequest $request, $id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validated();
        $topic->update($validatedData);

        return response()->json($topic);
    }

    /**
     * Remove the specified topic from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], Response::HTTP_NOT_FOUND);
        }

        $topic->delete();

        return response()->json(['message' => 'Topic deleted'], Response::HTTP_NO_CONTENT);
    }
}
