<x-master title="| Users">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="m-0">All Owners</h3>
            <x-owner-sort
                action="owners"
                :sort="$sort"
            />
        </div>

        @if (count($owners))
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($owners as $owner)
                    <x-owner-card :owner="$owner"/>
                @endforeach
            </div>
        @else
            <div class="text-center">No Owners Found</div>
        @endif
    </div>
</x-master>