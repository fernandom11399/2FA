<div data-mdb-input-init class="form-outline mb-4">
    <input type="{{ $type }}" id="{{ $name }}" class="form-control form-control-lg" name="{{ $name }}" value="{{ $oldValue }}"/>
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    @foreach ($errors->get($name) as $error)
        <br>
        <small style="color: red">{{ $error }}</small>
    @endforeach
</div>
