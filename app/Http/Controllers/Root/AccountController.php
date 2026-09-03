<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\CompanyProfile;
use App\Models\InternProfile;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Profile::whereNull('deleted_at')->where('role', '!=', 'ROOT');

        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $accounts = $query->orderByDesc('created_at')->paginate(20);

        return view('dashboard.root.accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('dashboard.root.accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:profiles,email',
            'role' => 'required|in:COMPANY,INTERN',
            'password' => 'nullable|string|min:6',
        ]);

        $profile = Profile::create([
            'id' => Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password_hash' => $validated['password'] ? Hash::make($validated['password']) : null,
        ]);

        // Create role-specific profile
        $slug = $this->generateSlug($validated['name']);

        match($validated['role']) {
            'COMPANY' => CompanyProfile::create([
                'id' => Str::uuid(),
                'user_id' => $profile->id,
                'slug' => $slug,
                'name' => $validated['name'],
                'gmail_access' => $validated['email'],
            ]),
            'INTERN' => InternProfile::create([
                'id' => Str::uuid(),
                'user_id' => $profile->id,
                'slug' => $slug,
                'name' => $validated['name'],
                'contact_email' => $validated['email'],
                'gmail_access' => $validated['email'],
            ]),
        };

        // Send email notification to new user
        $password = $validated['password'] ?? null;
        EmailService::sendAccountCreatedEmail(
            $validated['email'],
            $validated['name'],
            $validated['role'],
            $password
        );

        return redirect()->route('root.accounts.index')
            ->with('success', 'Akun berhasil dibuat.');
    }

    public function show(string $id)
    {
        $account = Profile::findOrFail($id);
        $account->load(['companyProfile', 'internProfile']);

        return view('dashboard.root.accounts.show', compact('account'));
    }

    public function edit(string $id)
    {
        $account = Profile::findOrFail($id);
        return view('dashboard.root.accounts.edit', compact('account'));
    }

    public function update(Request $request, string $id)
    {
        $account = Profile::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:profiles,email,{$id}",
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password_hash'] = Hash::make($validated['password']);
        }

        $account->update($updateData);

        return redirect()->route('root.accounts.show', $id)
            ->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $account = Profile::findOrFail($id);
        $account->delete();

        return redirect()->route('root.accounts.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    protected function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while (CompanyProfile::where('slug', $slug)->exists() || InternProfile::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }
}
