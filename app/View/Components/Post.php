<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Post extends Component
{
    public string $title;
    public string $languages;
    public object $company;
    public string $location;

    public function __construct($post)
    {
        $this->title = $post->job_title;
        $this->languages = implode(', ', $post->languages->pluck('name')->toArray());
        $this->company = $post->company;
        $this->location = $post->location ?? '';
    }

    public function render()
    {
        return view('components.post');
    }
}
