# Example Implementations - Bangladesh Consultant Website

This document provides code examples for key features to help you get started quickly.

## 📦 Database Migration Example

### Services Table Migration

```php
<?php
// database/migrations/2025_01_27_000001_create_services_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('services')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('parent_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
```

### Leads Table Migration

```php
<?php
// database/migrations/2025_01_27_000002_create_leads_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->text('message');
            $table->enum('source', ['contact_form', 'callback_request', 'quote_request'])->default('contact_form');
            $table->enum('status', ['new', 'contacted', 'converted', 'closed'])->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('service_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
```

## 🏗️ Model Examples

### Service Model

```php
<?php
// app/Models/Service.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'parent_id',
        'slug',
        'title',
        'description',
        'content',
        'icon',
        'image',
        'meta_title',
        'meta_description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Service::class, 'parent_id')->orderBy('sort_order');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    // Accessors
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }
}
```

### Lead Model

```php
<?php
// app/Models/Lead.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'service_id',
        'message',
        'source',
        'status',
        'assigned_to',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class);
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
```

## 🎮 Controller Examples

### Service Controller

```php
<?php
// app/Http/Controllers/Public/ServiceController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $services = Service::parents()
            ->active()
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('public/Services/Index', [
            'services' => $services,
        ]);
    }

    public function show(Service $service): Response
    {
        $service->load('parent', 'children');

        $relatedServices = Service::where('parent_id', $service->parent_id)
            ->where('id', '!=', $service->id)
            ->active()
            ->limit(3)
            ->get();

        return Inertia::render('public/Services/Show', [
            'service' => $service,
            'relatedServices' => $relatedServices,
        ]);
    }

    public function showChild(Service $parent, Service $child): Response
    {
        if ($child->parent_id !== $parent->id) {
            abort(404);
        }

        return Inertia::render('public/Services/Show', [
            'service' => $child,
            'parent' => $parent,
        ]);
    }
}
```

### Contact Controller

```php
<?php
// app/Http/Controllers/Public/ContactController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        $services = \App\Models\Service::parents()->active()->get();

        return Inertia::render('public/Contact', [
            'services' => $services,
        ]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $lead = Lead::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'service_id' => $request->service_id,
            'message' => $request->message,
            'source' => 'contact_form',
            'status' => 'new',
        ]);

        // Send notification to admin
        Mail::to(config('mail.admin_email', 'mbscfirm@gmail.com'))
            ->send(new \App\Mail\NewLeadNotification($lead));

        // Send confirmation to user
        Mail::to($lead->email)
            ->send(new \App\Mail\ContactConfirmation($lead));

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
```

## 📝 Request Validation Examples

### Contact Request

```php
<?php
// app/Http/Requests/ContactRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'service_id' => ['nullable', 'exists:services,id'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your name.',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'message.required' => 'Please provide your message.',
        ];
    }
}
```

## 🎨 Vue Component Examples

### ServiceCard.vue

```vue
<template>
    <div class="group relative overflow-hidden rounded-lg border border-sidebar-border bg-card p-6 transition-all hover:shadow-lg">
        <div class="mb-4 flex items-center gap-3">
            <div
                v-if="service.icon"
                class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary"
            >
                <Icon :name="service.icon" class="h-6 w-6" />
            </div>
            <h3 class="text-lg font-semibold">{{ service.title }}</h3>
        </div>
        
        <p class="mb-4 text-sm text-muted-foreground line-clamp-3">
            {{ service.description }}
        </p>
        
        <div v-if="service.children && service.children.length > 0" class="mb-4">
            <p class="mb-2 text-xs font-medium text-muted-foreground">Sub-services:</p>
            <ul class="space-y-1">
                <li
                    v-for="child in service.children.slice(0, 3)"
                    :key="child.id"
                    class="text-xs text-muted-foreground"
                >
                    • {{ child.title }}
                </li>
                <li
                    v-if="service.children.length > 3"
                    class="text-xs text-muted-foreground"
                >
                    +{{ service.children.length - 3 }} more
                </li>
            </ul>
        </div>
        
        <Link
            :href="route('services.show', service.slug)"
            class="inline-flex items-center gap-2 text-sm font-medium text-primary hover:underline"
        >
            Learn More
            <Icon name="arrow-right" class="h-4 w-4" />
        </Link>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Icon from '@/components/Icon.vue';

interface Props {
    service: {
        id: number;
        slug: string;
        title: string;
        description: string | null;
        icon: string | null;
        children?: Array<{
            id: number;
            title: string;
        }>;
    };
}

defineProps<Props>();
</script>
```

### ContactForm.vue

```vue
<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium">
                    Name <span class="text-destructive">*</span>
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                    :class="{ 'border-destructive': form.errors.name }"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium">
                    Email <span class="text-destructive">*</span>
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                    :class="{ 'border-destructive': form.errors.email }"
                />
                <InputError :message="form.errors.email" />
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="phone" class="mb-2 block text-sm font-medium">
                    Phone
                </label>
                <input
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                />
            </div>

            <div>
                <label for="service_id" class="mb-2 block text-sm font-medium">
                    Service Interested In
                </label>
                <select
                    id="service_id"
                    v-model="form.service_id"
                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                >
                    <option value="">Select a service...</option>
                    <option
                        v-for="service in services"
                        :key="service.id"
                        :value="service.id"
                    >
                        {{ service.title }}
                    </option>
                </select>
            </div>
        </div>

        <div>
            <label for="message" class="mb-2 block text-sm font-medium">
                Message <span class="text-destructive">*</span>
            </label>
            <textarea
                id="message"
                v-model="form.message"
                rows="5"
                required
                class="w-full rounded-md border border-input bg-background px-3 py-2"
                :class="{ 'border-destructive': form.errors.message }"
            />
            <InputError :message="form.errors.message" />
        </div>

        <Button type="submit" :disabled="form.processing">
            <span v-if="form.processing">Sending...</span>
            <span v-else>Send Message</span>
        </Button>
    </form>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import InputError from '@/components/InputError.vue';

interface Service {
    id: number;
    title: string;
}

interface Props {
    services?: Service[];
}

const props = withDefaults(defineProps<Props>(), {
    services: () => [],
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    company: '',
    service_id: null as number | null,
    message: '',
});

const submit = () => {
    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
```

## 🛣️ Route Examples

### Public Routes

```php
<?php
// routes/public.php

use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\ServiceController;
use Illuminate\Support\Facades\Route;

// Services
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/services/{parent:slug}/{child:slug}', [ServiceController::class, 'showChild'])
    ->name('services.child');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
```

## 📧 Mail Example

### New Lead Notification

```php
<?php
// app/Mail/NewLeadNotification.php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLeadNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead)
    {
    }

    public function build(): self
    {
        return $this->subject('New Contact Form Submission - ' . $this->lead->name)
            ->view('emails.lead-notification')
            ->with([
                'lead' => $this->lead,
            ]);
    }
}
```

## 📊 Seeder Example

### Services Seeder

```php
<?php
// database/seeders/ServiceSeeder.php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Company Registration',
                'slug' => 'company-registration',
                'description' => 'Expert company registration services in Bangladesh',
                'icon' => 'building',
                'sort_order' => 1,
                'children' => [
                    ['title' => 'Private Company', 'slug' => 'private-company'],
                    ['title' => 'Public Company', 'slug' => 'public-company'],
                    ['title' => 'Branch Office', 'slug' => 'branch-office'],
                ],
            ],
            [
                'title' => 'Payroll Services',
                'slug' => 'payroll-services',
                'description' => 'Comprehensive payroll management solutions',
                'icon' => 'users',
                'sort_order' => 2,
            ],
            // Add more services...
        ];

        foreach ($services as $serviceData) {
            $children = $serviceData['children'] ?? [];
            unset($serviceData['children']);

            $service = Service::create($serviceData);

            foreach ($children as $child) {
                Service::create([
                    ...$child,
                    'parent_id' => $service->id,
                ]);
            }
        }
    }
}
```

---

These examples provide a solid foundation to start building the Bangladesh Consultant website. Customize them based on your specific requirements!
