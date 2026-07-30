<!-- User -->
<div class="shrink-0" data-kt-dropdown="true" data-kt-dropdown-offset="10px, 10px" data-kt-dropdown-offset-rtl="-20px, 10px"
    data-kt-dropdown-placement="bottom-end" data-kt-dropdown-placement-rtl="bottom-start" data-kt-dropdown-trigger="click">
    <div class="shrink-0 cursor-pointer" data-kt-dropdown-toggle="true">
        <img alt="{{ auth()->user()->name }}" class="size-9 shrink-0 rounded-full border-2 border-green-500"
            src="{{ asset('assets/media/avatars/300-2.png') }}" />
    </div>
    <div class="kt-dropdown-menu w-[250px] hidden" data-kt-dropdown-menu="true">
        <div class="flex items-center justify-between gap-1.5 px-2.5 py-1.5">
            <div class="flex items-center gap-2">
                <img alt="{{ auth()->user()->name }}" class="size-9 shrink-0 rounded-full border-2 border-green-500"
                    src="{{ asset('assets/media/avatars/300-2.png') }}" />
                <div class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold leading-none text-foreground">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="text-xs font-medium leading-none text-secondary-foreground">
                        {{ auth()->user()->email }}
                    </span>
                </div>
            </div>
            <span class="kt-badge kt-badge-sm kt-badge-primary kt-badge-outline">
                Admin
            </span>
        </div>
        <ul class="kt-dropdown-menu-sub">
            <li>
                <div class="kt-dropdown-menu-separator"></div>
            </li>
            <li>
                <a class="kt-dropdown-menu-link" href="{{ route('dashboard.index') }}" wire:navigate>
                    <i class="ki-filled ki-element-11"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a class="kt-dropdown-menu-link" href="{{ route('home') }}" wire:navigate>
                    <i class="ki-filled ki-home-2"></i>
                    Website Desa
                </a>
            </li>
            <li>
                <div class="kt-dropdown-menu-separator"></div>
            </li>
        </ul>
        <div class="mb-2.5 flex flex-col gap-3.5 px-2.5 pt-1.5">
            <div class="flex items-center justify-between gap-2">
                <span class="flex items-center gap-2">
                    <i class="ki-filled ki-moon text-base text-muted-foreground"></i>
                    <span class="text-2sm font-medium">Dark Mode</span>
                </span>
                <input class="kt-switch" data-kt-theme-switch-state="dark" data-kt-theme-switch-toggle="true"
                    name="check" type="checkbox" value="1" />
            </div>
            <livewire:logout />
        </div>
    </div>
</div>
<!-- End of User -->
