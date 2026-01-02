<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use App\Models\File;
use App\Models\Society;
use App\Models\SocietyUser;
use App\Models\Notification;
use App\Observers\PostSubject;
use App\Observers\FollowerNotificationObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Store a newly created post in society
     */
    public function store(Request $request, $societyID)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Please login to post.');
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|max:6144', // 6MB in KB
                'file' => 'nullable|max:10240', // 10MB in KB
            ]);

            // Get society and check if user is member
            $society = Society::where('societyID', $societyID)
                             ->where('isDelete', false)
                             ->firstOrFail();

            $userMembership = SocietyUser::where('userID', Auth::id())
                                   ->where('societyID', $societyID)
                                   ->first();

            if (!$userMembership) {
                return redirect()->back()->with('error', 'You must be a member to post.');
            }

            // Check post permissions based on society settings
            $canPost = false;
            if ($society->whoCanPost === 'president_only') {
                $canPost = $userMembership->position === 'president';
            } elseif ($society->whoCanPost === 'committee') {
                $canPost = in_array($userMembership->position, ['president', 'committee']);
            } elseif ($society->whoCanPost === 'all') {
                $canPost = true;
            }

            if (!$canPost) {
                $message = match($society->whoCanPost) {
                    'president_only' => 'Only the president can create posts in this society.',
                    'committee' => 'Only president and committee members can create posts in this society.',
                    'all' => 'Members cannot create posts in this society.',
                    default => 'You do not have permission to create posts in this society.'
                };
                return redirect()->back()->with('error', $message);
            }

            // Prepare post data
            $postData = [
                'userID' => Auth::id(),
                'societyID' => $societyID,
                'title' => $validated['title'],
                'content' => $validated['content'],
                'isDelete' => false,
            ];

            // Create the post
            $post = Post::create($postData);

            // Handle image upload if provided - use Image model and pivot table
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts/images', 'public');
                $image = Image::create(['filePath' => $imagePath]);
                $post->images()->attach($image->imageID);
            }

            // Handle file upload if provided - use File model and pivot table
            if ($request->hasFile('file')) {
                $uploadedFile = $request->file('file');
                $filePath = $uploadedFile->store('posts/files', 'public');
                $file = File::create([
                    'filePath' => $filePath,
                    'originalName' => $uploadedFile->getClientOriginalName(),
                    'fileSize' => $uploadedFile->getSize()
                ]);
                $post->files()->attach($file->fileID);
            }

            // Use Observer pattern: Create subject and attach observer
            // When post is created, notify all followers of the society
            $postSubject = new PostSubject($post);
            $postSubject->attach(new FollowerNotificationObserver());
            $postSubject->notify();

            return redirect()->back()->with('success', 'Post created and notifications sent!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show a single post with full details
     */
    public function show($societyID, $postID)
    {
        $society = Society::where('societyID', $societyID)
                         ->where('isDelete', false)
                         ->firstOrFail();

        $post = Post::where('postID', $postID)
                   ->where('societyID', $societyID)
                   ->where('isDelete', false)
                   ->with('user', 'comments.user', 'images', 'files')
                   ->firstOrFail();

        $isMember = false;
        if (Auth::check()) {
            $isMember = $society->members->where('userID', Auth::id())->isNotEmpty();
        }

        return view('society.post-detail', compact('society', 'post', 'isMember'));
    }

    /**
     * Delete a post (only by president)
     */
    public function destroy($societyID, $postID)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Please login to delete posts.');
        }

        try {
            $society = Society::where('societyID', $societyID)
                             ->where('isDelete', false)
                             ->firstOrFail();

            $post = Post::where('postID', $postID)
                       ->where('societyID', $societyID)
                       ->where('isDelete', false)
                       ->firstOrFail();

            // Check if user is president or post author
            $userMembership = SocietyUser::where('userID', Auth::id())
                                       ->where('societyID', $societyID)
                                       ->first();

            $canDelete = $userMembership && (
                $userMembership->position === 'president' || 
                $post->userID === Auth::id()
            );

            if (!$canDelete) {
                return redirect()->back()->with('error', 'You do not have permission to delete this post.');
            }

            // Soft delete the post
            $post->update(['isDelete' => true]);

            // Delete all notifications related to this post
            Notification::where('postID', $postID)->delete();

            return redirect()->route('society.show', $societyID)->with('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
