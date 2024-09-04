@props(['Rating'])

<small class="text-body-secondary">
    @for ($i=0; $i<5; $i++)
        @if ($i<floor($Rating))
            <i class="bi bi-star-fill"></i>
        @elseif ($i < ceil($Rating) && $Rating - floor($Rating) >= 0.5)
            <i class="bi bi-star-half"></i>
        @else
            <i class="bi bi-star"></i>
        @endif
    @endfor
</small>