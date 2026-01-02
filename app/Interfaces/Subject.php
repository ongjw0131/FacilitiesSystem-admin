<?php

namespace App\Interfaces;

/**
 * Subject Interface
 * 
 * Defines the contract for subjects in the Observer pattern.
 * A subject manages a collection of observers and notifies them of changes.
 */
interface Subject
{
    /**
     * Attach an observer to this subject
     * 
     * @param Observer $observer
     * @return void
     */
    public function attach(Observer $observer): void;

    /**
     * Detach an observer from this subject
     * 
     * @param Observer $observer
     * @return void
     */
    public function detach(Observer $observer): void;

    /**
     * Notify all attached observers of a change
     * 
     * @return void
     */
    public function notify(): void;
}
