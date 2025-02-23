<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInput extends Component
{
    public $name;
    public $type;
    public $label;
    public $oldValue;

    /**
     * Create a new component instance.
     *
     * @return void
     */

    public function __construct($name, $type = 'text', $label = '', $oldValue = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->label = $label;
        $this->oldValue = $oldValue ?? old($name);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-input');
    }
}
