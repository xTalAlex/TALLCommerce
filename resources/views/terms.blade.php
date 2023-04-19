<x-guest-layout>
    <div class="pt-4 bg-base-100">
        <div class="flex flex-col items-center min-h-screen pt-6 sm:pt-0">
            <div>
                <x-application-logo />
            </div>

            <div class="w-full prose shadow-md card bg-base-100 sm:max-w-2xl">
                <div class="card-body">
                    {!! $terms !!}
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>