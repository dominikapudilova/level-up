<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class KnowledgeTree extends Component
{
    public $edufields;
    public $mode;
    public $course;
    public $formName;

    public function __construct(
        $edufields,
        $mode = 'view',
        $course = null,
        $formName = ''
    ) {
        $this->edufields = $edufields;
        $this->mode = $mode;
        $this->course = $course;
        $this->formName = $formName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.knowledge-tree');
    }

    public function isChecked($course, $knowledge) {
        return isset($course) && $course->knowledge->contains($knowledge);
    }
}
