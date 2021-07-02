<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectCategory extends Component
{
    public $categories;
    public $required;
    public $label;
    public $value;
    public $name;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $name,
        $categories,
        bool $required,
        string $label = null,
        $value = null
    ) {
        $this->categories = $categories;
        $this->required = $required;
        $this->label = $label;
        $this->value = $value;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.select-category');
    }
}
