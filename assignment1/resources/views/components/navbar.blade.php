<nav class="shadow-sm">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between py-3 mb-4 border-bottom">
            {{-- logo --}}
            <div>
                <a href={{url("/")}} class="d-flex align-items-center text-decoration-none">
                    <img src="{{asset('/images/logo.png')}}" class="me-2" height="32">
                    <div class="d-none d-sm-block fs-4">Easy Stay</div>
                </a>
            </div>
    
            {{-- menus: listings, users --}}
            <div>
                <a href={{url("/")}}><button type="button" class="btn btn-outline-primary me-2">Listings</button></a>
                <a href={{url("users")}}><button type="button" class="btn btn-outline-primary">Users</button></a>
            </div>
    
            {{-- create --}}
            <div>
                <a href={{url("listings/create")}}><button type="button" class="btn btn-outline-primary">Create</button></a>
            </div>
        </div>
    </div>
</nav>