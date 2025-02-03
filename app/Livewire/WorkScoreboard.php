<?php  
  
namespace App\Http\Livewire;  
  
use Livewire\Component;  
use App\Models\Work;  
  
class WorkScoreboard extends Component  
{  
    public $works;  
  
    public function mount()  
    {  
        $this->fetchWorks();  
    }  
  
    public function fetchWorks()  
    {  
        $this->works = Work::select('name', 'progress', 'status')->get()->toArray();  
    }  
  
    public function render()  
    {  
        return view('livewire.work-scoreboard');  
    }  
}  
