@props(['href'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center gap-2 px-6 py-3
            bg-slate-700 hover:bg-slate-800
            text-white text-base font-semibold
            rounded-lg shadow transition'
    ]) }}>

    ← {{ $slot->isEmpty() ? 'Back' : $slot }}

</a>
