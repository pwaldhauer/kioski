@props(['for'])

@error($for)
    <div class="text-xs text-red-600">{{ $message }}</div>
@enderror
