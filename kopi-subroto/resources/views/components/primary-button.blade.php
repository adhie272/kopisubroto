<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-brand-brown border border-transparent rounded-2xl font-bold text-sm text-white uppercase tracking-widest hover:bg-brand-brownDark active:bg-brand-brownDark focus:outline-none focus:ring-2 focus:ring-brand-teal focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
