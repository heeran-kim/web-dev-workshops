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
                  <input type="text" class="form-control" name="title" value="{{$listing->title}}">
              </div>
              <div class="col-12">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="userName" value="{{$listing->userName}}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Rent</label>
                <input type="text" class="form-control" name="rent" value="{{$listing->rent}}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Available Date</label>
                <input type="date" class="form-control" name="availableDate" value="{{$listing->availableDate}}">
              </div>
              <div class="col-12">
                <label class="form-label">Street</label>
                <input type="text" class="form-control" name="street" value="{{$listing->street}}">
              </div>
              <div class="col-md-6">
                <label class="form-label">City</label>
                <input type="text" class="form-control" name="city" value="{{$listing->city}}">
              </div>
              <div class="col-md-6">
                <label class="form-label">State</label>
                <select name="state" class="form-select">
                  @php
                      $states = ['NSW', 'QLD', 'VIC', 'SA', 'WA', 'TAS', 'NT', 'ACT'];
                  @endphp
                  @foreach ($states as $state)
                      <option value="{{$state}}" {{$listing->state == $state ? 'selected' : '' }}>{{$state}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-6">
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="isBillIncluded" {{$listing->isBillIncluded ? 'checked' : ''}}>
                      <label class="form-check-label">Is Bill Included</label>
                  </div>
              </div>
              <div class="col-6">
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="isFurnished" {{$listing->isFurnished ? 'checked' : ''}}>
                      <label class="form-check-label">Is Furnished</label>
                  </div>
              </div>
              <div class="col-12">
                  <label class="form-label">Description</label>
                  <textarea name="description" name="description" class="form-control" rows="5">{{$listing->description}}</textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary">Edit</button>
              </div>
          </form>

        </div>
    </div>
</x-master>