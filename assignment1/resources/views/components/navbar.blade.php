<nav class="shadow-sm">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between py-3 mb-4 border-bottom">
            {{-- logo --}}
            <div>
                <a href="/" class="d-flex align-items-center text-decoration-none">
                    <img src="{{asset('/images/logo.png')}}" class="me-2" height="32">
                    <div class="d-none d-sm-block fs-4">Easy Stay</div>
                </a>
            </div>
    
            {{-- menus: listings, owners --}}
            <div>
                <a href="/"><button type="button" class="btn btn-outline-primary me-2">Listings</button></a>
                <a href="/owners"><button type="button" class="btn btn-outline-primary">Owners</button></a>
            </div>
    
            {{-- create --}}
            <div>
                <a href="/listings/create"><button type="button" class="btn btn-outline-primary">Create</button></a>
            </div>
        </div>
    </div>
</nav>