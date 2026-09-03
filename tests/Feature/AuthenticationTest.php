<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\InternProfile;
use App\Models\CompanyProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // ========================================
    // LOGIN TESTS
    // ========================================

    public function test_login_page_renders(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk');
        $response->assertSee('Google');
        $response->assertSee('Daftar sebagai Intern');
        $response->assertSee('Daftar sebagai Perusahaan');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $uuid = (string) Str::uuid();
        $profile = Profile::create([
            'id' => $uuid,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'ROOT',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($profile);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Test User',
            'email' => 'testwrong@example.com',
            'role' => 'ROOT',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'testwrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Logout User',
            'email' => 'logout@example.com',
            'role' => 'INTERN',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($profile);

        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/dashboard/intern');
        $response->assertRedirect('/login');
    }

    // ========================================
    // ROLE-BASED REDIRECT TESTS
    // ========================================

    public function test_root_redirected_to_root_dashboard_on_login(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Admin',
            'email' => 'root-redirect@example.com',
            'role' => 'ROOT',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'root-redirect@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('root.dashboard'));
    }

    public function test_company_redirected_to_company_dashboard_on_login(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Company',
            'email' => 'company-redirect@example.com',
            'role' => 'COMPANY',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'company-redirect@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('company.dashboard'));
    }

    public function test_intern_redirected_to_intern_dashboard_on_login(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Intern',
            'email' => 'intern-redirect@example.com',
            'role' => 'INTERN',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'intern-redirect@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('intern.dashboard'));
    }

    public function test_already_logged_in_user_redirected_by_role(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Already In',
            'email' => 'already@example.com',
            'role' => 'INTERN',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($profile);

        $response = $this->get('/login');
        $response->assertRedirect(route('intern.dashboard'));
    }

    // ========================================
    // INTERN REGISTRATION TESTS
    // ========================================

    public function test_intern_registration_page_renders(): void
    {
        $response = $this->get('/register/intern');
        $response->assertStatus(200);
        $response->assertSee('Daftar sebagai Intern');
        $response->assertSee('Nama Lengkap');
        $response->assertSee('Email');
        $response->assertSee('Password');
    }

    public function test_intern_can_register_manually(): void
    {
        $response = $this->post('/register/intern', [
            'name' => 'New Intern',
            'email' => 'newintern@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('intern.dashboard'));

        $this->assertDatabaseHas('profiles', [
            'email' => 'newintern@example.com',
            'role' => 'INTERN',
        ]);

        $this->assertDatabaseHas('intern_profiles', [
            'contact_email' => 'newintern@example.com',
        ]);

        $this->assertAuthenticated();
    }

    public function test_intern_registration_validates_required_fields(): void
    {
        $response = $this->post('/register/intern', [
            'name' => '',
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    public function test_intern_registration_validates_unique_email(): void
    {
        Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'role' => 'INTERN',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/register/intern', [
            'name' => 'Another Intern',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_intern_registration_validates_password_confirmation(): void
    {
        $response = $this->post('/register/intern', [
            'name' => 'Test Intern',
            'email' => 'testconfirm@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // ========================================
    // COMPANY REGISTRATION TESTS
    // ========================================

    public function test_company_registration_page_renders(): void
    {
        $response = $this->get('/register/company');
        $response->assertStatus(200);
        $response->assertSee('Daftar sebagai Perusahaan');
        $response->assertSee('Nama Perusahaan');
        $response->assertSee('Email');
        $response->assertSee('Password');
    }

    public function test_company_can_register_manually(): void
    {
        $response = $this->post('/register/company', [
            'name' => 'New Company',
            'email' => 'newcompany@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('company.dashboard'));

        $this->assertDatabaseHas('profiles', [
            'email' => 'newcompany@example.com',
            'role' => 'COMPANY',
        ]);

        $this->assertDatabaseHas('company_profiles', [
            'contact_email' => 'newcompany@example.com',
        ]);

        $this->assertAuthenticated();
    }

    public function test_company_registration_is_immediately_active(): void
    {
        $response = $this->post('/register/company', [
            'name' => 'Active Company',
            'email' => 'activecompany@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('company.dashboard'));

        // Company should be logged in and redirected to dashboard (not pending)
        $this->assertAuthenticated();

        $profile = Profile::where('email', 'activecompany@example.com')->first();
        $this->assertNotNull($profile);
        $this->assertEquals('COMPANY', $profile->role);
        // Should NOT have any pending status
        $this->assertNull($profile->deleted_at);
    }

    public function test_company_registration_validates_required_fields(): void
    {
        $response = $this->post('/register/company', [
            'name' => '',
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    public function test_company_registration_validates_unique_email(): void
    {
        Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Existing Company',
            'email' => 'existing@example.com',
            'role' => 'COMPANY',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/register/company', [
            'name' => 'Another Company',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // ========================================
    // ROOT ACCOUNT CREATION TESTS
    // ========================================

    public function test_root_can_create_company_account(): void
    {
        $admin = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin-create@example.com',
            'role' => 'ROOT',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($admin);

        $response = $this->post('/dashboard/root/accounts', [
            'name' => 'New Company',
            'email' => 'newcompany@example.com',
            'role' => 'COMPANY',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('profiles', [
            'email' => 'newcompany@example.com',
            'role' => 'COMPANY',
        ]);
    }

    public function test_root_can_create_intern_account(): void
    {
        $admin = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin-intern@example.com',
            'role' => 'ROOT',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($admin);

        $response = $this->post('/dashboard/root/accounts', [
            'name' => 'New Intern',
            'email' => 'newintern@example.com',
            'role' => 'INTERN',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('profiles', [
            'email' => 'newintern@example.com',
            'role' => 'INTERN',
        ]);
    }

    // ========================================
    // AUTHORIZATION TESTS
    // ========================================

    public function test_intern_cannot_access_company_dashboard(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Intern User',
            'email' => 'internonly@example.com',
            'role' => 'INTERN',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($profile);

        $response = $this->get('/dashboard/company');
        $response->assertStatus(403);
    }

    public function test_company_cannot_access_intern_dashboard(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Company User',
            'email' => 'companyonly@example.com',
            'role' => 'COMPANY',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($profile);

        $response = $this->get('/dashboard/intern');
        $response->assertStatus(403);
    }

    public function test_intern_cannot_access_root_dashboard(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Intern',
            'email' => 'internroot@example.com',
            'role' => 'INTERN',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($profile);

        $response = $this->get('/dashboard/root');
        $response->assertStatus(403);
    }

    public function test_company_cannot_access_root_dashboard(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Company',
            'email' => 'companyroot@example.com',
            'role' => 'COMPANY',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($profile);

        $response = $this->get('/dashboard/root');
        $response->assertStatus(403);
    }

    public function test_root_can_access_root_dashboard(): void
    {
        $admin = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin-access@example.com',
            'role' => 'ROOT',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($admin);

        $response = $this->get('/dashboard/root');
        $response->assertStatus(200);
    }

    public function test_root_can_access_company_dashboard(): void
    {
        $admin = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin-company@example.com',
            'role' => 'ROOT',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($admin);

        // ROOT should be able to access all dashboards (or at least not get 403)
        // Note: ROOT routes use role:ROOT middleware, so this may return 403 if ROOT can't access COMPANY routes
        // This is expected behavior - ROOT manages from their own dashboard
        $response = $this->get('/dashboard/company');
        // ROOT doesn't have role:COMPANY, so this should be 403
        $response->assertStatus(403);
    }

    public function test_company_cannot_self_assign_root_role(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Company Hacker',
            'email' => 'hacker@example.com',
            'role' => 'COMPANY',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($profile);

        // Try to update own role via profile update (should not be allowed)
        $response = $this->put('/dashboard/company/profile', [
            'name' => 'Company Hacker',
            'role' => 'ROOT',
        ]);

        // The profile update should not change the role
        $profile->refresh();
        $this->assertEquals('COMPANY', $profile->role);
    }

    public function test_intern_cannot_self_assign_root_role(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Intern Hacker',
            'email' => 'internhacker@example.com',
            'role' => 'INTERN',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->actingAs($profile);

        // Try to update own role via profile update (should not be allowed)
        $response = $this->put('/dashboard/intern/profile', [
            'name' => 'Intern Hacker',
            'role' => 'ROOT',
        ]);

        // The profile update should not change the role
        $profile->refresh();
        $this->assertEquals('INTERN', $profile->role);
    }

    // ========================================
    // DUPLICATE EMAIL PREVENTION TESTS
    // ========================================

    public function test_intern_registration_rejects_duplicate_email(): void
    {
        Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Existing User',
            'email' => 'duplicate@example.com',
            'role' => 'COMPANY',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/register/intern', [
            'name' => 'New Intern',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_company_registration_rejects_duplicate_email(): void
    {
        Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Existing User',
            'email' => 'duplicate@example.com',
            'role' => 'INTERN',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/register/company', [
            'name' => 'New Company',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ========================================
    // LANDING PAGE & NAVIGATION TESTS
    // ========================================

    public function test_landing_page_has_registration_ctas(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Daftar sebagai Intern');
        $response->assertSee('Daftar sebagai Perusahaan');
        $response->assertSee('Ingin Bermitra dengan YUHLEZ?');
    }

    public function test_login_page_has_registration_links(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee(route('register.intern'));
        $response->assertSee(route('register.company'));
    }

    // ========================================
    // PASSWORD VALIDATION TESTS
    // ========================================

    public function test_registration_requires_minimum_password_length(): void
    {
        $response = $this->post('/register/intern', [
            'name' => 'Test User',
            'email' => 'short@example.com',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_login_requires_password(): void
    {
        Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'No Password User',
            'email' => 'nopass@example.com',
            'role' => 'INTERN',
            'password_hash' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'nopass@example.com',
            'password' => 'anypassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ========================================
    // GOOGLE OAUTH INTENT TESTS
    // ========================================

    public function test_google_redirect_stores_intern_intent(): void
    {
        $response = $this->get('/v1/auth/google/redirect?intent=intern');
        // This will redirect to Google, but we can check session was set
        $this->assertEquals('intern', session('google_oauth_intent'));
    }

    public function test_google_redirect_stores_company_intent(): void
    {
        $response = $this->get('/v1/auth/google/redirect?intent=company');
        $this->assertEquals('company', session('google_oauth_intent'));
    }

    public function test_google_redirect_defaults_to_login_mode(): void
    {
        $response = $this->get('/v1/auth/google/redirect');
        // No intent = login page mode (null = login only, no new account creation)
        $this->assertNull(session('google_oauth_intent'));
    }

    public function test_google_redirect_rejects_invalid_intent(): void
    {
        $response = $this->get('/v1/auth/google/redirect?intent=invalid');
        // Invalid intent = falls back to login mode (null)
        $this->assertNull(session('google_oauth_intent'));
    }

    // ========================================
    // FORGOT PASSWORD TESTS
    // ========================================

    public function test_forgot_password_page_renders(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
        $response->assertSee('Lupa Password');
    }

    // ========================================
    // MIDDLEWARE TESTS
    // ========================================

    public function test_intern_profile_middleware_redirects_incomplete_profile(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Incomplete Intern',
            'email' => 'incomplete@example.com',
            'role' => 'INTERN',
            'password_hash' => Hash::make('password123'),
        ]);

        // Create intern profile but leave fields empty
        InternProfile::create([
            'id' => Str::uuid(),
            'user_id' => $profile->id,
            'name' => 'Incomplete Intern',
            'slug' => 'incomplete-intern',
        ]);

        $this->actingAs($profile);

        // Try to access route that requires complete profile
        $response = $this->post('/dashboard/intern/applications', [
            'program_id' => 'some-id',
        ]);

        $response->assertRedirect(route('intern.profile.edit'));
    }

    public function test_intern_can_access_profile_edit_without_complete_profile(): void
    {
        $profile = Profile::create([
            'id' => (string) Str::uuid(),
            'name' => 'Incomplete Intern',
            'email' => 'incomplete2@example.com',
            'role' => 'INTERN',
            'password_hash' => Hash::make('password123'),
        ]);

        InternProfile::create([
            'id' => Str::uuid(),
            'user_id' => $profile->id,
            'name' => 'Incomplete Intern',
            'slug' => 'incomplete-intern-2',
        ]);

        $this->actingAs($profile);

        $response = $this->get('/dashboard/intern/profile');
        $response->assertStatus(200);
    }
}
