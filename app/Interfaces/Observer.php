<?php

namespace App\Interfaces;

/**
 * Observer Interface
 * 
 * Defines the contract for observers in the Observer pattern.
 * Any class implementing this can react to changes in a subject.
 */
interface Observer
{
    /**
     * Called when the subject changes
     * 
     * @param Subject $subject The subject that has changed
     * @return void
     */
    public function update(Subject $subject): void;
}
