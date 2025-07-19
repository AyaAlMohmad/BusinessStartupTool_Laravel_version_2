<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function index()
    {

        // $stories = Story::where('user_id', Auth::id())->get();
        $stories = Story::with('user')->get();
        return response()->json([
            'success' => true,
            'data' => $stories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable',
            'educational' => 'nullable',
            'content' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $imagePath = '/images/' . $imageName;
            }

            $story = Story::create([
                'user_id' => Auth::id(), 
                'educational' => $request->educational,
                'title' => $request->title,
                'content' => $request->content,
                'image' => $imagePath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Story created successfully',
                'data' => $story
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create story',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
      
            $story = Story::with('user')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $story
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Story not found or not owned by you',
                'error' => $e->getMessage()
            ], 404);
        }

    }
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'title' => 'sometimes|nullable',
        //     'educational' => 'sometimes|nullable',
        //     'content' => 'sometimes|nullable',
        //     // 'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ]);
    
    
    
        try {
            $story = Story::where('user_id', Auth::id())->findOrFail($id);
     
       
            $imagePath = null;
            if ($request->hasFile('image')) {
                  // Delete old image if exists
                  if ($story->image && file_exists(public_path($story->image))) {
                    unlink(public_path($story->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $imagePath = '/images/' . $imageName;
            }
 

            $data = [
                'user_id' => Auth::id(),
                'educational' => $request->educational,
                'title' => $request->title,
                'content' => $request->content,
            ];
            
            if ($imagePath) {
                $data['image'] = $imagePath;
            }
            
            $story->update($data);
            

            return response()->json([
                'success' => true,
                'message' => 'Story updated successfully',
                'data' => $story
            ]);
        } catch (\Exception $e) {
       
            return response()->json([
                'success' => false,
                'message' => 'Failed to update story or story not owned by you',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
          
            $story = Story::where('user_id', Auth::id())->findOrFail($id);
            
            // Delete associated image
            if ($story->image && file_exists(public_path($story->image))) {
                unlink(public_path($story->image));
            }
            
            $story->delete();

            return response()->json([
                'success' => true,
                'message' => 'Story deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete story or story not owned by you',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}