@props(['style' => session('flash.bannerStyle', 'success'), 'message' => session('flash.banner')])

<div x-data="{{ json_encode(['show' => true, 'style' => $style, 'message' => $message]) }}"
            class="fixed inset-x-0 z-50 top-16"
            :class="{ 'bg-success-500': style == 'success', 'bg-danger-500': style == 'danger', 'bg-warning-500': style == 'warning' , 'bg-secondary-500': style!= 'warning' && style != 'success' && style != 'danger' }"
            style="display: none;"
            x-show="show && message"
            x-on:click="show = false"
            x-transition.duration.500ms.opacity.origin.top
            x-init="
                document.addEventListener('banner-message', event => {
                    if(message)
                        message = null;
                    style = event.detail.style;
                    message = event.detail.message;
                    show = true;
                    setTimeout(() => show = false, 4000);
                });
            ">
    <div class="max-w-screen-xl px-3 py-2 mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex items-center flex-1 w-0 min-w-0">
                <span class="flex p-2">
                    <svg x-show="style == 'success'" class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="style == 'danger'" class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="style != 'success' && style != 'danger'" class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>

                <p class="ml-3 text-sm text-white truncate select-none" x-text="message"></p>
            </div>

            <div class="shrink-0 sm:ml-3">
                <button
                    type="button"
                    class="flex p-2 -mr-1 transition focus:outline-none sm:-mr-2"
                    :class="{ 'hover:bg-success-600 focus:bg-success-600': style == 'success', 'hover:bg-danger-600 focus:bg-danger-600': style == 'danger' }"
                    aria-label="Dismiss"
                    x-on:click="show = false">
                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
