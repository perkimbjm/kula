<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Facility;
use Illuminate\Support\Facades\Cache;

class WorkScoreboard extends Component
{
    public $facilities;
    public $limit = 50;
    public $showAll = false;

    public function mount()
    {
        $this->fetchFacilities();
    }

    public function fetchFacilities()
    {
        // Cache scoreboard data untuk mengurangi database load
        $cacheKey = 'facility_scoreboard_' . $this->limit;

        $this->facilities = Cache::remember($cacheKey, 300, function () { // Cache 5 menit
            return Facility::with('work')
                ->limit($this->limit)
                ->get()
                ->toArray();
        });
    }

    public function loadMore()
    {
        $this->limit += 25;
        $this->fetchFacilities();
    }

    public function showAllFacilities()
    {
        $this->showAll = true;
        $this->limit = 1000; // Reasonable limit untuk performa
        $this->fetchFacilities();
    }

    public function refreshData()
    {
        // Clear cache dan refresh data
        Cache::forget('facility_scoreboard_' . $this->limit);
        $this->fetchFacilities();

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
