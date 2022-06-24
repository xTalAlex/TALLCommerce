<?php

namespace App\Http\Livewire\Review;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DestroyForm extends Component
{
    use AuthorizesRequests;
    
    public $review;

    public $confirmingReviewDeletion = false;

    public function mount($review)
    {
        $this->review = $review;
    }

    public function deleteReview()
    {
        $this->authorize('delete', $this->review);

        $this->review->delete();

        return redirect(url()->previous());
    }

    public function render()
    {
        return view('review.destroy-form');
    }
}
