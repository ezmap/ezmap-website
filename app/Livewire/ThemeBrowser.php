<?php

namespace App\Livewire;

use App\Models\Theme;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Features\SupportPagination\WithoutUrlPagination;

class ThemeBrowser extends Component
{
    use WithPagination, WithoutUrlPagination;

    public string $tag = '';
    public string $col = '';

    public array $tags = ['colorful', 'complex', 'dark', 'greyscale', 'light', 'monochrome', 'no-labels', 'simple', 'two-tone'];
    public array $colors = ['black', 'blue', 'gray', 'green', 'multi', 'orange', 'purple', 'red', 'white', 'yellow'];

    public function setTag(string $tag): void
    {
        $this->tag = in_array($tag, $this->tags) ? $tag : '';
        $this->col = '';
        $this->resetPage();
    }

    public function setColor(string $color): void
    {
        $this->col = in_array($color, $this->colors) ? $color : '';
        $this->tag = '';
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->tag = '';
        $this->col = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Theme::orderBy('name', 'asc');

        if ($this->col !== '') {
            $query->where('colors', 'LIKE', "%{$this->col}%");
        } elseif ($this->tag !== '') {
            $query->where('tags', 'LIKE', "%{$this->tag}%");
        }

        return view('livewire.theme-browser', [
            'themes' => $query->paginate(24),
            'totalThemes' => Theme::count(),
        ]);
    }
}
