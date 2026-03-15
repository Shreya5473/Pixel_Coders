@push('meta')
    <meta name="description" content="@lang('shop::app.customers.signup-form.page-title')"/>
    <meta name="keywords" content="@lang('shop::app.customers.signup-form.page-title')"/>
@endPush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <x-slot:title>Create Account — BAGISTO</x-slot>

    <div style="min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 20px;position:relative;z-index:1;">

        <!-- Back -->
        <a href="{{ route('shop.home.index') }}" style="position:fixed;top:24px;left:24px;font:500 13px/1 'DM Sans',sans-serif;letter-spacing:0.10em;text-transform:uppercase;color:#8A95A3;text-decoration:none;display:flex;align-items:center;gap:8px;transition:color 0.2s;" onmouseover="this.style.color='#2F3A45'" onmouseout="this.style.color='#8A95A3'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            BAGISTO
        </a>

        <div style="max-width:480px;width:100%;background:linear-gradient(135deg,rgba(230,226,248,0.72),rgba(218,213,244,0.68));backdrop-filter:blur(28px);-webkit-backdrop-filter:blur(28px);border:1px solid rgba(255,255,255,0.45);border-radius:28px;box-shadow:0 16px 48px rgba(100,120,160,0.18);padding:44px 40px;">

            <p style="font:500 28px/1.2 'Fraunces',serif;color:#2F3A45;text-align:center;margin:0 0 6px;">Create Account</p>
            <p style="font:300 italic 16px/1.4 'Fraunces',serif;color:#8A95A3;text-align:center;margin:0 0 32px;">Join BAGISTO today.</p>

            {!! view_render_event('bagisto.shop.customers.sign-up.before') !!}

            <x-shop::form :action="route('shop.customers.register.store')">
                {!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

                <!-- Name row -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required" style="font:500 10px/1 'DM Sans';letter-spacing:0.16em;text-transform:uppercase;color:#8A95A3;display:block;margin-bottom:8px;">First Name</x-shop::form.control-group.label>
                        <x-shop::form.control-group.control type="text" name="first_name" rules="required" :value="old('first_name')" placeholder="First" aria-required="true" />
                        <x-shop::form.control-group.error control-name="first_name" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required" style="font:500 10px/1 'DM Sans';letter-spacing:0.16em;text-transform:uppercase;color:#8A95A3;display:block;margin-bottom:8px;">Last Name</x-shop::form.control-group.label>
                        <x-shop::form.control-group.control type="text" name="last_name" rules="required" :value="old('last_name')" placeholder="Last" aria-required="true" />
                        <x-shop::form.control-group.error control-name="last_name" />
                    </x-shop::form.control-group>
                </div>

                <!-- Email -->
                <x-shop::form.control-group class="mb-4">
                    <x-shop::form.control-group.label class="required" style="font:500 10px/1 'DM Sans';letter-spacing:0.16em;text-transform:uppercase;color:#8A95A3;display:block;margin-bottom:8px;">Email</x-shop::form.control-group.label>
                    <x-shop::form.control-group.control type="email" name="email" rules="required|email" :value="old('email')" placeholder="email@example.com" aria-required="true" />
                    <x-shop::form.control-group.error control-name="email" />
                </x-shop::form.control-group>

                <!-- Phone (optional) -->
                @if (core()->getConfigData('customer.settings.phone.phone_number_required') != 'disabled')
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label style="font:500 10px/1 'DM Sans';letter-spacing:0.16em;text-transform:uppercase;color:#8A95A3;display:block;margin-bottom:8px;">Phone</x-shop::form.control-group.label>
                        <x-shop::form.control-group.control type="tel" name="phone" :value="old('phone')" placeholder="+1 (555) 000-0000" />
                        <x-shop::form.control-group.error control-name="phone" />
                    </x-shop::form.control-group>
                @endif

                <!-- Password -->
                <x-shop::form.control-group class="mb-4">
                    <x-shop::form.control-group.label class="required" style="font:500 10px/1 'DM Sans';letter-spacing:0.16em;text-transform:uppercase;color:#8A95A3;display:block;margin-bottom:8px;">Password</x-shop::form.control-group.label>
                    <x-shop::form.control-group.control type="password" id="password" name="password" rules="required|min:6" placeholder="Min. 6 characters" aria-required="true" />
                    <x-shop::form.control-group.error control-name="password" />
                </x-shop::form.control-group>

                <!-- Confirm Password -->
                <x-shop::form.control-group class="mb-6">
                    <x-shop::form.control-group.label class="required" style="font:500 10px/1 'DM Sans';letter-spacing:0.16em;text-transform:uppercase;color:#8A95A3;display:block;margin-bottom:8px;">Confirm Password</x-shop::form.control-group.label>
                    <x-shop::form.control-group.control type="password" name="password_confirmation" rules="required|min:6|confirmed:@password" placeholder="Repeat password" aria-required="true" />
                    <x-shop::form.control-group.error control-name="password_confirmation" />
                </x-shop::form.control-group>

                @if (core()->getConfigData('customer.captcha.credentials.status'))
                    <x-shop::form.control-group class="mt-5">
                        {!! \Webkul\Customer\Facades\Captcha::render() !!}
                        <x-shop::form.control-group.error control-name="g-recaptcha-response" />
                    </x-shop::form.control-group>
                @endif

                {!! view_render_event('bagisto.shop.customers.signup_form_controls.after') !!}

                <button type="submit" style="width:100%;padding:16px 24px;background:rgba(160,140,230,0.22);border:1px solid rgba(160,140,230,0.42);border-radius:12px;font:700 11px/1 'DM Sans';letter-spacing:0.22em;text-transform:uppercase;color:#7060A8;cursor:pointer;transition:all 0.2s;backdrop-filter:blur(10px);" onmouseover="this.style.background='rgba(160,140,230,0.38)'" onmouseout="this.style.background='rgba(160,140,230,0.22)'">
                    Create Account
                </button>
            </x-shop::form>

            {!! view_render_event('bagisto.shop.customers.sign-up.after') !!}

            <div style="height:1px;background:rgba(255,255,255,0.45);margin:24px 0;"></div>

            <p style="font:400 13px/1.5 'DM Sans';color:#8A95A3;text-align:center;margin:0;">
                Already have an account?
                <a href="{{ route('shop.customer.session.create') }}" style="color:#7060A8;font-weight:500;text-decoration:none;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">Sign In →</a>
            </p>
        </div>
    </div>

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}
    @endpush
</x-shop::layouts>
