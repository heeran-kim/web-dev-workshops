<x-master title="| Edit">
    <div class="d-flex justify-content-center m-3">
        <div class="bg-light p-3 border rounded shadow-sm" style="width: 800px;">
            <h3 class="text-center mt-3 p-1 text-uppercase">Edit</h3>
            <h5 class="text-center">Edit your listing and find your wonderful roommates</h5>
            
            <form method="POST" action={{url("listings/$listing->listingId")}} class="row g-3">
              @csrf
              @method('PUT')
              <div class="col-12">
                  <label class="form-label">Title</label>
                  <input
                    type="text" class="form-control" name="title"
                    value="{{session('formFields.title', $listing->title)}}"
                  >
                  @if (session('errorMessage.title'))
                    <small class="text-danger">{{session('errorMessage.title')}}</small>
                  @endif
              </div>
              <div class="col-12">
                <label class="form-label">Name</label>
                <input
                  type="text" class="form-control" name="ownerName" placeholder="John Doe"
                  value="{{session('formFields.ownerName', $listing->ownerName)}}"
                >
                @if (session('errorMessage.ownerName'))
                  <small class="text-danger">{{session('errorMessage.ownerName')}}</small>
                @endif
              </div>
              <div class="col-md-6">
                <label class="form-label">Rent</label>
                <input
                  type="text" class="form-control" name="rent"
                  value="{{session('formFields.rent', $listing->rent)}}"
                >
                @if (session('errorMessage.rent'))
                  <small class="text-danger">{{session('errorMessage.rent')}}</small>
                @endif
              </div>
              <div class="col-md-6">
                <label class="form-label">Available Date</label>
                <input
                  type="date" class="form-control" name="availableDate"
                  value="{{session('formFields.availableDate', $listing->availableDate)}}"
                >
              </div>
              <div class="col-12">
                <label class="form-label">Street</label>
                <input type="text" class="form-control" name="street" value="{{$listing->street}}">
              </div>
              <div class="col-md-6">
                <label class="form-label">City</label>
                <input
                  type="text" class="form-control" name="city"
                  value="{{session('formFields.city', $listing->city)}}"
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
                  @foreach ($states as $state)
                      <option
                        value="{{$state}}"
                        {{session('formFields.state') == $state ? 'selected' : ($listing->state == $state ? 'selected' : '')}}
                      >{{$state}}</option>
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
                        {{session('formFields.isBillIncluded') ? 'checked' : ($listing->isBillIncluded ? 'checked' : '')}}
                      >
                      <label class="form-check-label">Is Bill Included</label>
                  </div>
              </div>
              <div class="col-6">
                  <div class="form-check">
                      <input
                        class="form-check-input" type="checkbox" name="isFurnished"
                        {{session('formFields.isFurnished') ? 'checked' : ($listing->isFurnished ? 'checked' : '')}}
                      >
                      <label class="form-check-label">Is Furnished</label>
                  </div>
              </div>
              <div class="col-12">
                  <label class="form-label">Description</label>
                  <textarea
                      name="description" name="description" class="form-control" rows="5"
                      placeholder="Enter a detailed description of the property, highlighting key features and amenities."
                    >{{session('formFields.description', $listing->description)}}</textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary">Edit</button>
              </div>
          </form>

        </div>
    </div>
</x-master>