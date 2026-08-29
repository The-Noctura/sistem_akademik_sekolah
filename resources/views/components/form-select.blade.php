@props(['name', 'label', 'options' => [], 'selected' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium mb-1 text-slate-700">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full border rounded-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-600 ' . ($errors->has($name) ? 'border-red-600' : 'border-slate-200')]) }}>
        <option value="">-- Pilih --</option>
        @foreach ($options as $value => $labelText)
            <option value="{{ $value }}" @selected(old($name, $selected) == $value)>{{ $labelText }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
