# MAPPING LENGKAP: FastAPI + Next.js → Laravel

## STATUS: AUDIT SELESAI - BELUM CODING

---

## 1. MAPPING FASTAPI ROUTES → LARAVEL

### PUBLIC ROUTES (tanpa login)

| FastAPI | Laravel | View |
|---------|---------|------|
| GET /public/programs | GET /magang | public.programs |
| GET /public/programs/{slug} | GET /magang/{slug} | public.program.show |
| GET /public/companies | GET /perusahaan | public.companies |
| GET /public/companies/{identifier} | GET /perusahaan/{slug} | public.company.show |
| GET /public/works | GET /karya | public.works |
| GET /public/works/{slug} | GET /karya/{slug} | public.work.show |
| GET /public/locations | GET /lokasi | public.locations |
| GET /public/locations/{identifier} | GET /lokasi/{slug} | public.location.show |
| GET /public/creators | GET /kreator | public.creators |
| GET /public/creators/{identifier} | GET /kreator/{slug} | public.creator.show |
| GET /public/contributors | GET /kontributor | public.contributors |
| GET /public/interns/{identifier} | GET /intern/{slug} | public.intern.show |
| GET /public/kost/search | GET /kost | public.kost |

### AUTH ROUTES

| FastAPI | Laravel | Controller |
|---------|---------|------------|
| GET /api/v1/auth/google | GET /auth/google/redirect | GoogleController@redirect |
| GET /api/v1/auth/google/callback | GET /auth/google/callback | GoogleController@callback |
| POST /api/v1/auth/logout | POST /logout | GoogleController@logout |
| POST /api/v1/auth/register | POST /auth/register | AuthController@register |

### DASHBOARD ROUTES (authenticated)

| FastAPI | Laravel | Controller |
|---------|---------|------------|
| GET /api/v1/me | GET /dashboard | DashboardController@index |
| PATCH /api/v1/me | PUT /dashboard/profile | ProfileController@update |

### ROOT/ADMIN ROUTES

| FastAPI | Laravel | Controller |
|---------|---------|------------|
| GET /api/v1/users | GET /dashboard/root/accounts | Root\AccountController@index |
| POST /api/v1/users | POST /dashboard/root/accounts | Root\AccountController@store |
| GET /api/v1/users/{id} | GET /dashboard/root/accounts/{id} | Root\AccountController@show |
| PATCH /api/v1/users/{id} | PUT /dashboard/root/accounts/{id} | Root\AccountController@update |
| DELETE /api/v1/users/{id} | DELETE /dashboard/root/accounts/{id} | Root\AccountController@destroy |
| GET /api/v1/companies | GET /dashboard/root/companies | Root\DashboardController@companies |
| GET /api/v1/interns | GET /dashboard/root/interns | Root\DashboardController@interns |
| GET /api/v1/programs | GET /dashboard/root/programs | Root\ProgramController@index |
| GET /api/v1/programs/{id} | GET /dashboard/root/programs/{id} | Root\ProgramController@show |
| PATCH /api/v1/programs/{id} | PUT /dashboard/root/programs/{id} | Root\ProgramController@update |
| DELETE /api/v1/programs/{id} | DELETE /dashboard/root/programs/{id} | Root\ProgramController@destroy |
| GET /api/v1/applications | GET /dashboard/root/applications | Root\ApplicationController@index |
| GET /api/v1/applications/{id} | GET /dashboard/root/applications/{id} | Root\ApplicationController@show |
| GET /api/v1/certificates | GET /dashboard/root/certificates | Root\CertificateController@index |
| GET /api/v1/certificates/{id} | GET /dashboard/root/certificates/{id} | Root\CertificateController@show |
| POST /api/v1/certificates/{id}/issue | POST /dashboard/root/certificates/{id}/issue | Root\CertificateController@issue |
| GET /api/v1/notifications | GET /dashboard/root/notifications | Root\NotificationController@index |
| PATCH /api/v1/notifications/{id}/read | POST /dashboard/root/notifications/{id}/read | Root\NotificationController@markRead |
| PATCH /api/v1/notifications/read-all | POST /dashboard/root/notifications/read-all | Root\NotificationController@markAllRead |

### COMPANY ROUTES

| FastAPI | Laravel | Controller |
|---------|---------|------------|
| GET /api/v1/companies/{id} | GET /dashboard/company/profile | Company\ProfileController@edit |
| PATCH /api/v1/companies/{id} | PUT /dashboard/company/profile | Company\ProfileController@update |
| GET /api/v1/programs (own) | GET /dashboard/company/programs | Company\ProgramController@index |
| POST /api/v1/programs | POST /dashboard/company/programs | Company\ProgramController@store |
| GET /api/v1/programs/{id} | GET /dashboard/company/programs/{slug} | Company\ProgramController@show |
| PATCH /api/v1/programs/{id} | PUT /dashboard/company/programs/{slug} | Company\ProgramController@update |
| DELETE /api/v1/programs/{id} | DELETE /dashboard/company/programs/{slug} | Company\ProgramController@destroy |
| GET /api/v1/programs/{id}/applications | GET /dashboard/company/applications | Company\ApplicationController@index |
| PATCH /api/v1/applications/{id}/accept | POST /dashboard/company/applications/{id}/accept | Company\ApplicationController@accept |
| PATCH /api/v1/applications/{id}/reject | POST /dashboard/company/applications/{id}/reject | Company\ApplicationController@reject |
| GET /api/v1/works (own) | GET /dashboard/company/works | Company\WorkController@index |
| POST /api/v1/works | POST /dashboard/company/works | Company\WorkController@store |
| GET /api/v1/works/{id} | GET /dashboard/company/works/{slug} | Company\WorkController@show |
| PATCH /api/v1/works/{id} | PUT /dashboard/company/works/{slug} | Company\WorkController@update |
| DELETE /api/v1/works/{id} | DELETE /dashboard/company/works/{slug} | Company\WorkController@destroy |
| POST /api/v1/works/{id}/gallery | POST /dashboard/company/works/{id}/gallery | Company\WorkController@addGallery |
| DELETE /api/v1/gallery/{id} | DELETE /dashboard/company/gallery/{id} | Company\WorkController@removeGallery |
| GET /api/v1/works/{id}/interns | GET /dashboard/company/works/{id}/interns | Company\WorkController@interns |
| POST /api/v1/works/{id}/interns | POST /dashboard/company/works/{id}/interns | Company\WorkController@addIntern |
| DELETE /api/v1/works/{id}/interns/{intern_id} | DELETE /dashboard/company/works/{id}/interns/{intern_id} | Company\WorkController@removeIntern |
| GET /api/v1/certificates (own programs) | GET /dashboard/company/certificates | Company\CertificateController@index |
| POST /api/v1/programs/{id}/certificates | POST /dashboard/company/programs/{id}/certificates | Company\CertificateController@store |
| PATCH /api/v1/certificates/{id}/mark-eligible | POST /dashboard/company/certificates/{id}/eligible | Company\CertificateController@markEligible |
| POST /api/v1/certificates/{id}/issue | POST /dashboard/company/certificates/{id}/issue | Company\CertificateController@issue |

### INTERN ROUTES

| FastAPI | Laravel | Controller |
|---------|---------|------------|
| GET /api/v1/programs | GET /dashboard/intern/programs | Intern\ProgramController@index |
| GET /api/v1/programs/{id} | GET /dashboard/intern/programs/{slug} | Intern\ProgramController@show |
| POST /api/v1/applications | POST /dashboard/intern/applications | Intern\ApplicationController@store |
| GET /api/v1/applications/me | GET /dashboard/intern/applications | Intern\ApplicationController@index |
| PATCH /api/v1/applications/{id}/cancel | POST /dashboard/intern/applications/{id}/cancel | Intern\ApplicationController@cancel |
| GET /api/v1/certificates/me | GET /dashboard/intern/certificates | Intern\CertificateController@index |
| GET /api/v1/certificates/{id}/download | GET /dashboard/intern/certificates/{id}/download | Intern\CertificateController@download |

### FILE ROUTES (authenticated)

| FastAPI | Laravel | Controller |
|---------|---------|------------|
| POST /api/v1/files/upload | POST /files/upload | FileController@upload |
| GET /api/v1/files/{id}/download | GET /files/{id}/download | FileController@download |
| DELETE /api/v1/files/{id} | DELETE /files/{id} | FileController@destroy |

---

## 2. MAPPING NEXT.JS PAGES → BLADE

### PUBLIC PAGES

| Next.js Page | Blade View | Route |
|--------------|------------|-------|
| (marketing)/page.tsx | home.blade.php | GET / |
| (marketing)/magang/page.tsx | public/programs/index.blade.php | GET /magang |
| (marketing)/magang/[slug]/page.tsx | public/programs/show.blade.php | GET /magang/{slug} |
| (marketing)/perusahaan/page.tsx | public/companies/index.blade.php | GET /perusahaan |
| (marketing)/perusahaan/[slug]/page.tsx | public/companies/show.blade.php | GET /perusahaan/{slug} |
| (marketing)/karya/page.tsx | public/works/index.blade.php | GET /karya |
| (marketing)/karya/[slug]/page.tsx | public/works/show.blade.php | GET /karya/{slug} |
| (marketing)/lokasi/page.tsx | public/locations/index.blade.php | GET /lokasi |
| (marketing)/lokasi/[slug]/page.tsx | public/locations/show.blade.php | GET /lokasi/{slug} |
| (marketing)/kreator/page.tsx | public/creators/index.blade.php | GET /kreator |
| (marketing)/kreator/[slug]/page.tsx | public/creators/show.blade.php | GET /kreator/{slug} |
| (marketing)/intern/[slug]/page.tsx | public/interns/show.blade.php | GET /intern/{slug} |

### AUTH PAGES

| Next.js Page | Blade View | Route |
|--------------|------------|-------|
| login/page.tsx | auth/login.blade.php | GET /login |
| auth/callback/page.tsx | - (handled by controller) | GET /auth/google/callback |
| onboarding/page.tsx | onboarding/index.blade.php | GET /onboarding |

### DASHBOARD - ROOT

| Next.js Page | Blade View | Route |
|--------------|------------|-------|
| dashboard/root/page.tsx | dashboard/root/index.blade.php | GET /dashboard/root |
| dashboard/root/accounts/page.tsx | dashboard/root/accounts/index.blade.php | GET /dashboard/root/accounts |
| dashboard/root/accounts/new/page.tsx | dashboard/root/accounts/create.blade.php | GET /dashboard/root/accounts/create |
| dashboard/root/accounts/[id]/page.tsx | dashboard/root/accounts/show.blade.php | GET /dashboard/root/accounts/{id} |
| dashboard/root/accounts/[id]/edit/page.tsx | dashboard/root/accounts/edit.blade.php | GET /dashboard/root/accounts/{id}/edit |
| dashboard/root/companies/page.tsx | dashboard/root/companies.blade.php | GET /dashboard/root/companies |
| dashboard/root/interns/page.tsx | dashboard/root/interns.blade.php | GET /dashboard/root/interns |
| dashboard/root/programs/page.tsx | dashboard/root/programs/index.blade.php | GET /dashboard/root/programs |
| dashboard/root/applications/page.tsx | dashboard/root/applications/index.blade.php | GET /dashboard/root/applications |
| dashboard/root/certificates/page.tsx | dashboard/root/certificates/index.blade.php | GET /dashboard/root/certificates |
| dashboard/root/notifications/page.tsx | dashboard/root/notifications/index.blade.php | GET /dashboard/root/notifications |
| dashboard/root/works/page.tsx | dashboard/root/works/index.blade.php | GET /dashboard/root/works |
| dashboard/root/contributors/page.tsx | dashboard/root/contributors/index.blade.php | GET /dashboard/root/contributors |
| dashboard/root/lokasi/page.tsx | dashboard/root/locations/index.blade.php | GET /dashboard/root/locations |
| dashboard/root/kost/page.tsx | dashboard/root/kost/index.blade.php | GET /dashboard/root/kost |
| dashboard/root/participants/page.tsx | dashboard/root/participants.blade.php | GET /dashboard/root/participants |

### DASHBOARD - COMPANY

| Next.js Page | Blade View | Route |
|--------------|------------|-------|
| dashboard/company/page.tsx | dashboard/company/index.blade.php | GET /dashboard/company |
| dashboard/company/profile/page.tsx | dashboard/company/profile/edit.blade.php | GET /dashboard/company/profile |
| dashboard/company/programs/page.tsx | dashboard/company/programs/index.blade.php | GET /dashboard/company/programs |
| dashboard/company/programs/new/page.tsx | dashboard/company/programs/create.blade.php | GET /dashboard/company/programs/create |
| dashboard/company/programs/[id]/page.tsx | dashboard/company/programs/show.blade.php | GET /dashboard/company/programs/{slug} |
| dashboard/company/programs/[id]/edit/page.tsx | dashboard/company/programs/edit.blade.php | GET /dashboard/company/programs/{slug}/edit |
| dashboard/company/applications/page.tsx | dashboard/company/applications/index.blade.php | GET /dashboard/company/applications |
| dashboard/company/applications/[id]/page.tsx | dashboard/company/applications/show.blade.php | GET /dashboard/company/applications/{id} |
| dashboard/company/works/page.tsx | dashboard/company/works/index.blade.php | GET /dashboard/company/works |
| dashboard/company/works/new/page.tsx | dashboard/company/works/create.blade.php | GET /dashboard/company/works/create |
| dashboard/company/works/[id]/page.tsx | dashboard/company/works/show.blade.php | GET /dashboard/company/works/{slug} |
| dashboard/company/works/[id]/edit/page.tsx | dashboard/company/works/edit.blade.php | GET /dashboard/company/works/{slug}/edit |
| dashboard/company/interns/page.tsx | dashboard/company/interns.blade.php | GET /dashboard/company/interns |
| dashboard/company/certificates/page.tsx | dashboard/company/certificates/index.blade.php | GET /dashboard/company/certificates |
| dashboard/company/notifications/page.tsx | dashboard/company/notifications/index.blade.php | GET /dashboard/company/notifications |

### DASHBOARD - INTERN

| Next.js Page | Blade View | Route |
|--------------|------------|-------|
| dashboard/intern/page.tsx | dashboard/intern/index.blade.php | GET /dashboard/intern |
| dashboard/intern/profile/page.tsx | dashboard/intern/profile/edit.blade.php | GET /dashboard/intern/profile |
| dashboard/intern/programs/page.tsx | dashboard/intern/programs/index.blade.php | GET /dashboard/intern/programs |
| dashboard/intern/program/[slug]/page.tsx | dashboard/intern/programs/show.blade.php | GET /dashboard/intern/programs/{slug} |
| dashboard/intern/applications/page.tsx | dashboard/intern/applications/index.blade.php | GET /dashboard/intern/applications |
| dashboard/intern/applications/[id]/page.tsx | dashboard/intern/applications/show.blade.php | GET /dashboard/intern/applications/{id} |
| dashboard/intern/works/page.tsx | dashboard/intern/works/index.blade.php | GET /dashboard/intern/works |
| dashboard/intern/certificates/page.tsx | dashboard/intern/certificates/index.blade.php | GET /dashboard/intern/certificates |
| dashboard/intern/notifications/page.tsx | dashboard/intern/notifications/index.blade.php | GET /dashboard/intern/notifications |

### DASHBOARD - KREATOR

| Next.js Page | Blade View | Route |
|--------------|------------|-------|
| dashboard/kreator/page.tsx | dashboard/kreator/index.blade.php | GET /dashboard/kreator |
| dashboard/kreator/profil/page.tsx | dashboard/kreator/profile/edit.blade.php | GET /dashboard/kreator/profile |
| dashboard/kreator/karya/page.tsx | dashboard/kreator/works/index.blade.php | GET /dashboard/kreator/works |
| dashboard/kreator/karya/new/page.tsx | dashboard/kreator/works/create.blade.php | GET /dashboard/kreator/works/create |
| dashboard/kreator/karya/[id]/page.tsx | dashboard/kreator/works/show.blade.php | GET /dashboard/kreator/works/{slug} |
| dashboard/kreator/karya/[id]/edit/page.tsx | dashboard/kreator/works/edit.blade.php | GET /dashboard/kreator/works/{slug}/edit |
| dashboard/kreator/notifications/page.tsx | dashboard/kreator/notifications/index.blade.php | GET /dashboard/kreator/notifications |

---

## 3. MAPPING ROLE & PERMISSION

| Role | Dashboard | Can Do |
|------|-----------|--------|
| ROOT | /dashboard/root | Manage accounts, view all data, oversee everything |
| COMPANY | /dashboard/company | Manage profile, programs, works, applications, certificates |
| INTERN | /dashboard/intern | Browse programs, apply, view certificates, view works |
| KREATOR | /dashboard/kreator | Manage profile, create/manage public works |

### Authorization Rules:

**ROOT:**
- Can access all data
- Can create/edit/delete accounts (COMPANY, INTERN, KREATOR)
- Can view all programs, applications, certificates
- Can mark certificates eligible
- Cannot create/edit/delete ROOT accounts

**COMPANY:**
- Can manage own profile
- Can create/edit/delete own programs
- Can view/manage applications for own programs
- Can accept/reject applications
- Can create/edit/delete own works
- Can manage gallery for own works
- Can add/remove interns to own works
- Can issue certificates for own programs
- Can view/download CV of applicants

**INTERN:**
- Can manage own profile
- Can browse programs
- Can apply to programs (if profile complete)
- Can cancel pending applications
- Can view own applications status
- Can view/download own certificates
- Can view works they participate in

**KREATOR:**
- Can manage own profile
- Can create/edit/delete own public works
- Can manage gallery for own works

---

## 4. MAPPING DATABASE

### Tables (19 tables - EXISTING, DO NOT MODIFY):

1. **profiles** - User accounts (ROOT, COMPANY, INTERN, KREATOR)
2. **company_profiles** - Company profiles (1:1 with profiles)
3. **intern_profiles** - Intern profiles (1:1 with profiles)
4. **creator_profiles** - Creator profiles (1:1 with profiles)
5. **internship_programs** - Internship programs (belongs to company)
6. **internship_positions** - Positions within programs
7. **internship_applications** - Applications from interns
8. **application_status_histories** - Application status changes
9. **program_interns** - Accepted participants
10. **program_banners** - Banner images for programs
11. **certificates** - Certificates for completed programs
12. **notifications** - In-app notifications
13. **files** - File metadata (actual files in Supabase Storage)
14. **works** - Creative works (PROGRAM_WORK or PUBLIC_WORK)
15. **work_gallery** - Gallery images for works
16. **work_interns** - Intern participants in works
17. **contributors** - Partner/contributor organizations
18. **locations** - Physical locations
19. **kost_places** - Boarding houses near locations

### Key Relationships:

```
profiles (1) → (1) company_profiles
profiles (1) → (1) intern_profiles
profiles (1) → (1) creator_profiles
profiles (1) → (n) notifications

company_profiles (1) → (n) internship_programs
company_profiles (1) → (n) works (PROGRAM_WORK)

intern_profiles (1) → (n) internship_applications
intern_profiles (1) → (n) certificates
intern_profiles (1) → (n) work_interns

internship_programs (1) → (n) internship_positions
internship_programs (1) → (n) internship_applications
internship_programs (1) → (n) program_interns
internship_programs (1) → (n) program_banners

internship_applications (1) → (n) application_status_histories
internship_applications (n) → (1) program_interns (when accepted)

certificates (n) → (1) internship_programs
certificates (n) → (1) intern_profiles
certificates (n) → (1) files (PDF)

works (1) → (n) work_gallery
works (1) → (n) work_interns
works (n) → (1) files (poster)
works (n) → (1) files (media)

contributors (n) → (1) files (logo)
locations (1) → (n) company_profiles
locations (1) → (n) internship_programs
```

---

## 5. MAPPING STORAGE

### Supabase Storage Buckets:

| Bucket | Used For | Access |
|--------|----------|--------|
| cvs | Intern CVs (PDF only) | Private - owner + company + root |
| photos | Profile photos | Private - owner + root |
| logos | Company logos | Private - owner + root |
| banners | Program banners | Private - owner + root |
| works | Work images/videos | Private - owner + root |
| certificates | Certificate PDFs | Private - owner + company + root |
| general | Other files | Private - owner + root |

### File Access Rules:

**CV (cvs bucket):**
- Owner (intern) can download
- Company can download CV of applicants to their programs
- Root can download all

**Certificate (certificates bucket):**
- Owner (intern) can download (only if ISSUED)
- Company can download certificates for their programs
- Root can download all

**Other files:**
- Uploader can access
- Root can access

---

## 6. MAPPING EMAIL

### Email Events:

| Event | Subject | Recipient | Trigger |
|-------|---------|-----------|---------|
| APPLICATION_ACCEPTED | YUHLEZ - Pendaftaran Magang Anda Diterima | Intern | Company accepts application |
| APPLICATION_REJECTED | YUHLEZ - Hasil Pendaftaran Magang | Intern | Company rejects application |
| PROGRAM_UPDATE | YUHLEZ - Program Magang Diperbarui | Intern participants | Company updates program |
| PROFILE_UPDATE | YUHLEZ - Profil Anda Diperbarui | User | User updates profile |
| CERTIFICATE_AVAILABLE | YUHLEZ - Sertifikat Magang Anda | Intern | Root marks certificate eligible + issues |

### Email Configuration:

- SMTP: smtp.gmail.com:587
- From: yuhlez.notifikasi@gmail.com
- Username: bintangmahardika33335@gmail.com
- Password: (App Password)
- TLS: true

---

## 7. MAPPING NOTIFICATION

### Notification Types:

| Type | Title | Message Template | Web | Email |
|------|-------|------------------|-----|-------|
| APPLICATION_ACCEPTED | Pendaftaran Diterima | "Selamat! Pendaftaran kamu untuk program '{title}' diterima." | ✓ | ✓ |
| APPLICATION_REJECTED | Pendaftaran Ditolak | "Pendaftaran kamu untuk program '{title}' ditolak." | ✓ | ✓ |
| PROGRAM_UPDATE | Program diperbarui | "Program magang '{title}' baru saja diperbarui." | ✓ | ✓ |
| PROFILE_UPDATE | Profil Diperbarui | "Profil berhasil diperbarui." | ✓ | ✓ |
| CERTIFICATE_AVAILABLE | Sertifikat Tersedia | "Sertifikat untuk program '{title}' sudah diterbitkan." | ✓ | ✓ |
| SYSTEM | System notification | (varies) | ✓ | No |

---

## 8. RANCANGAN LANDING PAGE BARU

### Struktur Landing Page YUHLEZ:

```
HERO (dark zinc-950, yellow accents)
├── Headline: "From Useless to YUHLEZ"
├── Sub: "The Best Solution for Website & Web Apps"
├── Description
├── CTA: Lihat Program Magang | Lihat Portfolio | Masuk
└── Brand info card

TENTANG
├── Visi
└── Misi

TIM
├── Adjiemas (CEO)
├── Ratono (CTO)
└── Cabit (Designer)

LAYANAN
├── Web Design
├── Web Apps
├── IT Consultant
├── Extend Project
├── Mini ERP/POS/Inventory
└── Database & Infrastruktur

PORTFOLIO
├── Garasi Asatu Spector
├── E-Performance Polkesjati
├── SIMPENDI Poltek Harber
├── E-Renggar Kemenkes
├── Website SPASI
├── Website LAMFI
├── Website TCF
└── Website Poltek Harber

KAPABILITAS & TEKNOLOGI
├── RnD Team
├── Dedicated Programmer
├── IT Hardware
└── YUHLEZ Academy

KONTRIBUTOR & KOLABORATOR
└── Marquee/contributor cards

EKOSISTEM
├── SPASI Creative Space
├── Politeknik Harapan Bersama
├── Sinema Pantura
└── Tegal Children's Festival

PROGRAM MAGANG (from DB)
├── 6 latest programs
└── Lihat semua

PERUSAHAAN (from DB)
├── 6 latest companies
└── (no pagination needed on landing)

KARYA (from DB)
├── 6 latest works
└── Lihat semua

CARA KERJA
├── 01 Konsultasi
├── 02 Perancangan
├── 03 Pengembangan
└── 04 Peluncuran & Dukungan

CTA (dark zinc-950)
├── "Sudah siap untuk go digital?"
├── Hubungi Kami
├── WhatsApp
└── Jelajahi Program Magang

FOOTER
├── Brand info
├── Links
└── Copyright
```

### Design System:

- **Primary**: zinc-950 (dark), yellow-400 (accent)
- **Background**: white, zinc-50 (alternating)
- **Text**: zinc-900, zinc-600, zinc-400
- **Cards**: rounded-2xl, border zinc-200, shadow-sm
- **Buttons**: rounded-xl, font-semibold
- **Typography**: Inter font family
- **Responsive**: grid 1-2-3-4 columns

---

## 9. RANCANGAN KARYA/GALLERY NETFLIX-STYLE

### Halaman Karya (/karya):

```
KARYA YUHLEZ

Filter:
[Semua] [Karya Program] [Karya Kreator]
[Film] [Dokumenter] [Fotografi] [Desain] [Teknologi] [Multimedia] [Lainnya]

Grid (3 columns):
┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│   POSTER    │ │   POSTER    │ │   POSTER    │
│             │ │             │ │             │
├─────────────┤ ├─────────────┤ ├─────────────┤
│ Title       │ │ Title       │ │ Title       │
│ Badge: Type │ │ Badge: Type │ │ Badge: Type │
│ Description │ │ Description │ │ Description │
│ Company     │ │ Company     │ │ Company     │
│ Date        │ │ Date        │ │ Date        │
└─────────────┘ └─────────────┘ └─────────────┘
```

### Detail Karya (/karya/{slug}):

```
KARYA YUHLEZ

[Badge: Karya Program] [Badge: Film] [Tahun 2024]

# Judul Karya
oleh Perusahaan/Kreator

Deskripsi singkat...

Deskripsi lengkap (rich text)...

## Galeri
[X foto - akses file asli tersedia untuk akun yang terhubung]

[Image Grid - lazy loaded]

## Peserta Karya
[Participant 1] [Participant 2] [Participant 3]

← Kembali ke galeri karya
```

### Visual Style:

- **Grid**: masonry-like or uniform grid
- **Cards**: poster image top, info bottom
- **Hover**: subtle shadow lift
- **Lazy loading**: all images
- **Responsive**: 1 col mobile, 2 col tablet, 3 col desktop
- **Filter pills**: rounded-full, active = zinc-900 text yellow-400

---

## 10. STRATEGI OPTIMASI QUERY & LOADING

### Homepage:

```php
// 1 query with eager loading
$programs = InternshipProgram::with('company')
    ->whereNull('deleted_at')
    ->where('registration_end', '>=', now())
    ->latest()
    ->limit(6)
    ->get();

$companies = CompanyProfile::with('user')
    ->whereNull('deleted_at')
    ->latest()
    ->limit(6)
    ->get();

$works = Work::with(['company', 'creator', 'poster'])
    ->whereNull('deleted_at')
    ->where('is_published', true)
    ->latest()
    ->limit(6)
    ->get();

$contributors = Contributor::whereNull('deleted_at')
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();
```

### Listing Pages:

```php
// Paginated with selective columns
$programs = InternshipProgram::select('id', 'slug', 'title', 'short_description', 'company_id', 'registration_end')
    ->with('company:name,slug')
    ->whereNull('deleted_at')
    ->latest()
    ->paginate(12);
```

### Dashboard:

```php
// Aggregate queries for stats
$stats = [
    'total_programs' => InternshipProgram::where('company_id', $company->id)
        ->whereNull('deleted_at')
        ->count(),
    'pending_applications' => InternshipApplication::whereHas('program', fn($q) => $q->where('company_id', $company->id))
        ->whereNull('deleted_at')
        ->where('status', 'PENDING')
        ->count(),
];
```

### Avoid N+1:

```php
// BAD
foreach ($programs as $program) {
    echo $program->company->name; // N+1 query!
}

// GOOD
$programs = InternshipProgram::with('company')->get();
foreach ($programs as $program) {
    echo $program->company->name; // No extra query
}
```

---

## 11. CHECKLIST PERBANDINGAN FITUR

### FastAPI Routes vs Laravel Routes:

- [ ] GET /public/programs
- [ ] GET /public/programs/{slug}
- [ ] GET /public/companies
- [ ] GET /public/companies/{identifier}
- [ ] GET /public/works
- [ ] GET /public/works/{slug}
- [ ] GET /public/locations
- [ ] GET /public/locations/{identifier}
- [ ] GET /public/creators
- [ ] GET /public/creators/{identifier}
- [ ] GET /public/contributors
- [ ] GET /public/interns/{identifier}
- [ ] GET /public/kost/search
- [ ] GET /api/v1/auth/google
- [ ] GET /api/v1/auth/google/callback
- [ ] POST /api/v1/auth/logout
- [ ] GET /api/v1/me
- [ ] PATCH /api/v1/me
- [ ] GET /api/v1/users (CRUD)
- [ ] GET /api/v1/companies
- [ ] GET /api/v1/interns
- [ ] GET /api/v1/programs (CRUD)
- [ ] GET /api/v1/applications
- [ ] PATCH /api/v1/applications/{id}/accept
- [ ] PATCH /api/v1/applications/{id}/reject
- [ ] GET /api/v1/certificates
- [ ] POST /api/v1/certificates/{id}/issue
- [ ] GET /api/v1/works (CRUD)
- [ ] POST /api/v1/works/{id}/gallery
- [ ] DELETE /api/v1/gallery/{id}
- [ ] GET /api/v1/works/{id}/interns
- [ ] POST /api/v1/works/{id}/interns
- [ ] DELETE /api/v1/works/{id}/interns/{intern_id}
- [ ] GET /api/v1/notifications
- [ ] PATCH /api/v1/notifications/{id}/read
- [ ] PATCH /api/v1/notifications/read-all
- [ ] POST /api/v1/files/upload
- [ ] GET /api/v1/files/{id}/download
- [ ] DELETE /api/v1/files/{id}

### Next.js Pages vs Blade Views:

- [ ] Landing page
- [ ] Programs listing
- [ ] Program detail
- [ ] Companies listing
- [ ] Company detail
- [ ] Works listing
- [ ] Work detail
- [ ] Locations listing
- [ ] Location detail
- [ ] Creators listing
- [ ] Creator detail
- [ ] Contributors listing
- [ ] Intern profile (public)
- [ ] Login
- [ ] Onboarding
- [ ] Root dashboard
- [ ] Root accounts CRUD
- [ ] Root companies listing
- [ ] Root interns listing
- [ ] Root programs
- [ ] Root applications
- [ ] Root certificates
- [ ] Root notifications
- [ ] Root works
- [ ] Root contributors
- [ ] Root locations
- [ ] Root kost
- [ ] Root participants
- [ ] Company dashboard
- [ ] Company profile
- [ ] Company programs CRUD
- [ ] Company applications
- [ ] Company works CRUD
- [ ] Company gallery
- [ ] Company interns
- [ ] Company certificates
- [ ] Company notifications
- [ ] Intern dashboard
- [ ] Intern profile
- [ ] Intern programs
- [ ] Intern applications
- [ ] Intern works
- [ ] Intern certificates
- [ ] Intern notifications
- [ ] Kreator dashboard
- [ ] Kreator profile
- [ ] Kreator works CRUD
- [ ] Kreator notifications

---

## CATATAN PENTING

1. **JANGAN mengubah database existing** - Semua 19 tabel sudah ada dengan data production
2. **JANGAN membuat UI generik** - Harus sesuai branding YUHLEZ (dark zinc + yellow)
3. **JANGAN menghapus fitur** - Semua fitur FastAPI + Next.js harus ada di Laravel
4. **Certificate oleh COMPANY, bukan ROOT** - ROOT hanya oversee/marking eligible
5. **Work/Karya = Company-owned** - Company membuat karya, menambahkan intern sebagai peserta
6. **Intern hanya VIEW karya** - Tidak membuat/mengedit/menghapus karya
7. **Landing page harus profesional** - Bukan admin panel, tapi platform showcase
8. **Gallery karya harus cinematic** - Netflix/Behance style, bukan table/CRUD cards
9. **Performance target** - Landing page secepat mungkin, dashboard tanpa request berulang
10. **Mobile-first** - Responsive di semua device
