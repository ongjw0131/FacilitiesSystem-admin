<?php

namespace App\Observers;

use App\Models\Post;
use App\Interfaces\Subject;
use App\Interfaces\Observer;

/**
 * PostSubject
 * 
 * Concrete Subject implementation for Post events.
 * When a post is created, this subject notifies all attached observers.
 */
class PostSubject implements Subject
{
    /**
     * The Post instance
     */
    protected Post $post;

    /**
     * Array of attached observers
     */
    protected array $observers = [];

    /**
     * Constructor
     * 
     * @param Post $post The post being observed
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the post instance
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * Attach an observer
     */
    public function attach(Observer $observer): void
    {
        if (!in_array($observer, $this->observers, true)) {
            $this->observers[] = $observer;
        }
    }

    /**
     * Detach an observer
     */
    public function detach(Observer $observer): void
    {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    /**
     * Notify all attached observers
     */
    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
