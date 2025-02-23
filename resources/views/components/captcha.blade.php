<div class="form-group">
    {!! NoCaptcha::renderJs('es') !!}
    {!! NoCaptcha::display() !!}
    @if ($errors->has('g-recaptcha-response'))
    <br>
    <small style="color: red">{{ $errors->first('g-recaptcha-response') }}</small>
    @endif
</div>