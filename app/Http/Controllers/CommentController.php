<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment
     */
    public function store(Request $request, $societyID, $postID)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Please login to comment'], 401);
        }

        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            // Get post and verify it exists
            $post = Post::where('postID', $postID)
                        ->where('societyID', $societyID)
                        ->where('isDelete', false)
                        ->firstOrFail();

            // Create the comment
            $comment = Comment::create([
                'postID' => $postID,
                'userID' => Auth::id(),
                'content' => $validated['content'],
                'isDelete' => false,
            ]);

            // Load relationships for response
            $comment->load(['user', 'post']);

            return response()->json([
                'message' => 'Comment created successfully',
                'comment' => $comment,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating comment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all comments for a post
     */
    public function getComments($societyID, $postID)
    {
        try {
            // Get post and verify it exists
            $post = Post::where('postID', $postID)
                        ->where('societyID', $societyID)
                        ->where('isDelete', false)
                        ->firstOrFail();

            // Get all non-deleted comments
            $comments = Comment::where('postID', $postID)
                                ->where('isDelete', false)
                                ->with(['user'])
                                ->orderBy('created_at', 'asc')
                                ->get();

            return response()->json([
                'comments' => $comments,
                'total' => $comments->count(),
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching comments: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a comment
     */
    public function destroy($societyID, $postID, $commentID)
    {
        try {
            // Get comment
            $comment = Comment::where('commentID', $commentID)
                              ->where('postID', $postID)
                              ->firstOrFail();

            // Check authorization - user can only delete their own comments or if they're president
            $society = Society::findOrFail($societyID);
            $userRole = $society->members()->where('userID', Auth::id())->first()?->position;
            
            $canDelete = Auth::id() === $comment->userID || $userRole === 'president';

            if (!$canDelete) {
                return response()->json(['message' => 'Unauthorized to delete this comment'], 403);
            }

            // Soft delete the comment
            $comment->update([
                'isDelete' => true,
                'deletedAt' => now(),
            ]);

            return response()->json(['message' => 'Comment deleted successfully']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting comment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a comment
     */
    public function update(Request $request, $societyID, $postID, $commentID)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            // Get comment
            $comment = Comment::where('commentID', $commentID)
                              ->where('postID', $postID)
                              ->where('isDelete', false)
                              ->firstOrFail();

            // Check authorization - only the comment author can update
            if (Auth::id() !== $comment->userID) {
                return response()->json(['message' => 'Unauthorized to update this comment'], 403);
            }

            // Update the comment
            $comment->update(['content' => $validated['content']]);
            $comment->load(['user']);

            return response()->json([
                'message' => 'Comment updated successfully',
                'comment' => $comment,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating comment: ' . $e->getMessage()], 500);
        }
    }
}
