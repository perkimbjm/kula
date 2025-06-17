<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Work;
use Illuminate\Support\Facades\Cache;

class WorkScoreboard extends Component
{
    public $works;
    public $limit = 50;
    public $showAll = false;

    public function mount()
    {
        $this->fetchWorks();
    }

    public function fetchWorks()
    {
        // Cache scoreboard data untuk mengurangi database load
        $cacheKey = 'work_scoreboard_' . $this->limit;

        $this->works = Cache::remember($cacheKey, 300, function () { // Cache 5 menit
            return Work::forDashboard()
                ->limit($this->limit)
                ->get()
                ->toArray();
        });
    }

    public function loadMore()
    {
        $this->limit += 25;
        $this->fetchWorks();
    }

    public function showAllWorks()
    {
        $this->showAll = true;
        $this->limit = 1000; // Reasonable limit untuk performa
        $this->fetchWorks();
    }

    public function refreshData()
    {
        // Clear cache dan refresh data
        Cache::forget('work_scoreboard_' . $this->limit);
        $this->fetchWorks();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Data berhasil direfresh!'
        ]);
    }

    public function render()
    {
        return view('livewire.work-scoreboard');
    }
}
