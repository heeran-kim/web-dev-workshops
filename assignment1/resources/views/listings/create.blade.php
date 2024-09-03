<x-master title="| Create">
    <div class="d-flex justify-content-center m-3">
        <div class="bg-light p-3 border rounded shadow-sm" style="width: 800px;">
            <h3 class="text-center mt-3 p-1 text-uppercase">Create</h3>
            <h5 class="text-center">Create your listing and find your wonderful roommates</h5>
            
            <form method="POST" action={{url("listings")}} class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" placeholder="Cozy Studio">
                </div>
                <div class="col-12">
                  <label class="form-label">Name</label>
                  <input type="text" class="form-control" name="userName" placeholder="John Doe">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Rent</label>
                  <input type="text" class="form-control" name="rent" placeholder="Rent per week">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Available Date</label>
                  <input type="date" class="form-control" name="availableDate">
                </div>
                <div class="col-12">
                  <label class="form-label">Street</label>
                  <input type="text" class="form-control" name="street" placeholder="1234 Main St">
                </div>
                <div class="col-md-6">
                  <label class="form-label">City</label>
                  <input type="text" class="form-control" name="city" placeholder="Sydney">
                </div>
                <div class="col-md-6">
                  <label class="form-label">State</label>
                  <select name="state" class="form-select">
                    @php
                      $states = ['NSW', 'QLD', 'VIC', 'SA', 'WA', 'TAS', 'NT', 'ACT'];
                    @endphp
                    <option disabled selected>Choose a state or territory</option>
                    @foreach ($states as $state)
                        <option value="{{$state}}"}}>{{$state}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="isBillIncluded">
                        <label class="form-check-label">Is Bill Included</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="isFurnished">
                        <label class="form-check-label">Is Furnished</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" name="description" class="form-control" rows="5" placeholder="Enter a detailed description of the property, highlighting key features and amenities."></textarea>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
            
        </div>
    </div>
</x-master>