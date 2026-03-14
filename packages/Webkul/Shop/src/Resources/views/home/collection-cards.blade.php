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
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
@media (max-width: 1200px) { .glass-card-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .glass-card-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; } }

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
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 5rem;
    position: relative;
    overflow: hidden;
}
.glass-collection-card:nth-child(7n+1) .card-image-wrap { background: linear-gradient(135deg, rgba(221,242,230,0.85), rgba(207,233,219,0.70)); }
.glass-collection-card:nth-child(7n+2) .card-image-wrap { background: linear-gradient(135deg, rgba(220,231,246,0.85), rgba(207,224,244,0.70)); }
.glass-collection-card:nth-child(7n+3) .card-image-wrap { background: linear-gradient(135deg, rgba(230,226,248,0.85), rgba(218,213,244,0.70)); }
.glass-collection-card:nth-child(7n+4) .card-image-wrap { background: linear-gradient(135deg, rgba(246,231,210,0.85), rgba(241,217,184,0.70)); }
.glass-collection-card:nth-child(7n+5) .card-image-wrap { background: linear-gradient(135deg, rgba(217,239,233,0.85), rgba(203,230,223,0.70)); }
.glass-collection-card:nth-child(7n+6) .card-image-wrap { background: linear-gradient(135deg, rgba(241,243,246,0.85), rgba(231,235,240,0.70)); }
.glass-collection-card:nth-child(7n+7) .card-image-wrap { background: linear-gradient(135deg, rgba(221,242,230,0.85), rgba(220,231,246,0.70)); }
.glass-collection-card .card-label {
    padding: 16px 18px;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255,255,255,0.32);
}
.glass-collection-card .card-title {
    font-family: 'Fraunces', serif;
    font-size: 1.1rem;
    font-weight: 500;
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
    <h2 class="section-heading">Our Collections</h2>
    <p class="section-subtitle">Curated fashion collections for every little one</p>
    <div class="glass-card-grid">
        <a href="{{ url('/kids-clothing') }}" class="collection-card-link" aria-label="Kids Clothing">
            <div class="glass-collection-card">
                <div class="card-image-wrap">👗</div>
                <div class="card-label"><div class="card-title">Kids Clothing</div><div class="card-sub">Shop now</div></div>
            </div>
        </a>
        <a href="{{ url('/hats') }}" class="collection-card-link" aria-label="Hats">
            <div class="glass-collection-card">
                <div class="card-image-wrap">🎩</div>
                <div class="card-label"><div class="card-title">Hats</div><div class="card-sub">Shop now</div></div>
            </div>
        </a>
        <a href="{{ url('/sweaters') }}" class="collection-card-link" aria-label="Sweaters">
            <div class="glass-collection-card">
                <div class="card-image-wrap">🧥</div>
                <div class="card-label"><div class="card-title">Sweaters</div><div class="card-sub">Shop now</div></div>
            </div>
        </a>
        <a href="{{ url('/costumes') }}" class="collection-card-link" aria-label="Costumes">
            <div class="glass-collection-card">
                <div class="card-image-wrap">🎭</div>
                <div class="card-label"><div class="card-title">Costumes</div><div class="card-sub">Shop now</div></div>
            </div>
        </a>
        <a href="{{ url('/jackets') }}" class="collection-card-link" aria-label="Outerwear">
            <div class="glass-collection-card">
                <div class="card-image-wrap">🧣</div>
                <div class="card-label"><div class="card-title">Outerwear</div><div class="card-sub">Shop now</div></div>
            </div>
        </a>
        <a href="{{ url('/t-shirts') }}" class="collection-card-link" aria-label="T-Shirts">
            <div class="glass-collection-card">
                <div class="card-image-wrap">👕</div>
                <div class="card-label"><div class="card-title">T-Shirts</div><div class="card-sub">Shop now</div></div>
            </div>
        </a>
        <a href="{{ url('/pants') }}" class="collection-card-link" aria-label="Pants">
            <div class="glass-collection-card">
                <div class="card-image-wrap">👖</div>
                <div class="card-label"><div class="card-title">Pants</div><div class="card-sub">Shop now</div></div>
            </div>
        </a>
    </div>
</div>
