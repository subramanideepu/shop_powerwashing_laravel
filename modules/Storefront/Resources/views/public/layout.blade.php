<!DOCTYPE html>
<html lang="{{ locale() }}">
    <head>
        <base href="{{ config('app.url') }}">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

        <title>
            @hasSection('title')
                @yield('title') - {{ setting('store_name') }}
            @else
                {{ setting('store_name') }}
            @endif
        </title>

        @stack('meta')
        @PWA

        <link rel="shortcut icon" href="{{ $favicon }}" type="image/x-icon">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="{{ font_url(setting('storefront_display_font', 'Poppins')) }}" rel="stylesheet">

        @include('storefront::public.partials.variables')

        @vite([
            'modules/Storefront/Resources/assets/public/sass/app.scss',
            'modules/Storefront/Resources/assets/public/js/app.js',
            'modules/Storefront/Resources/assets/public/js/main.js'
        ])

        @stack('styles')

        {!! setting('custom_header_assets') !!}

        <script>
            window.FleetCart = {
                baseUrl: '{{ config('app.url') }}',
                rtl: {{ is_rtl() ? 'true' : 'false' }},
                storeName: '{{ setting('store_name') }}',
                storeLogo: '{{ $logo }}',
                currency: '{{ currency() }}',
                locale: '{{ locale() }}',
                loggedIn: {{ auth()->check() ? 'true' : 'false' }},
                csrfToken: '{{ csrf_token() }}',
                cart: {!! $cart !!},
                wishlist: {!! $wishlist !!},
                compareList: {!! $compareList !!},
                langs: {
                    'storefront::storefront.something_went_wrong': '{{ trans('storefront::storefront.something_went_wrong') }}',
                    'storefront::layouts.more_results': '{{ trans('storefront::layouts.more_results') }}'
                },
            };
        </script>

        {!! $schemaMarkup->toScript() !!}

        @stack('globals')

        <script type="module">
            Alpine.start();
        </script>

        @routes
    </head>

    <body
        dir="{{ is_rtl() ? 'rtl' : 'ltr' }}"
        class="page-template {{ is_rtl() ? 'rtl' : 'ltr' }}" 
        data-theme-color="{{ $themeColor->toHexString() }}"
    >
        <div x-data="App" class="wrapper">
            @include('storefront::public.layouts.top_nav')
            @include('storefront::public.layouts.header')
            @include('storefront::public.layouts.navigation')
            @include('storefront::public.layouts.breadcrumb')

            @yield('content')

            @include('storefront::public.home.sections.newsletter_subscription')
            @include('storefront::public.layouts.footer')

            <div
                class="overlay"
                :class="{ active: $store.layout.overlay }"
                @click="hideOverlay"
            >
            </div>

            @include('storefront::public.layouts.sidebar_menu')
            @include('storefront::public.layouts.localization')

            @if (!request()->routeIs('checkout.create'))
                @include('storefront::public.layouts.sidebar_cart')
            @endif

            @include('storefront::public.layouts.alert')
            @include('storefront::public.layouts.newsletter_popup')
            @include('storefront::public.layouts.cookie_bar')
            @include('storefront::public.layouts.scroll_to_top')
          <div x-data="App">
   <div x-cloak x-show="$store.quote.show" class="quote-modal-overlay">

    <div class="quote-modal">

        <h3>Get Quote</h3>

        <form @submit.prevent="submitQuote">

            {{-- <input type="hidden" x-model="$store.quote.product?.name">
            <input type="hidden" x-model="$store.quote.product?.url"> --}}

            <div class="form-group">
                <label>Name</label>
                <input type="text" x-model="quoteForm.name" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" x-model="quoteForm.phone" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" x-model="quoteForm.email" required>
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea x-model="quoteForm.message"></textarea>
            </div>

            <button class="btn btn-primary" :class="{ 'btn-loading': loading }">
                Submit Quote
            </button>

              <button 
        type="button"
        class="quote-close-btn"
        @click="$store.quote.close()"
    >
        ✕
    </button>

        </form>

    </div>
</div>
</div>
        </div>

        @stack('pre-scripts')
        @stack('scripts')

        {!! setting('custom_footer_assets') !!}
    </body>
</html>
