# Route Import Fixes

## Issue
Components were trying to import a non-existent `route` helper function from `@/routes`, causing errors:
```
The requested module '/resources/js/routes/index.ts' does not provide an export named 'route'
```

## Solution
Wayfinder generates individual route functions, not a `route()` helper. Updated all components to:

1. Import route functions from their specific modules:
   - `import { home, about } from '@/routes'` (for routes in index.ts)
   - `import * as services from '@/routes/services'` (for service routes)
   - `import * as blog from '@/routes/blog'` (for blog routes)
   - etc.

2. Use route functions with `.url` property:
   - `home().url` instead of `route('home')`
   - `services.index().url` instead of `route('services.index')`
   - `blog.show({ post: slug }).url` instead of `route('blog.show', slug)`

## Files Fixed

### Layouts
- ✅ `resources/js/layouts/public/PublicLayout.vue`

### Pages
- ✅ `resources/js/pages/public/Home.vue`
- ✅ `resources/js/pages/public/About.vue`
- ✅ `resources/js/pages/public/Contact.vue`
- ✅ `resources/js/pages/public/Services/Index.vue`
- ✅ `resources/js/pages/public/Services/Show.vue`
- ✅ `resources/js/pages/public/Blog/Index.vue`
- ✅ `resources/js/pages/public/Blog/Show.vue`
- ✅ `resources/js/pages/public/CaseStudies/Index.vue`
- ✅ `resources/js/pages/public/CaseStudies/Show.vue`
- ✅ `resources/js/pages/public/FAQ.vue`

### Components
- ✅ `resources/js/components/public/ServiceCard.vue`
- ✅ `resources/js/components/public/ContactForm.vue`
- ✅ `resources/js/components/public/BlogCard.vue`
- ✅ `resources/js/components/public/CaseStudyCard.vue`

## Route Usage Examples

```typescript
// Simple routes (no parameters)
import { home, about } from '@/routes';
home().url  // '/'
about().url  // '/about'

// Routes with namespaces
import * as services from '@/routes/services';
services.index().url  // '/services'
services.show({ service: 'company-registration' }).url  // '/services/company-registration'

// Routes with multiple parameters
services.child({ parent: 'company-registration', child: 'private-company' }).url
// '/services/company-registration/private-company'

// Blog routes
import * as blog from '@/routes/blog';
blog.index().url  // '/blog'
blog.show({ post: 'slug' }).url  // '/blog/slug'
```

All route imports have been fixed and should work correctly now!
