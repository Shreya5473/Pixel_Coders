{{-- GLASSMORPHISM COLLECTION CARDS - Homepage --}}
<style>
.glass-collections-section {
    padding: 60px 60px 40px;
}
@media (max-width: 1024px) { .glass-collections-section { padding: 40px 32px 30px; } }
@media (max-width: 768px)  { .glass-collections-section { padding: 30px 16px 20px; } }

.glass-collections-section .section-heading {
    font-family: 'Fraunces', serif !important;
    font-size: 2.2rem;
    font-weight: 500;
    color: #2F3A45;
    text-align: center;
    margin-bottom: 8px;
}
.glass-collections-section .section-subtitle {
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    color: #6B7684;
    text-align: center;
    margin-bottom: 36px;
}
.glass-card-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
@media (max-width: 1200px) { .glass-card-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .glass-card-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; } }
@media (max-width: 520px)  { .glass-card-grid { grid-template-columns: 1fr; } }

.collection-card-link {
    text-decoration: none;
    display: block;
    border-radius: 20px;
    transition: all 0.18s ease;
}
.collection-card-link:hover { transform: translateY(-4px); }
.collection-card-link:hover .glass-collection-card {
    box-shadow: 0 14px 40px rgba(100,120,160,0.22) !important;
}
.glass-collection-card {
    background: rgba(255,255,255,0.27);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,0.42);
    border-radius: 20px;
    box-shadow: 0 6px 24px rgba(100,120,160,0.13);
    overflow: hidden;
    transition: all 0.18s ease;
    display: flex;
    flex-direction: column;
}
.glass-collection-card .card-image-wrap {
    height: 360px;
    position: relative;
    overflow: hidden;
}
.glass-collection-card .card-image-wrap::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.38) 0%, rgba(0,0,0,0.06) 45%, rgba(0,0,0,0.05) 100%);
}
.glass-collection-card .card-image {
    height: 100%;
    width: 100%;
    object-fit: cover;
    display: block;
}
.glass-collection-card .card-label {
    position: absolute;
    left: 16px;
    right: 16px;
    bottom: 16px;
    z-index: 1;
    padding: 14px 16px;
    border-radius: 14px;
    background: rgba(255,255,255,0.88);
    backdrop-filter: blur(6px);
}
.glass-collection-card .card-title {
    font-family: 'Fraunces', serif;
    font-size: 1.25rem;
    font-weight: 600;
    color: #2F3A45;
    margin-bottom: 4px;
}
.glass-collection-card .card-sub {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.82rem;
    color: #8A95A3;
}
.glass-collection-card .card-sub::after {
    content: ' →';
    color: #7060A8;
    transition: margin 0.18s;
}
.collection-card-link:hover .card-sub::after { margin-left: 3px; }
</style>

<div class="glass-collections-section container">
    <h2 class="section-heading">Collections</h2>
    <p class="section-subtitle">Tap a collection to open the same category pages available in the top menu</p>

    @php
        $collections = [
            [
                'title' => 'Clothes',
                'url' => url('/kids-clothing'),
                'image' => 'images/collections/Clothes/Black/Black1.jpg',
            ],
            [
                'title' => 'Costumes',
                'url' => url('/costumes'),
                'image' => 'images/collections/Costumes/Yellow/COSTUME YELLOW 2.jpg',
            ],
            [
                'title' => 'Hats',
                'url' => url('/hats'),
                'image' => 'images/collections/Hats/Red/RED HAT1.jpg',
            ],
            [
                'title' => 'Pants',
                'url' => url('/pants'),
                'image' => 'images/collections/Pants/White/PANTS WHITE 1.jpg',
            ],
            [
                'title' => 'Sweaters',
                'url' => url('/sweaters'),
                'image' => 'images/collections/Sweaters/Black/BLACK S1.jpg',
            ],
        ];

        $encodedAsset = fn (string $relativePath) => asset(
            collect(explode('/', $relativePath))
                ->map(fn (string $segment) => rawurlencode($segment))
                ->implode('/')
        );
    @endphp

    <div class="glass-card-grid">
        @foreach ($collections as $collection)
            <a href="{{ $collection['url'] }}" class="collection-card-link" aria-label="{{ $collection['title'] }}">
                <div class="glass-collection-card">
                    <div class="card-image-wrap">
                        <img
                            class="card-image"
                            src="{{ $encodedAsset($collection['image']) }}"
                            alt="{{ $collection['title'] }}"
                            loading="lazy"
                            decoding="async"
                        />

                        <div class="card-label">
                            <div class="card-title">{{ $collection['title'] }}</div>
                            <div class="card-sub">View collection</div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
