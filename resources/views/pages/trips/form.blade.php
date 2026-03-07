<form id="tripForm"
      action="{{ isset($trip) ? route('trips.update',$trip->id) : route('trips.store') }}"
      method="POST">

    @csrf
    @if(isset($trip))
        @method('PUT')
    @endif

    <div class="row">

        <div class="col-md-6 mb-3">
            <label class="form-label">Device</label>
            <select name="device_id" class="form-control" required>
                <option value="">Select Device</option>
                @foreach($devices as $device)
                    <option value="{{ $device->id }}"
                        {{ isset($trip) && $trip->device_id == $device->id ? 'selected' : '' }}>
                        {{ $device->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Entity</label>
            <select name="entity_id" class="form-control" required>
                <option value="">Select Entity</option>
                @foreach($entities as $entity)
                    <option value="{{ $entity->id }}"
                        {{ isset($trip) && $trip->entity_id == $entity->id ? 'selected' : '' }}>
                        {{ $entity->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Direction</label>
            <select name="direction" class="form-control" required>
                <option value="IN" {{ isset($trip) && $trip->direction == 'IN' ? 'selected' : '' }}>IN</option>
                <option value="OUT" {{ isset($trip) && $trip->direction == 'OUT' ? 'selected' : '' }}>OUT</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Weight</label>
            <input type="number"
                   name="weight"
                   class="form-control"
                   value="{{ $trip->weight ?? '' }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Trip Date Time</label>
            <input type="datetime-local"
                   name="device_timestamp"
                   class="form-control"
                   value="{{ isset($trip) ? \Carbon\Carbon::parse($trip->device_timestamp)->format('Y-m-d\TH:i') : '' }}">
        </div>

    </div>

    <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary">
            {{ isset($trip) ? 'Update Trip' : 'Save Trip' }}
        </button>
    </div>
</form>
