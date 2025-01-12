<div class="progress-bar">
    <div class="progress-bar-value {{ 
        floatval($getState()) < 50 ? 'bg-danger-600' : 
        (floatval($getState()) < 75 ? 'bg-warning-600' : 
        'bg-primary-600') 
    }}" style="width: {{ $getState() }}%;">
        {{ $getState() }}%
    </div>
</div>