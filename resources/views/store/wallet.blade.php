@if(isset($wallet) && $wallet)
    <div class="last-update">
        آخر تحديث: {{ $wallet->updated_at->diffForHumans() }}
    </div>
@endif