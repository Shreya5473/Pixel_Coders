{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div style="display:flex;align-items:center;justify-content:space-between;padding:0 28px;min-height:72px;gap:24px;">

    {{-- ── Left: Brand ──────────────────────────────────────────────── --}}
    <div style="flex-shrink:0;">
        <a href="{{ route('shop.home.index') }}" style="display:flex;align-items:center;gap:10px;font:500 20px/1 'Fraunces',serif;color:#2F3A45;text-decoration:none;letter-spacing:0.04em;" aria-label="BAGISTO Home">
            <img src="{{ request()->getSchemeAndHttpHost() }}/images/acm-logo.png" alt="ACM Logo" style="width:26px;height:26px;object-fit:contain;border-radius:8px;" loading="lazy" decoding="async" />
            BAGISTO
        </a>
    </div>

    {{-- ── Center: Nav Links ────────────────────────────────────────── --}}
    <nav style="display:flex;align-items:center;gap:6px;flex:1;justify-content:center;" aria-label="Main navigation">
        <a href="{{ route('shop.home.index') }}" style="font:500 12px/1 'DM Sans',sans-serif;color:#6B7684;text-decoration:none;letter-spacing:0.06em;padding:8px 12px;border-radius:8px;transition:all 0.18s ease;white-space:nowrap;" onmouseover="this.style.color='#2F3A45';this.style.background='rgba(255,255,255,0.35)'" onmouseout="this.style.color='#6B7684';this.style.background='transparent'">Home</a>
        <span style="color:rgba(180,180,180,0.55);font-size:11px;">|</span>
        @php
            $navCategories = [
                ['label' => 'Clothes',   'url' => '/kids-clothing'],
                ['label' => 'Hats',      'url' => '/hats'],
                ['label' => 'Sweaters',  'url' => '/sweaters'],
                ['label' => 'Pants',     'url' => '/pants'],
                ['label' => 'Costumes',  'url' => '/costumes'],
            ];
        @endphp
        @foreach($navCategories as $nc)
            <a href="{{ $nc['url'] }}" style="font:500 11px/1 'DM Sans',sans-serif;color:#6B7684;text-decoration:none;letter-spacing:0.08em;text-transform:uppercase;padding:8px 10px;border-radius:8px;transition:all 0.18s ease;white-space:nowrap;" onmouseover="this.style.color='#2F3A45';this.style.background='rgba(255,255,255,0.35)'" onmouseout="this.style.color='#6B7684';this.style.background='transparent'">{{ $nc['label'] }}</a>
        @endforeach
    </nav>

    {{-- ── Right: Search + Cart + User ─────────────────────────────── --}}
    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

        {{-- Search ─────────────────────────────────────────────────── --}}
        <form action="{{ route('shop.search.index') }}" style="position:relative;display:flex;align-items:center;" role="search">
            <label for="organic-search" class="sr-only">Search</label>
            <div style="position:absolute;left:11px;top:50%;transform:translateY(-50%);pointer-events:none;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8A95A3" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            <input
                type="text"
                id="organic-search"
                name="query"
                value="{{ request('query') }}"
                list="shop-search-suggestions"
                data-search-suggestions-url="{{ route('shop.search.suggestions') }}"
                autocomplete="off"
                minlength="{{ core()->getConfigData('catalog.products.search.min_query_length') }}"
                maxlength="{{ core()->getConfigData('catalog.products.search.max_query_length') }}"
                placeholder="Search…"
                aria-label="Search"
                pattern="[^\\]+"
                required
                style="background:rgba(255,255,255,0.40);border:1px solid rgba(255,255,255,0.55);border-radius:12px;padding:9px 14px 9px 36px;font:400 13px/1 'DM Sans',sans-serif;color:#2F3A45;width:200px;outline:none;transition:all 0.2s ease;backdrop-filter:blur(10px);"
                onfocus="this.style.width='260px';this.style.borderColor='rgba(160,140,230,0.52)'"
                onblur="this.style.width='200px';this.style.borderColor='rgba(255,255,255,0.55)'"
            />
            <button type="submit" class="hidden" aria-label="Submit search"></button>
        </form>
        <datalist id="shop-search-suggestions"></datalist>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}

        {{-- Compare ─────────────────────────────────────────────────── --}}
        @if(core()->getConfigData('catalog.products.settings.compare_option'))
            <a href="{{ route('shop.compare.index') }}" style="display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,0.28);border:1px solid rgba(255,255,255,0.42);transition:all 0.2s;text-decoration:none;" aria-label="Compare" onmouseover="this.style.background='rgba(255,255,255,0.48)'" onmouseout="this.style.background='rgba(255,255,255,0.28)'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6B7684" stroke-width="1.8"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/></svg>
            </a>
        @endif

        {{-- Cart ────────────────────────────────────────────────────── --}}
        @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
            <a href="{{ route('shop.checkout.cart.index') }}" style="display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,0.28);border:1px solid rgba(255,255,255,0.42);transition:all 0.2s;text-decoration:none;" aria-label="Cart" onmouseover="this.style.background='rgba(255,255,255,0.48)'" onmouseout="this.style.background='rgba(255,255,255,0.28)'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6B7684" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </a>
        @endif

        {{-- User / Profile ──────────────────────────────────────────── --}}
        @auth('customer')
            <div style="position:relative;" class="profile-dropdown-wrap">
                <a href="{{ route('shop.customers.account.profile.index') }}" style="display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:12px;background:rgba(160,140,230,0.18);border:1px solid rgba(160,140,230,0.35);transition:all 0.2s;text-decoration:none;" aria-label="My Account" onmouseover="this.style.background='rgba(160,140,230,0.30)'" onmouseout="this.style.background='rgba(160,140,230,0.18)'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7060A8" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </a>
                <div class="profile-dropdown" style="display:none;position:absolute;top:calc(100% + 10px);right:0;min-width:180px;background:rgba(255,255,255,0.85);backdrop-filter:blur(28px);border:1px solid rgba(255,255,255,0.55);border-radius:16px;box-shadow:0 16px 48px rgba(100,120,160,0.22);padding:8px;z-index:1100;">
                    <a href="{{ route('shop.customers.account.profile.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font:400 13px/1 'DM Sans';color:#2F3A45;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='rgba(160,140,230,0.12)'" onmouseout="this.style.background='transparent'">My Profile</a>
                    <a href="{{ route('shop.customers.account.orders.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font:400 13px/1 'DM Sans';color:#2F3A45;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='rgba(160,140,230,0.12)'" onmouseout="this.style.background='transparent'">My Orders</a>
                    <a href="{{ route('shop.customers.account.wishlist.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font:400 13px/1 'DM Sans';color:#2F3A45;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='rgba(160,140,230,0.12)'" onmouseout="this.style.background='transparent'">Wishlist</a>
                    <div style="height:1px;background:rgba(255,255,255,0.45);margin:6px 10px;"></div>
                    <a href="{{ route('shop.customer.session.destroy') }}" onclick="event.preventDefault();document.getElementById('nav-logout-form').submit();" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font:400 13px/1 'DM Sans';color:#8A5A3A;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='rgba(246,231,210,0.40)'" onmouseout="this.style.background='transparent'">Sign Out</a>
                    <form id="nav-logout-form" action="{{ route('shop.customer.session.destroy') }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
                </div>
            </div>
        @else
            <a href="{{ route('shop.customer.session.create') }}" style="display:flex;align-items:center;gap:6px;padding:9px 18px;border-radius:12px;background:rgba(160,140,230,0.18);border:1px solid rgba(160,140,230,0.35);font:500 12px/1 'DM Sans';color:#7060A8;text-decoration:none;transition:all 0.2s;white-space:nowrap;" aria-label="Sign In" onmouseover="this.style.background='rgba(160,140,230,0.30)'" onmouseout="this.style.background='rgba(160,140,230,0.18)'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#7060A8" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Sign In
            </a>
        @endauth
    </div>
</div>

@pushOnce('scripts')
    <script type="module">
        // Search suggestions
        document.querySelectorAll('input[name="query"][data-search-suggestions-url]').forEach(searchInput => {
            const datalist = document.getElementById('shop-search-suggestions');
            if (!datalist) return;
            let debounceTimer = null;
            searchInput.addEventListener('input', () => {
                const term = searchInput.value.trim();
                clearTimeout(debounceTimer);
                if (term.length < 2) { datalist.innerHTML = ''; return; }
                debounceTimer = setTimeout(async () => {
                    try {
                        const response = await fetch(`${searchInput.dataset.searchSuggestionsUrl}?query=${encodeURIComponent(term)}`, { headers: { 'Accept': 'application/json' } });
                        const payload  = await response.json();
                        datalist.innerHTML = (payload.data || []).map(item => `<option value="${String(item.name).replace(/"/g, '&quot;')}"></option>`).join('');
                    } catch { datalist.innerHTML = ''; }
                }, 220);
            });
        });

        // Profile dropdown toggle
        const wrap = document.querySelector('.profile-dropdown-wrap');
        if (wrap) {
            const btn  = wrap.querySelector('a');
            const drop = wrap.querySelector('.profile-dropdown');
            if (btn && drop) {
                btn.addEventListener('mouseenter', () => { drop.style.display = 'block'; });
                wrap.addEventListener('mouseleave', () => { drop.style.display = 'none'; });
            }
        }
    </script>
@endPushOnce

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
