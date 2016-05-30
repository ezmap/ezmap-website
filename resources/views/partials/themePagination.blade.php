<ul class="pagination pagination-sm">
    @if($themes->currentPage() == 1 )
        <li class="disabled"><span><i class="fa fa-backward"></i></span></li>
    @else
        <li><a href="{{ $themes->previousPageUrl() }}" rel="prev"><i class="fa fa-backward"></i></a></li>
    @endif
    @for($i = 1; $i <= 3; $i++)
        @if($i == $themes->currentPage())
            <li class="active"><span>{{ $i }}</span></li>
        @else
            @if($themes->lastPage() > $i)
                <li><a href="{{ $themes->url($i) }}">{{ $i }}</a></li>
            @endif
        @endif
    @endfor
    @if($themes->currentPage() == 3 || $themes->currentPage() == 4)
        <li class="{{ ($themes->currentPage() == 4) ? 'active':'' }}"><a href="{{ $themes->url(4) }}"> 4 </a></li>

    @elseif($themes->currentPage() >= 5)
        <li class="disabled"><span><i class="fa fa-ellipsis-h"></i></span></li>
    @endif

    @for($i = $themes->currentPage() - 2; $i <= $themes->currentPage() + 2; $i++)
        @if($i > 4 && $i == $themes->currentPage() && $i <= $themes->lastPage() - 3)
            <li class="active"><span>{{ $i }}</span></li>
        @else
            @if($i > 4 && $i < $themes->lastPage() - 2)
                <li><a href="{{ $themes->url($i) }}">{{ $i }}</a></li>
            @endif
        @endif
    @endfor

    @if($themes->currentPage() < $themes->lastPage() - 5)
        <li class="disabled"><span><i class="fa fa-ellipsis-h"></i></span></li>
    @endif
    @for($i = $themes->lastPage() - 2; $i <= $themes->lastPage(); $i++)
        @if($i == $themes->currentPage())
            <li class="active"><span>{{ $i }}</span></li>
        @else
            <li><a href="{{ $themes->url($i) }}">{{ $i }}</a></li>
        @endif
    @endfor

    @if($themes->currentPage() == $themes->lastPage())
        <li class="disabled"><span><i class="fa fa-forward"></i></span></li>
    @else
        <li><a href="{{ $themes->nextPageUrl() }}" rel="next"><i class="fa fa-forward"></i></a></li>
    @endif
</ul>
