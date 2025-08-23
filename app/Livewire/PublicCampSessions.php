<?php

namespace App\Livewire;

use App\Models\CampInstance;
use Livewire\Component;

class PublicCampSessions extends Component
{
    public $searchTerm = '';
    public $gradeFilter = '';
    public $priceFilter = '';

    public function updatedSearchTerm()
    {
        // Reset pagination when search changes
    }

    public function updatedGradeFilter()
    {
        // Reset pagination when filter changes
    }

    public function updatedPriceFilter()
    {
        // Reset pagination when filter changes
    }

    public function getCampInstancesProperty()
    {
        $query = CampInstance::with('camp')
            ->where('is_active', true);

        // Apply search filter
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('camp', function ($campQuery) {
                      $campQuery->where('display_name', 'like', '%' . $this->searchTerm . '%')
                               ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        // Apply grade filter
        if ($this->gradeFilter) {
            switch ($this->gradeFilter) {
                case 'elementary':
                    $query->where('grade_from', '<=', 5)->where('grade_to', '<=', 5);
                    break;
                case 'middle':
                    $query->where(function ($q) {
                        $q->whereBetween('grade_from', [6, 8])
                          ->orWhereBetween('grade_to', [6, 8]);
                    });
                    break;
                case 'high':
                    $query->where('grade_from', '>=', 9);
                    break;
            }
        }

        // Apply price filter
        if ($this->priceFilter) {
            switch ($this->priceFilter) {
                case 'free':
                    $query->where('price', 0);
                    break;
                case 'low':
                    $query->where('price', '>', 0)->where('price', '<=', 100);
                    break;
                case 'medium':
                    $query->where('price', '>', 100)->where('price', '<=', 200);
                    break;
                case 'high':
                    $query->where('price', '>', 200);
                    break;
            }
        }

        return $query->orderBy('start_date')->get();
    }

    public function getGradeSuffix($num)
    {
        if ($num == 1) return 'st';
        if ($num == 2) return 'nd';
        if ($num == 3) return 'rd';
        return 'th';
    }

    public function render()
    {
        return view('livewire.public-camp-sessions')->layout('components.layouts.public');
    }
}
