@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-brand-line text-brand-ink focus:border-brand-teal focus:ring-brand-teal rounded-2xl shadow-sm']) }}>
