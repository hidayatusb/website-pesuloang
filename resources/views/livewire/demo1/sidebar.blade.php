<!-- Sidebar -->
<div class="kt-sidebar fixed bottom-0 top-0 z-20 hidden h-screen shrink-0 flex-col items-stretch border-e border-e-border bg-background [--kt-drawer-enable:true] lg:flex lg:[--kt-drawer-enable:false]"
    data-kt-drawer="true" data-kt-drawer-class="kt-drawer kt-drawer-start top-0 bottom-0" id="sidebar">
    <div class="kt-sidebar-header relative hidden shrink-0 items-center justify-between px-3 lg:flex lg:px-6"
        id="sidebar_header">
        <div class="kt-sidebar-logo min-w-0">
            <a class="dark:hidden" href="{{ route('dashboard.index') }}" wire:navigate>
                <img class="default-logo min-h-[22px] max-w-none"
                    src="{{ asset('assets/media/app/desa-pesuloang.png') }}" width="200" />
                <img class="small-logo min-h-[22px] max-w-none" src="{{ asset('assets/media/app/mini-logo-majene.png') }}" width="29"  />
            </a>
            <a class="hidden dark:block" href="{{ route('dashboard.index') }}" wire:navigate>
                <img class="default-logo min-h-[22px] max-w-none"
                    src="{{ asset('assets/media/app/desa-pesuloang-dark.png') }}" width="200"  />
                    <img class="small-logo min-h-[22px] max-w-none" src="{{ asset('assets/media/app/mini-logo-majene.png') }}" width="200"  />
            </a>
        </div>
        <button
            class="kt-btn kt-btn-outline kt-btn-icon absolute start-full top-2/4 z-40 size-[30px] -translate-x-2/4 -translate-y-2/4 rtl:translate-x-2/4"
            data-kt-toggle="body" data-kt-toggle-class="kt-sidebar-collapse" id="sidebar_toggle">
            <i
                class="ki-filled ki-black-left-line kt-toggle-active:rotate-180 rtl:translate rtl:kt-toggle-active:rotate-0 transition-all duration-300 rtl:rotate-180">
            </i>
        </button>
    </div>
    <div class="kt-sidebar-content flex grow shrink-0 py-5 pe-2" id="sidebar_content">
        <div class="kt-scrollable-y-hover grow shrink-0 flex ps-2 lg:ps-5 pe-1 lg:pe-3" data-kt-scrollable="true"
            data-kt-scrollable-dependencies="#sidebar_header" data-kt-scrollable-height="auto"
            data-kt-scrollable-offset="0px" data-kt-scrollable-wrappers="#sidebar_content" id="sidebar_scrollable">
            <!-- Sidebar Menu -->
            <div class="kt-menu flex grow flex-col gap-1" data-kt-menu="true" data-kt-menu-accordion-expand-all="false"
                id="sidebar_menu">
                <div class="kt-menu-item pt-2.25 pb-px">
                    <span
                        class="kt-menu-heading pe-[10px] ps-[10px] text-xs font-medium uppercase text-muted-foreground">
                        Menu
                    </span>
                </div>
                <div class="kt-menu-item">
                    <a class="kt-menu-link kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] hover:rounded-lg"
                        href="{{ route('dashboard.index') }}" wire:navigate tabindex="0">
                        <span class="kt-menu-icon w-[20px] items-start text-muted-foreground">
                            <i class="ki-filled ki-element-11 text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary text-sm font-medium text-foreground">
                            Dashboard
                        </span>
                    </a>
                </div>

                <div class="kt-menu-item pt-2.25 pb-px">
                    <span class="kt-menu-heading pe-[10px] ps-[10px] text-xs font-medium uppercase text-muted-foreground">
                        Website Desa
                    </span>
                </div>
                <div class="kt-menu-item">
                    <a class="kt-menu-link kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] hover:rounded-lg"
                        href="{{ route('desa.identitas.index') }}" wire:navigate tabindex="0">
                        <span class="kt-menu-icon w-[20px] items-start text-muted-foreground">
                            <i class="ki-filled ki-flag text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary text-sm font-medium text-foreground">
                            Identitas Desa
                        </span>
                    </a>
                </div>
                <div class="kt-menu-item">
                    <a class="kt-menu-link kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] hover:rounded-lg"
                        href="{{ route('desa.statistika.index') }}" wire:navigate tabindex="0">
                        <span class="kt-menu-icon w-[20px] items-start text-muted-foreground">
                            <i class="ki-filled ki-chart-line text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary text-sm font-medium text-foreground">
                            Statistika Desa
                        </span>
                    </a>
                </div>
                <div class="kt-menu-item">
                    <a class="kt-menu-link kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] hover:rounded-lg"
                        href="{{ route('desa.berita.index') }}" wire:navigate tabindex="0">
                        <span class="kt-menu-icon w-[20px] items-start text-muted-foreground">
                            <i class="ki-filled ki-book text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary text-sm font-medium text-foreground">
                            Berita & Pengumuman
                        </span>
                    </a>
                </div>
                <div class="kt-menu-item">
                    <a class="kt-menu-link kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] hover:rounded-lg"
                        href="{{ route('desa.umkm.index') }}" wire:navigate tabindex="0">
                        <span class="kt-menu-icon w-[20px] items-start text-muted-foreground">
                            <i class="ki-filled ki-shop text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary text-sm font-medium text-foreground">
                            UMKM Desa
                        </span>
                    </a>
                </div>
                <div class="kt-menu-item">
                    <a class="kt-menu-link kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] hover:rounded-lg"
                        href="{{ route('desa.layanan.index') }}" wire:navigate tabindex="0">
                        <span class="kt-menu-icon w-[20px] items-start text-muted-foreground">
                            <i class="ki-filled ki-clipboard text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary text-sm font-medium text-foreground">
                            Layanan Desa
                        </span>
                    </a>
                </div>
                <div class="kt-menu-item">
                    <a class="kt-menu-link kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] hover:rounded-lg"
                        href="{{ route('desa.infografis.index') }}" wire:navigate tabindex="0">
                        <span class="kt-menu-icon w-[20px] items-start text-muted-foreground">
                            <i class="ki-filled ki-picture text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary text-sm font-medium text-foreground">
                            Infografis
                        </span>
                    </a>
                </div>
                <div class="kt-menu-item">
                    <a class="kt-menu-link kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] hover:rounded-lg"
                        href="{{ route('desa.dokumen.index') }}" wire:navigate tabindex="0">
                        <span class="kt-menu-icon w-[20px] items-start text-muted-foreground">
                            <i class="ki-filled ki-files text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary text-sm font-medium text-foreground">
                            Dokumen Desa
                        </span>
                    </a>
                </div>
                <div class="kt-menu-item">
                    <a class="kt-menu-link kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] hover:rounded-lg"
                        href="{{ route('desa.galeri.index') }}" wire:navigate tabindex="0">
                        <span class="kt-menu-icon w-[20px] items-start text-muted-foreground">
                            <i class="ki-filled ki-picture text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary text-sm font-medium text-foreground">
                            Dokumentasi / Galeri
                        </span>
                    </a>
                </div>
                <div class="kt-menu-item">
                    <a class="kt-menu-link kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] hover:rounded-lg"
                        href="{{ route('desa.aparatur.index') }}" wire:navigate tabindex="0">
                        <span class="kt-menu-icon w-[20px] items-start text-muted-foreground">
                            <i class="ki-filled ki-people text-lg"></i>
                        </span>
                        <span
                            class="kt-menu-title kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary text-sm font-medium text-foreground">
                            Aparatur Desa
                        </span>
                    </a>
                </div>
            </div>
            <!-- End of Sidebar Menu -->
        </div>
    </div>
</div>
<!-- End of Sidebar -->
