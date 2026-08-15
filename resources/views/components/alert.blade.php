{{-- 
FILE: resources/views/components/alert.blade.php
KOMPONEN: Alert
FUNGSI: Menampilkan notifikasi sukses/error
--}}

@props(['type' => 'success', 'message'])

@php
$classes = $type === 'success' 
    ? 'bg-green-50 text-green-700 border-green-200' 
    : 'bg-red-50 text-red-700 border-red-200';
@endphp

@if($message)
<div class="border {{ $classes }} rounded-md px-4 py-3 mb-6 text-sm">
    {{ $message }}
</div>
@endif