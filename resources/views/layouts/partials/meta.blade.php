<div class="row">
    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" id="meta-title" name="meta[title]" class="form-control @error('meta[title]') is-invalid @enderror" @isset($form) form="{{$form}}" @endisset
                   value="{{ old('meta[title]', $meta['title'] ?? null) }}" placeholder="-=Тег Meta-title=-" @if((isset($required) and $required) || !isset($required)) required @endif>
            <label for="meta-title">-=Тег Meta-title=-</label>
            @error('meta[title]')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" id="meta-description" name="meta[description]" class="form-control @error('meta[description]') is-invalid @enderror" @isset($form) form="{{$form}}" @endisset
                   value="{{ old('meta[description]', $meta['description'] ?? null) }}" placeholder="-=Тег Meta-description=-" @if((isset($required) and $required) || !isset($required)) required @endif>
            <label for="meta-description">-=Тег Meta-description=-</label>
            @error('meta[description]')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
</div>
