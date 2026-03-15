Welcome to our project! For this hackathon, we were challenged to rebuild and improve a broken e-commerce repository. We have used old github repository of [Bagisto](https://github.com/bagisto/bagisto). We retained the core structure and product data but dramatically improved the UI, backend capabilities, and user experience. 

## 🎬 Demo Video & Deployment
- **Watch the Demo Video:** [[Link to 4-6 minute walkthrough video]*](https://drive.google.com/drive/folders/1n4kLiUPbsCcJ2zSRHuM6_wDsEAQQATe_?usp=drive_link)
  

---

## 🛠 Project Overview
### The Before (Original State)-

Before our intervention, the Bagisto base repo suffered from a variety of friction points common in out-of-the-box e-commerce setups:
- **Clunky User Interface:** The default "Velocity" theme felt rigid, slightly outdated, and lacked the visual wow-factor required for modern boutique commerce.
- **Complex Authentication Flow:** The original sign-up strictly required a multitude of fields upfront, leading to increased drop-off rates for new potential customers.
- **Broken Data Pipelines & Empty Stores:** The homepage layout had hardcoded legacy links that didn't match real data. Products were entirely missing from these categories, and there were no clickable options to actually browse items efficiently.
- **Basic Payment & Search Flow:** Search relied on simple, slow database queries, and the storefront lacked modern integration for scalable payments.



### The After (Our Enhancements & Fixes)-
We totally revitalized the platform, turning it into a fast, premium, highly-usable store while rigorously keeping the core Laravel/Bagisto Foundation intact for product and order management.

#### 1. UI & UX Rebuild: The "Glassmorphism" Aesthetic ✨
We discarded the blocky nature of the default site and engineered a stunning, custom **Light Pastel Glassmorphism design system** across the platform. This aesthetic upgrade gives the e-commerce store a premium, modern, and trustworthy feel right from the first glance.
- **Populated Dynamic Collection Pages:** We completely solved the original "empty store" problem by curating and introducing over **108 high-quality editorial images**. These are beautifully organized into 6 main, dynamically rendered product sections: *Clothes, Hats, Pants, Sweaters, Costumes, and T-shirts*. Each section is fully interactive, featuring sleek, fully-clickable product cards on the new storefront. The website is now vibrant, populated, and fully functional!
- **Interactive Component Filters:** We implemented dynamic color filters (Red, Green, Blue, Black, White, Yellow) tailored perfectly within the glassmorphism theme, allowing users to effortlessly refine their search.
- **Improved Carts & Profiles:** We integrated seamless, modern slide-out modal carts and profile sidebars that load instantly via JavaScript, meaning users no longer have to suffer through disruptive full-page reloads when adding items to their cart.

#### 2. Backend Search & Payments ⚙️
- **Meilisearch + Laravel Scout Integration:** We replaced the slow standard query searching with Meilisearch, bringing blazing-fast autocomplete and typo-tolerant search functionality directly to the storefront.
- **Stripe Checkout Integration:** Configured an industry-standard, secure payment pipeline with the Stripe API, moving past the basic "Mock" payment solutions included.

#### 3. Streamlined Authentication & Onboarding 🔐
- **Email-Only Signups:** We completely rewrote the authentication logic. Users can now sign up securely using *just* an email and password, dramatically reducing bounce rates. To prevent breaking Bagisto's internal DB constraints (which demand a first/last name), we wrote a custom backend interceptor that elegantly auto-derives and seeds these values based on their email prefix.
- **Personalized & Professional Welcome Emails:** We completely overhauled the customer engagement flow. Triggered immediately by our custom signup form, the platform now automatically sends a highly professional, fashion-forward welcome email to the user (e.g., "Welcome to Bagisto – your new destination for fashion..."). This creates a fantastic first impression that was entirely absent in the original repository!

---

## 📂 Project Structure
Although we built off the massive Bagisto CRM core, the vast majority of our bespoke hackathon improvements occur within these domains:
```text
bagisto/
├── packages/Webkul/Shop/                # Primary module where we rebuilt the storefront
│   ├── src/Http/Controllers/            # (New logic for auth interception and dynamic collections)
│   ├── src/Resources/views/             # (Overhauled the entire UI into Glassmorphism blade views)
│   └── src/Routes/store-front-routes.php # (Rewrote storefront routing and API endpoints)
├── config/paymentmethods.php            # (Configured the new Stripe integration)
├── frontend/                            # (Compiled custom frontend SCSS/JS assets via Vite)
├── public/images/collections/           # (New dynamic high-res image catalog assets)
└── docker-compose.yml / sail            # (Environment deployment configurations)
```

---

## 💻 Technologies Used
- **Backend:** PHP 8.1+, Laravel 10.x, Bagisto Core Packages
- **Frontend:** Vanilla JS, Blade Templating, Custom CSS (Glassmorphism guidelines), Vite
- **Database:** MySQL
- **Search Engine:** Meilisearch + Laravel Scout
- **Payments:** Stripe SDK

---

## 🚀 Setup Steps (Localhost)

If you'd like to test the code locally, please follow these steps. The project has been configured to build and boot seamlessly using Laravel Sail.

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Shreya5473/Pixel_Coders.git
   cd Pixel_Coders
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configure Environment:**
   Copy the example environment variables and boot the containers.
   *(Please ensure you add your specific `STRIPE_API_KEY` and `MEILISEARCH_KEY` to the `.env` if testing full end-to-end purchasing).*
   ```bash
   cp .env.example .env
   ./vendor/bin/sail up -d
   ```

4. **Run Migrations & Seed the DB:**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate
   ./vendor/bin/sail artisan db:seed
   ```

5. **Start Scouting (Optional):**
   ```bash
   ./vendor/bin/sail artisan scout:import "Webkul\Product\Models\Product"
   ```

6. **Enjoy the Fast Storefront:**
   Navigate to `http://localhost:8080/` (or the respective port generated by Sail) to view the redesigned site!

---

*Thank you to the hackathon judges for the opportunity! We had a brilliant time wrestling with the massive Bagisto architecture and finding clever ways to optimize its foundation while completely modernizing its vibe.*
