<?php

namespace App\View\Components;

use App\Models\RejectedPodTransaction;
use Illuminate\View\Component;

class RejectedPod extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $count;

    public function __construct()
    {
        $this->count = RejectedPodTransaction::count();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.rejected-pod');
    }
}
