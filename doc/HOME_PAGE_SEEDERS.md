# Home Page Seeders

## Overview
Added seeders to populate data required for the home page display, including testimonials and blog posts.

## Seeders Created

### 1. TestimonialSeeder
**File**: `database/seeders/TestimonialSeeder.php`

**Purpose**: Seeds testimonials data for the home page testimonials section.

**Data Included**:
- 8 testimonials from various clients
- Linked to different services (Company Registration, Payroll Services, Tax & VAT Compliance, etc.)
- 3 featured testimonials (displayed on homepage)
- 5 additional testimonials (for other pages)
- All testimonials include:
  - Client name, position, and company
  - Testimonial text
  - Rating (5 stars)
  - Service association
  - Featured status
  - Active status

**Usage**:
```bash
php artisan db:seed --class=TestimonialSeeder
```

### 2. BlogPostSeeder
**File**: `database/seeders/BlogPostSeeder.php`

**Purpose**: Seeds blog posts data for the home page recent posts section.

**Data Included**:
- 6 blog posts covering various topics:
  1. Complete Guide to Company Registration in Bangladesh
  2. Understanding Tax and VAT Compliance in Bangladesh
  3. Latest Business News: Economic Growth and Investment Opportunities
  4. Legal Updates: Recent Changes in Company Law
  5. Payroll Management Best Practices for Bangladeshi Companies
  6. Accounting Software Solutions for Modern Businesses
- Each post includes:
  - Title and slug
  - Excerpt and full content (HTML formatted)
  - Category association
  - Meta title and description for SEO
  - Published status and date
  - Author (uses first available user or creates admin user)
  - View count (randomized 50-500)

**Usage**:
```bash
php artisan db:seed --class=BlogPostSeeder
```

## Integration

Both seeders have been added to `DatabaseSeeder.php` and will run automatically when executing:
```bash
php artisan db:seed
```

## Seed Order
The seeders run in the following order:
1. ServiceSeeder (testimonials depend on services)
2. BlogCategorySeeder (blog posts depend on categories)
3. StatisticSeeder
4. ContactInformationSeeder
5. FAQSeeder
6. TestimonialSeeder (requires services)
7. BlogPostSeeder (requires categories and users)

## Home Page Data Requirements

The home page (`HomeController`) requires:
- ✅ **Services** - Already seeded via `ServiceSeeder`
- ✅ **Statistics** - Already seeded via `StatisticSeeder`
- ✅ **Testimonials** - Now seeded via `TestimonialSeeder`
- ✅ **Blog Posts** - Now seeded via `BlogPostSeeder`

All required data for the home page is now available!

## Notes

- Both seeders use `firstOrCreate` to prevent duplicate entries when run multiple times
- Testimonials are linked to services by slug for easy reference
- Blog posts are linked to categories by slug
- Blog posts automatically get an author (first user or created admin user)
- All seed data is realistic and relevant to Bangladesh Consultant's services
