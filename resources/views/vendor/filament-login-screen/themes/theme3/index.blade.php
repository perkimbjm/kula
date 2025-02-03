@props([
    'heading' => null,
    'subheading' => null,
])

<div class="font-[sans-serif] text-[#333]">
    <div class="min-h-screen flex flex-col items-center justify-center">
      <div class="grid md:grid-cols-2 items-center gap-4 max-w-6xl w-full p-4 m-4 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] rounded-md">
        <div class="md:max-w-md w-full sm:px-6 py-4">
            <section class="grid auto-cols-fr gap-y-6">
                <x-filament-panels::header.simple
                        :heading="$heading ??= $this->getHeading()"
                        :logo="$this->hasLogo()"
                        :subheading="$subheading ??= $this->getSubHeading()"
                />
                @if (filament()->hasRegistration())
                    <x-slot name="subheading">
                        {{ __('filament-panels::pages/auth/login.actions.register.before') }}

                        {{ $this->registerAction }}
                    </x-slot>
                @endif


                <x-filament-panels::form wire:submit="authenticate">
                    {{ $this->form }}

                    <x-filament-panels::form.actions
                            :actions="$this->getCachedFormActions()"
                            :full-width="$this->hasFullWidthFormActions()"
                    />
                </x-filament-panels::form>

                @if (filament()->hasRegistration())
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            {{ __('filament-panels::pages/auth/login.actions.register.before') }}
                            {{ $this->registerAction }}
                        </p>
                    </div>
                @endif

            </section>
        </div>
        <div class="md:h-full max-md:mt-10 bg-[#ccd2ff] rounded-xl lg:p-12 p-8">
          <img src="{{ asset('img/login.png') }}" class="w-full h-full object-contain" alt="login-image" />
        </div>
      </div>
    </div>
  </div>
  