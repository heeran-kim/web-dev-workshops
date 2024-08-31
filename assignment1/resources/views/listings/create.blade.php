<x-master title="| Create">
    <div class="d-flex justify-content-center">
        <div class="bg-light p-3 border rounded shadow-sm" style="width: 800px;">
            <h3 class="text-center mt-3 p-1 text-uppercase">Create</h3>
            <h5 class="text-center">Create your listing and find your wonderful roommates</h5>
            <form method="POST" action="/listings" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" placeholder="Cozy Studio">
                </div>
                <div class="col-md-12">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" id="name" placeholder="John Doe">
                </div>
                <div class="col-md-6">
                  <label for="rent" class="form-label">Rent</label>
                  <input type="text" class="form-control" id="rent" placeholder="Rent per week">
                </div>
                <div class="col-md-6">
                  <label for="rent" class="form-label">Available Date</label>
                  <input type="date" class="form-control" id="date">
                </div>
                <div class="col-12">
                  <label for="address" class="form-label">Address</label>
                  <input type="text" class="form-control" id="address" placeholder="1234 Main St">
                </div>
                <div class="col-md-6">
                  <label for="city" class="form-label">City</label>
                  <input type="text" class="form-control" id="city" placeholder="Sydney">
                </div>
                <div class="col-md-6">
                  <label for="inputState" class="form-label">State</label>
                  <select id="inputState" class="form-select">
                    <option value="1">NSW</option>
                    <option value="2">QLD</option>
                    <option value="3">VIC</option>
                    <option value="4">NT</option>
                  </select>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="bill">
                        <label class="form-check-label" for="bill">
                            Is Bill Included
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="furnished">
                        <label class="form-check-label" for="furnished">
                            Is Furnished
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter a detailed description of the property, highlighting key features and amenities."></textarea>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</x-master>