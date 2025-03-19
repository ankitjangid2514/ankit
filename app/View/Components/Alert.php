<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public $type;
    public $message;

    /**
     * Create a new component instance.
     *
     * @param string|null $type
     * @param string|null $message
     */
    public function __construct($type = null, $message = null)
    {
        // Set the type and message, defaulting to empty strings if not provided
        $this->type = $type ?: 'success';  // Default to 'success' if not provided
        $this->message = $message ?: '';   // Default to empty string if no message
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.alert');
    }
}
