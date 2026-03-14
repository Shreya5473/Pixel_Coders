@php
    $channel = core()->getCurrentChannel();

    // ── Promo banner (only the home-offer block) ─────────────────────────────
    $promoBanner = $customizations->first(
        fn($c) => $c->type === 'static_content' && str_contains($c->options['html'] ?? '', 'home-offer')
    );

    // ── Color / collection data from /public/images/collections/ ─────────────
    $colorHexMap = [
        'black'  => '#1C1C1C', 'white'  => '#E8E3D9', 'red'    => '#8B2020',
        'green'  => '#1A5C38', 'yellow' => '#A07820', 'blue'   => '#1B3C6E',
        'grey'   => '#6B6B6B', 'pink'   => '#C06080',
    ];
    $tintClasses = ['tint-mint','tint-blue','tint-lav','tint-sand','tint-aqua','tint-frost'];

    $collectionDefs = [
        'Clothes'  => ['slug' => 'kids-clothing', 'label' => 'Clothes',   'cat_id' => 2, 'tint' => 'tint-mint',  'min' => 29, 'max' =>  59],
        'Costumes' => ['slug' => 'costumes',       'label' => 'Costumes',  'cat_id' => 5, 'tint' => 'tint-sand',  'min' => 39, 'max' =>  89],
        'Hats'     => ['slug' => 'hats',           'label' => 'Hats',      'cat_id' => 3, 'tint' => 'tint-blue',  'min' => 19, 'max' =>  49],
        'Pants'    => ['slug' => 'pants',          'label' => 'Pants',     'cat_id' => 8, 'tint' => 'tint-aqua',  'min' => 39, 'max' =>  79],
        'Sweaters' => ['slug' => 'sweaters',       'label' => 'Sweaters',  'cat_id' => 4, 'tint' => 'tint-lav',   'min' => 45, 'max' =>  99],
    ];

    $collections = [];
    $heroImages  = [];
    $imgBase     = public_path('images/collections');

    if (is_dir($imgBase)) {
        foreach ($collectionDefs as $folder => $def) {
            $catDir = $imgBase . DIRECTORY_SEPARATOR . $folder;
            if (!is_dir($catDir)) continue;

            $items = []; $colors = []; $thumbImg = null;

            foreach (array_filter(glob($catDir . DIRECTORY_SEPARATOR . '*'), 'is_dir') as $cDir) {
                $cName = strtolower(basename($cDir));
                $files = glob($cDir . DIRECTORY_SEPARATOR . '*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE);
                if (!empty($files)) {
                    $colors[] = ['name' => $cName, 'hex' => $colorHexMap[$cName] ?? '#888'];
                    if (!$thumbImg) {
                        $seg = ['images','collections',$folder,basename($cDir),basename($files[0])];
                        $thumbImg = asset(implode('/', array_map('rawurlencode', $seg)));
                    }
                }
                foreach (array_slice($files, 0, 8) as $f) {
                    $seg   = ['images','collections',$folder,basename($cDir),basename($f)];
                    $price = $def['min'] + (abs(crc32(basename($f))) % max(1, $def['max'] - $def['min'] + 1));
                    $items[] = [
                        'url'   => asset(implode('/', array_map('rawurlencode', $seg))),
                        'color' => $cName,
                        'hex'   => $colorHexMap[$cName] ?? '#888',
                        'name'  => ucfirst($cName),
                        'price' => number_format($price + 0.99, 2),
                    ];
                    // collect potential hero images (Clothes for editorial)
                    if ($folder === 'Clothes') {
                        $heroImages[] = asset(implode('/', array_map('rawurlencode', $seg)));
                    }
                }
            }

            // Resolve product ID for cart (use simple product so add-to-cart works with just product_id + quantity)
            $productRow = DB::table('product_categories as pc')
                ->where('pc.category_id', $def['cat_id'])
                ->join('product_flat as pf', 'pf.product_id', '=', 'pc.product_id')
                ->where('pf.status', 1)
                ->join('products as p', 'p.id', '=', 'pc.product_id')
                ->select('pc.product_id', 'p.type', 'p.id as pid')->first();

            $cartProductId = null;
            if ($productRow) {
                if (($productRow->type ?? '') === 'configurable') {
                    $child = DB::table('products')->where('parent_id', $productRow->product_id)->value('id');
                    $cartProductId = $child ?: $productRow->product_id;
                } else {
                    $cartProductId = $productRow->product_id;
                }
            }

            if (!empty($items)) {
                $collections[] = [
                    'slug'            => $def['slug'],
                    'label'           => $def['label'],
                    'cat_id'          => $def['cat_id'],
                    'tint'            => $def['tint'],
                    'product_id'      => $productRow?->product_id,
                    'cart_product_id' => $cartProductId,
                    'thumb'      => $thumbImg,
                    'items'      => $items,
                    'colors'     => $colors,
                ];
            }
        }
    }

    $heroImg = $heroImages[0] ?? null;
@endphp

@push('meta')
    <meta name="title"       content="{{ $channel->home_seo['meta_title'] ?? 'BAGISTO — Luxury Fashion' }}" />
    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />
    <meta name="keywords"    content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
@endPush

@push('scripts')
    @if (!empty($categories))
        <script>localStorage.setItem('categories', JSON.stringify(@json($categories)));</script>
    @endif
@endpush

<x-shop::layouts :has-feature="false">
    <x-slot:title>{{ $channel->home_seo['meta_title'] ?? 'BAGISTO — Luxury Fashion' }}</x-slot>

    {{-- ── Promo Banner ──────────────────────────────────────────────── --}}
    @if($promoBanner)
        {!! $promoBanner->options['html'] !!}
    @endif

    {{-- ── ACM Intro Landing ────────────────────────────────────────── --}}
    <section class="acm-intro" aria-label="Bagisto landing intro">
        <div class="acm-intro-card">
            <img class="acm-intro-logo" src="{{ asset('images/acm-logo.png') }}" alt="ACM Logo" loading="lazy" decoding="async" />

            <h1 class="acm-intro-word" aria-label="BAGISTO">
                <span class="acm-letter">B</span>
                <span class="acm-letter">A</span>
                <span class="acm-letter">G</span>
                <span class="acm-letter">I</span>
                <span class="acm-letter">S</span>
                <span class="acm-letter">T</span>
                <span class="acm-letter">O</span>
            </h1>

            <p class="acm-intro-sub">Curated style for every moment.</p>

            <a href="#collections" class="btn btn-primary" style="padding: 11px 24px; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase;">Start Shopping</a>
        </div>
    </section>

    {{-- ── Mid Showcase Section ───────────────────────────────────── --}}
    @if($heroImg)
        <section class="acm-mid-showcase" aria-label="Featured style showcase">
            <div class="acm-mid-showcase-inner">
                <div class="acm-mid-copy">
                    <p class="acm-mid-kicker">Editor's pick</p>
                    <p class="acm-mid-title">Signature silhouettes for every day.</p>
                    <p class="acm-mid-sub">Refined essentials with soft tailoring and modern cuts.</p>

                    <a href="{{ route('shop.search.index') }}" class="btn btn-primary" style="padding: 11px 24px; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase;">Explore All</a>
                </div>

                <div class="acm-mid-media">
                    <img src="{{ $heroImg }}" alt="Featured fashion look" loading="lazy" decoding="async" />
                </div>
            </div>
        </section>
    @endif

    {{-- ── Zara-style Collections Hub ───────────────────────────────── --}}
    <div id="collections">
        <v-bagisto-home></v-bagisto-home>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-bagisto-home-tpl">
            <div>
                <!-- Tab Navigation -->
                <nav style="display:flex;align-items:center;justify-content:center;padding:36px 48px 0;border-bottom:1px solid rgba(255,255,255,0.30);flex-wrap:wrap;background:rgba(255,255,255,0.15);backdrop-filter:blur(16px);">
                    <template v-for="(col, i) in collections" :key="i">
                        <button
                            :id="'tab-' + col.slug"
                            class="zh-tab-btn"
                            :class="{ 'is-active': activeIdx === i }"
                            @click="setTab(i)"
                            style="background:none;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:11px;font-weight:600;letter-spacing:0.20em;text-transform:uppercase;color:var(--text-muted);padding:14px 22px;position:relative;transition:color 0.22s ease;white-space:nowrap;outline:none;"
                        >
                            @{{ col.label }}
                            <span style="position:absolute;bottom:-1px;left:22px;right:22px;height:2px;background:var(--text-accent);transform:scaleX(0);transition:transform 0.3s ease;display:block;" :style="{transform: activeIdx===i ? 'scaleX(1)' : 'scaleX(0)'}"></span>
                        </button>
                        <span v-if="i < collections.length - 1" style="color:rgba(180,180,180,0.60);font-size:11px;">|</span>
                    </template>
                </nav>

                <!-- Product Carousel -->
                <div v-if="current" style="padding:40px 48px 16px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                        <span style="font:500 24px/1.3 'Fraunces',serif;color:var(--text-primary);">@{{ current.label }}</span>
                        <div style="display:flex;gap:8px;">
                            <button @click="scroll(-660)" style="width:36px;height:36px;border:1px solid rgba(255,255,255,0.45);background:rgba(255,255,255,0.28);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;cursor:pointer;border-radius:10px;transition:all 0.2s;" onmouseover="this.style.background='rgba(160,140,230,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.28)'">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                            </button>
                            <button @click="scroll(660)" style="width:36px;height:36px;border:1px solid rgba(255,255,255,0.45);background:rgba(255,255,255,0.28);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;cursor:pointer;border-radius:10px;transition:all 0.2s;" onmouseover="this.style.background='rgba(160,140,230,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.28)'">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </button>
                        </div>
                    </div>

                    <transition name="fade-slide" mode="out-in">
                        <div
                            class="zh-track"
                            ref="track"
                            :key="activeIdx"
                            @mousedown.prevent="dragStart"
                            @mousemove="dragMove"
                            @mouseup="dragEnd"
                            @mouseleave="dragEnd"
                            style="display:flex;gap:16px;overflow-x:auto;scroll-behavior:smooth;scrollbar-width:none;cursor:grab;padding-bottom:4px;"
                        >
                            <div
                                v-for="(item, j) in current.items"
                                :key="j"
                                :class="'product-card-glass ' + current.tint"
                                style="flex:0 0 calc(25% - 12px);min-width:220px;cursor:pointer;border-radius:20px;overflow:hidden;transition:all 0.32s ease;box-shadow:0 8px 32px rgba(100,120,160,0.12);"
                                @click="goToCollection(current.slug)"
                                onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 48px rgba(100,120,160,0.22)'"
                                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 32px rgba(100,120,160,0.12)'"
                            >
                                <div style="aspect-ratio:3/4;overflow:hidden;background:rgba(240,237,232,0.60);border-radius:16px 16px 0 0;position:relative;">
                                    <img
                                        :src="item.url"
                                        :alt="item.name"
                                        loading="lazy"
                                        decoding="async"
                                        style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.45s ease;"
                                        onmouseover="this.style.transform='scale(1.05)'"
                                        onmouseout="this.style.transform='scale(1)'"
                                    />
                                </div>
                                <div class="zh-card-info" style="padding:12px 14px 16px;background:rgba(255,255,255,0.22);backdrop-filter:blur(16px);border-top:1px solid rgba(255,255,255,0.35);">
                                    <div style="font:500 9px/1 'DM Sans';letter-spacing:0.10em;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px;display:inline-block;padding:3px 8px;background:rgba(255,255,255,0.40);border-radius:10px;border:1px solid rgba(255,255,255,0.55);">@{{ current.label }}</div>
                                    <p class="zh-card-name" style="font:500 15px/1.4 'Fraunces',serif;color:var(--text-primary);margin:0 0 4px;">@{{ item.name }}</p>
                                    <p class="zh-card-price" style="font:600 14px/1 'DM Sans';color:var(--text-primary);">$@{{ item.price }}</p>
                                    <div style="display:flex;gap:5px;margin-top:8px;">
                                        <span v-for="c in current.colors" :key="c.name" :style="{width:'14px',height:'14px',borderRadius:'50%',background:c.hex,border:'2px solid rgba(255,255,255,0.70)',display:'inline-block'}"></span>
                                    </div>
                                    <button
                                        @click.stop="quickAddToCart(current)"
                                        style="margin-top:10px;width:100%;background:rgba(160,140,230,0.22);border:1px solid rgba(160,140,230,0.42);color:var(--text-accent);border-radius:10px;padding:10px 12px;font:700 10px/1 'DM Sans';letter-spacing:0.16em;text-transform:uppercase;cursor:pointer;transition:all 0.2s ease;"
                                    >
                                        Add To Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
                <div v-else style="text-align:center;padding:80px 20px;font:11px 'DM Sans';letter-spacing:0.18em;text-transform:uppercase;color:var(--text-muted);">No products available.</div>

                <!-- View Collection -->
                <div v-if="current" style="text-align:center;padding:8px 48px 12px;">
                    <a :href="'/' + current.slug" style="font:500 10px/1 'DM Sans';letter-spacing:0.20em;text-transform:uppercase;color:var(--text-muted);text-decoration:underline;text-underline-offset:4px;transition:color 0.2s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-muted)'">
                        View @{{ current.label }} Collection →
                    </a>
                </div>

                <!-- Backdrop -->
                <div @click="closeDrawer" :style="{position:'fixed',inset:0,background:'rgba(180,195,215,0.30)',backdropFilter:'blur(5px)',zIndex:900,opacity:drawer.open?1:0,visibility:drawer.open?'visible':'hidden',transition:'all 0.35s ease'}"></div>

                <!-- Product Drawer -->
                <div :style="{position:'fixed',top:0,right:0,bottom:0,width:'460px',maxWidth:'100vw',background:'rgba(255,255,255,0.75)',backdropFilter:'blur(32px)',webkitBackdropFilter:'blur(32px)',borderLeft:'1px solid rgba(255,255,255,0.50)',boxShadow:'-12px 0 48px rgba(100,120,160,0.22)',zIndex:901,transform:drawer.open?'translateX(0)':'translateX(100%)',transition:'transform 0.42s cubic-bezier(0.4,0,0.2,1)',display:'flex',flexDirection:'column',overflow:'hidden'}">
                    <button @click="closeDrawer" style="position:absolute;top:14px;right:16px;width:34px;height:34px;background:rgba(255,255,255,0.80);border:1px solid rgba(255,255,255,0.55);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:10;transition:all 0.2s;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>

                    <template v-if="drawer.item">
                        <div style="width:100%;aspect-ratio:3/4;max-height:48vh;flex-shrink:0;overflow:hidden;background:rgba(240,237,232,0.65);">
                            <img :src="drawerImg" :alt="drawer.item.name" style="width:100%;height:100%;object-fit:cover;display:block;" />
                        </div>

                        <div style="flex:1;overflow-y:auto;padding:22px 26px 30px;display:flex;flex-direction:column;gap:18px;">
                            <div>
                                <p style="font:500 20px/1.3 'Fraunces',serif;color:var(--text-primary);margin:0 0 6px;">@{{ drawer.item.name }}</p>
                                <p style="font:600 18px/1 'DM Sans';color:var(--text-primary);margin:0;">$@{{ drawer.item.price }}</p>
                            </div>

                            <!-- Colors -->
                            <div v-if="current && current.colors.length > 1">
                                <p style="font:600 10px/1 'DM Sans';letter-spacing:0.18em;text-transform:uppercase;color:var(--text-muted);margin-bottom:10px;">Color — @{{ drawer.color }}</p>
                                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                    <button v-for="c in current.colors" :key="c.name"
                                        @click="pickColor(c)"
                                        :title="c.name"
                                        :style="{width:'26px',height:'26px',borderRadius:'50%',background:c.hex,border:'2px solid rgba(255,255,255,0.70)',outline:drawer.color===c.name?'2.5px solid rgba(160,140,230,0.55)':'2px solid transparent',outlineOffset:'2px',cursor:'pointer',transition:'outline 0.18s ease',flexShrink:0}"
                                    ></button>
                                </div>
                            </div>

                            <!-- Sizes -->
                            <div>
                                <p :style="{font:'600 10px/1 DM Sans',letterSpacing:'0.18em',textTransform:'uppercase',color:drawer.sizeError?'#c0392b':'var(--text-muted)',marginBottom:'10px',transition:'color 0.2s'}">
                                    @{{ drawer.sizeError ? '← Select a size' : 'Size' }}
                                </p>
                                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                    <button v-for="sz in sizes" :key="sz"
                                        @click="drawer.size=sz;drawer.sizeError=false"
                                        :style="{minWidth:'46px',height:'46px',padding:'0 8px',display:'flex',alignItems:'center',justifyContent:'center',border:drawer.size===sz?'1px solid rgba(160,140,230,0.45)':'1px solid rgba(255,255,255,0.55)',background:drawer.size===sz?'rgba(160,140,230,0.22)':'rgba(255,255,255,0.35)',borderRadius:'10px',font:'500 11px/1 DM Sans',color:drawer.size===sz?'var(--text-accent)':'var(--text-ui)',cursor:'pointer',transition:'all 0.16s ease',backdropFilter:'blur(10px)'}"
                                    >@{{ sz }}</button>
                                </div>
                            </div>

                            <!-- Add to Cart -->
                            <button
                                @click="addToCart"
                                :disabled="drawer.adding"
                                :style="{width:'100%',background:drawer.added?'rgba(207,233,219,0.55)':'rgba(160,140,230,0.22)',border:drawer.added?'1px solid rgba(207,233,219,0.72)':'1px solid rgba(160,140,230,0.42)',color:drawer.added?'var(--text-success)':'var(--text-accent)',borderRadius:'12px',padding:'17px 24px',font:'700 11px/1 DM Sans',letterSpacing:'0.22em',textTransform:'uppercase',cursor:'pointer',transition:'all 0.22s ease',backdropFilter:'blur(10px)',marginTop:'auto',opacity:drawer.adding?0.65:1}"
                            >
                                <span v-if="drawer.adding">Adding…</span>
                                <span v-else-if="drawer.added">✓ Added to Cart</span>
                                <span v-else>Add to Cart</span>
                            </button>

                            <a v-if="drawer.added" href="{{ route('shop.checkout.cart.index') }}" style="display:block;text-align:center;font:500 10px/1 'DM Sans';letter-spacing:0.16em;text-transform:uppercase;color:var(--text-muted);text-decoration:underline;text-underline-offset:3px;">
                                View Cart →
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </script>

        <script type="module">
            const __collections = @json($collections);

            app.component('v-bagisto-home', {
                template: '#v-bagisto-home-tpl',
                data() {
                    return {
                        collections: __collections,
                        activeIdx:   0,
                        sizes:       ['XS', 'S', 'M', 'L', 'XL'],
                        drawer: { open: false, item: null, color: null, size: null, added: false, adding: false, sizeError: false },
                        drag:   { on: false, startX: 0, scrollLeft: 0, moved: false },
                    };
                },
                computed: {
                    current() { return this.collections[this.activeIdx] ?? null; },
                    drawerImg() {
                        if (!this.drawer.item || !this.current) return this.drawer.item?.url ?? '';
                        const m = this.current.items.find(i => i.color === this.drawer.color);
                        return m ? m.url : this.drawer.item.url;
                    },
                },
                methods: {
                    setTab(i) {
                        this.activeIdx = i;
                        this.closeDrawer();
                        this.$nextTick(() => { if (this.$refs.track) this.$refs.track.scrollLeft = 0; });
                    },
                    openDrawer(item) {
                        if (this.drag.moved) return;
                        this.drawer = { ...this.drawer, open: true, item, color: item.color, size: null, added: false, adding: false, sizeError: false };
                        document.body.style.overflow = 'hidden';
                    },
                    closeDrawer() { this.drawer.open = false; document.body.style.overflow = ''; },
                    pickColor(c) {
                        this.drawer.color = c.name;
                        const m = this.current?.items.find(i => i.color === c.name);
                        if (m) this.drawer.item = { ...this.drawer.item, url: m.url };
                    },
                    goToCollection(slug) {
                        window.location.href = '/' + slug;
                    },
                    quickAddToCart(collection) {
                        const pid = collection?.cart_product_id ?? collection?.product_id;

                        if (!pid) {
                            window.location.href = '/' + (collection?.slug ?? '');
                            return;
                        }

                        this.$axios.post('{{ route('shop.api.checkout.cart.store') }}', {
                            product_id: pid,
                            quantity: 1,
                        })
                        .then((response) => {
                            if (response.data?.data) {
                                this.$emitter.emit('update-mini-cart', response.data.data);
                            }

                            window.location.href = '{{ route('shop.checkout.cart.index') }}';
                        })
                        .catch((error) => {
                            this.$emitter.emit('add-flash', { type: 'warning', message: error?.response?.data?.message ?? 'Unable to add item to cart.' });
                        });
                    },
                    addToCart() {
                        if (!this.drawer.size) { this.drawer.sizeError = true; setTimeout(() => { this.drawer.sizeError = false; }, 2000); return; }
                        const pid = this.current?.cart_product_id ?? this.current?.product_id;
                        if (!pid) { window.location.href = '/' + (this.current?.slug ?? ''); return; }
                        this.drawer.adding = true;
                        this.$axios.post('{{ route('shop.api.checkout.cart.store') }}', { product_id: pid, quantity: 1 }, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(r => {
                                if (r.data?.data) this.$emitter.emit('update-mini-cart', r.data.data);
                                this.drawer.adding = false;
                                this.drawer.added  = true;
                            })
                            .catch((err) => {
                                this.drawer.adding = false;
                                const msg = err.response?.data?.message || err.message || 'Could not add to cart';
                                if (typeof this.$emitter?.emit === 'function') this.$emitter.emit('add-flash', { type: 'warning', message: msg });
                                else window.alert(msg);
                            });
                    },
                    scroll(amt) { const el = this.$refs.track; if (el) el.scrollLeft += amt; },
                    dragStart(e) {
                        const el = this.$refs.track; if (!el) return;
                        this.drag = { on: true, startX: e.pageX - el.offsetLeft, scrollLeft: el.scrollLeft, moved: false };
                        el.style.cursor = 'grabbing';
                    },
                    dragMove(e) {
                        if (!this.drag.on) return;
                        const el = this.$refs.track;
                        const walk = (e.pageX - el.offsetLeft - this.drag.startX) * 1.1;
                        if (Math.abs(walk) > 5) this.drag.moved = true;
                        el.scrollLeft = this.drag.scrollLeft - walk;
                    },
                    dragEnd() {
                        this.drag.on = false;
                        if (this.$refs.track) this.$refs.track.style.cursor = 'grab';
                        setTimeout(() => { this.drag.moved = false; }, 60);
                    },
                },
            });
        </script>

        <style>
        .zh-track::-webkit-scrollbar { display: none; }
        .fade-slide-enter-active, .fade-slide-leave-active { transition: opacity 0.22s ease; }
        .fade-slide-enter-from, .fade-slide-leave-to { opacity: 0; }
        </style>
    @endPushOnce
</x-shop::layouts>
