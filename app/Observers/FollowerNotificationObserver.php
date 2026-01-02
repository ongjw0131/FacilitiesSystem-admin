<?php

namespace App\Observers;

use App\Models\Notification;
use App\Interfaces\Observer;
use App\Interfaces\Subject;

/**
 * FollowerNotificationObserver
 * 
 * Concrete Observer implementation.
 * When a post is created in a society, this observer creates notifications
 * for all users who are following that society.
 */
class FollowerNotificationObserver implements Observer
{
    /**
     * Handle update when PostSubject notifies
     * Creates notifications for all followers of the society
     */
    public function update(Subject $subject): void
    {
        // Ensure subject is a PostSubject
        if (!$subject instanceof PostSubject) {
            return;
        }

        $post = $subject->getPost();
        $society = $post->society;

        if (!$society) {
            return;
        }

        // Get all followers of this society (users who are following)
        $followers = $society->followers()->get();

        // Create notifications for each follower
        foreach ($followers as $follower) {
            Notification::create([
                'userID' => $follower->userID,
                'societyID' => $society->societyID,
                'postID' => $post->postID,
                'type' => 'post_created',
                'title' => 'New Post in ' . $society->societyName,
                'message' => $post->user->name . ' posted: ' . substr($post->title, 0, 50) . '...',
                'isRead' => false,
            ]);
        }
    }
}
