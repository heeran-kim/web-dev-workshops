<x-master title="| Create">
  <!-- Page to create a new listing -->
  <div class="d-flex justify-content-center m-3">
      <div class="bg-light p-3 border rounded shadow-sm" style="width: 800px;">
          <h3 class="text-center mt-3 p-1 text-uppercase">Create</h3>
          <h5 class="text-center">Create your listing to find your roommates</h5>
          
          <!-- Form to submit new listing details -->
          <form method="POST" action="{{url("listings")}}" class="row g-3">
              @csrf
              
              <!-- Input field for title -->
              <div class="col-12">
                  <label class="form-label">Title</label>
                  <input
                    type="text" class="form-control" name="title" placeholder="Cozy Studio"
                    value="{{session('formFields.title', '')}}"
                  >
                  @if (session('errorMessage.title'))
                    <small class="text-danger">{{session('errorMessage.title')}}</small>
                  @endif
              </div>

              <!-- Input field for owner name -->
              <div class="col-12">
                <label class="form-label">Name</label>
                <input
                  type="text" class="form-control" name="ownerName"
                  placeholder="Enter a name (3-20 chars, no special symbols, numbers will be removed)"
                  value="{{session('formFields.ownerName', session('userName', ''))}}"
                >
                @if (session('errorMessage.ownerName'))
                  <small class="text-danger">{{session('errorMessage.ownerName')}}</small>
                @endif
              </div>

              <div class="col-md-6">
                <label class="form-label">Rent</label>
                <input
                  type="text" class="form-control" name="rent" placeholder="Rent per week"
                  value="{{session('formFields.rent', '')}}"
                >
                @if (session('errorMessage.rent'))
                  <small class="text-danger">{{session('errorMessage.rent')}}</small>
                @endif
              </div>

              <div class="col-md-6">
                <label class="form-label">Available Date</label>
                <input
                  type="date" class="form-control" name="availableDate"
                  value="{{session('formFields.availableDate', '')}}"
                >
              </div>

              <div class="col-12">
                <label class="form-label">Street</label>
                <input
                  type="text" class="form-control" name="street" placeholder="1234 Main St"
                  value="{{session('formFields.street', '')}}"
                >
              </div>

              <div class="col-md-6">
                <label class="form-label">City</label>
                <input
                  type="text" class="form-control" name="city" placeholder="Sydney"
                  value="{{session('formFields.city', '')}}"
                >
                @if (session('errorMessage.city'))
                  <small class="text-danger">{{session('errorMessage.city')}}</small>
                @endif
              </div>

              <div class="col-md-6">
                <label class="form-label">State</label>
                <select name="state" class="form-select">
                  @php
                    $states = ['NSW', 'QLD', 'VIC', 'SA', 'WA', 'TAS', 'NT', 'ACT'];
                  @endphp
                  <option disabled selected>Choose a state or territory</option>
                  @foreach ($states as $state)
                      <option
                        value="{{$state}}"
                        {{session('formFields.state') == $state ? 'selected' : ''}}
                      >
                        {{$state}}
                      </option>
                  @endforeach
                </select>
                @if (session('errorMessage.state'))
                  <small class="text-danger">{{session('errorMessage.state')}}</small>
                @endif
              </div>

              <div class="col-6">
                  <div class="form-check">
                      <input
                        class="form-check-input" type="checkbox" name="isBillIncluded"
                        {{session('formFields.isBillIncluded') ? 'checked' : ''}}
                      >
                      <label class="form-check-label">Is Bill Included</label>
                  </div>
              </div>

              <div class="col-6">
                  <div class="form-check">
                      <input
                        class="form-check-input" type="checkbox" name="isFurnished"
                        {{session('formFields.isFurnished') ? 'checked' : ''}}
                      >
                      <label class="form-check-label">Is Furnished</label>
                  </div>
              </div>

              <div class="col-12">
                  <label class="form-label">Description</label>
                  <textarea
                    name="description" name="description" class="form-control" rows="5"
                    placeholder="Enter a detailed description of the property, highlighting key features and amenities."
                  >{{session('formFields.description', '')}}</textarea>
              </div>

              <!-- Submit button -->
              <div class="col-12">
                <button type="submit" class="btn btn-primary">Create</button>
              </div>
          </form>
          
      </div>
  </div>
</x-master>