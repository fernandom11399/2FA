<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormButton extends Component
{
    public $text;

    /**
     * Create a new component instance.
     *
     * @param string $text
     * @return void
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-button');
    }
}
