<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectCostCenter extends Component
{
    public $centers;
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
        $centers,
        bool $required,
        string $label = null,
        $value = null
    ) {
        $this->centers = $centers;
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
        return view('components.select-cost-center');
    }
}
