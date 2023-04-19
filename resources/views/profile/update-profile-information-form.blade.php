<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <x-input type="file" class="hidden" label="{{ __('Photo') }}"
                            wire:model="photo"
                            x-ref="photo"
                            id="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="object-cover w-20 h-20 rounded-full">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block w-20 h-20 bg-center bg-no-repeat bg-cover rounded-full"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-button>

                @if ($this->user->profile_photo_path)
                    <x-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-button>
                @endif

                <x-jet-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-input id="name" type="text" label="{{ __('Name') }}" class="block w-full" wire:model.defer="state.name" autocomplete="name" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-input id="email" type="email" label="{{ __('Email') }}" class="block w-full" wire:model.defer="state.email" />
        </div>

        <!-- Phone -->
        <div class="col-span-6 sm:col-span-4">
            <x-input id="phone" type="text" label="{{ __('Phone Number') }}" class="block w-full" wire:model.defer="state.phone" />
        </div>

        <!-- VAT -->
        <div class="col-span-6 sm:col-span-4">
            <x-input id="vat" type="text" label="{{ __('VAT') }}" class="block w-full" wire:model.defer="state.vat" />
        </div>

        <!-- Fiscal Code -->
        <div class="col-span-6 sm:col-span-4">
            <x-input label="{{ __('Fiscal Code') }}" id="fiscal_code" type="text" class="block w-full" wire:model.defer="state.fiscal_code" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved') }}
        </x-jet-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-jet-form-section>
