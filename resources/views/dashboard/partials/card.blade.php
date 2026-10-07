<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

    <p class="text-sm font-medium text-slate-500">
        {{ $label }}
    </p>

    <p class="mt-2 text-3xl font-black tracking-tight text-slate-900">
        {{ $value }}
    </p>

    @if(!empty($detail))

        <p class="mt-2 text-xs font-medium text-slate-500">
            {{ $detail }}
        </p>

    @endif

</div>
