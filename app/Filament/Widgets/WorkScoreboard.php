<?php  
  
namespace App\Filament\Widgets;  
  
use App\Models\Work;  
use Filament\Widgets\ChartWidget;  
  
class WorkScoreboard extends ChartWidget  
{  
    protected static ?string $heading = 'Work Scoreboard';  
  
    protected function getFilters(): ?array  
    {  
        return [  
            'today' => 'Today',  
            'week' => 'Last Week',  
            'month' => 'Last Month',  
            'year' => 'Last Year',  
        ];  
    }  
  
    protected function getData(): array  
    {  
        $query = Work::query();  
  
        if ($this->filter === 'today') {  
            $query->whereDate('created_at', today());  
        } elseif ($this->filter === 'week') {  
            $query->whereBetween('created_at', [now()->subWeek(), now()]);  
        } elseif ($this->filter === 'month') {  
            $query->whereBetween('created_at', [now()->subMonth(), now()]);  
        } elseif ($this->filter === 'year') {  
            $query->whereBetween('created_at', [now()->subYear(), now()]);  
        }  
  
        $totalWorks = $query->count();  
        $completedWorks = $query->where('status', 'completed')->count();  
        $inProgressWorks = $query->where('status', 'in_progress')->count();  
        $pendingWorks = $query->where('status', 'pending')->count();  
  
        $progressPercentage = $totalWorks > 0 ? ($completedWorks / $totalWorks) * 100 : 0;  
  
        return [  
            'datasets' => [  
                [  
                    'label' => 'Work Status',  
                    'data' => [$completedWorks, $inProgressWorks, $pendingWorks],  
                ],  
            ],  
            'labels' => ['Completed', 'In Progress', 'Pending'],  
            'meta' => [  
                'totalWorks' => $totalWorks,  
                'completedWorks' => $completedWorks,  
                'inProgressWorks' => $inProgressWorks,  
                'pendingWorks' => $pendingWorks,  
                'progressPercentage' => $progressPercentage,  
            ],  
        ];  
    }  
  
    protected function getType(): string  
    {  
        return 'pie';  
    }  
  
    protected function getOptions(): array  
    {  
        return [  
            'plugins' => [  
                'tooltip' => [  
                    'callbacks' => [  
                        'label' => 'function(context) {  
                            let label = context.label || "";  
                            if (label) {  
                                label += ": ";  
                            }  
                            if (context.parsed !== null) {  
                                label += context.parsed;  
                            }  
                            return label;  
                        }',  
                    ],  
                ],  
            ],  
        ];  
    }  
  
    // Change this method to public  
    public function getHeading(): string  
    {  
        return 'Work Scoreboard';  
    }  
  
    protected function getSubheading(): ?string  
    {  
        return 'Progress and Status Overview';  
    }  
  
    protected function getFooter(): ?string  
    {  
        $meta = $this->getData()['meta'];  
        return "Total Works: {$meta['totalWorks']} | Completed: {$meta['completedWorks']} | In Progress: {$meta['inProgressWorks']} | Pending: {$meta['pendingWorks']} | Progress: {$meta['progressPercentage']}%";  
    }  
}  
