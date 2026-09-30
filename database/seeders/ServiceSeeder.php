<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Icons are Lucide line icons (ISC licence). Pictures are local files
        // listed in CREDITS.md; replace either in the admin panel.
        $services = [
            [
                'slug' => 'vat',
                'title' => 'VAT Advisory & Compliance',
                'short_description' => 'BIN registration, Mushak filings, VAT books, audit support and appeals under VAT & SD Act, 2012.',
                'description' => 'Comprehensive VAT management under the VAT and Supplementary Duty Act, 2012. We support BIN registration/amendment, central vs branch registration strategy, price declaration (Mushak-4.3), monthly VAT return filing (Mushak 9.1) and amended returns (Mushak 9.2), VDS monitoring and Mushak 6.6 certificates, mandatory VAT books (Mushak 6.1/6.2/6.3/6.10), audit & investigation representation, demand notice defense, appeals and ADR, plus VAT refund and exemption consultancy.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>',
                'features' => [
                    'BIN registration and amendment via online VAT portal',
                    'Price declaration (Mushak-4.3) preparation/submission',
                    'Monthly VAT return (Mushak 9.1) and amended return (Mushak 9.2)',
                    'VDS management and Mushak 6.6 certificates',
                    'VAT books: Mushak 6.1, 6.2, 6.3 and contractual register (Mushak 6.10)',
                    'Audit, intelligence & investigation representation',
                    'Appeals, tribunal representation and ADR',
                    'Refunds and exemption/zero-rate consultancy',
                ],
                'image_url' => '/images/seed/vat-1280.webp',
                'image_width' => 1280,
                'image_height' => 854,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'slug' => 'rjsc-limited-company',
                'title' => 'RJSC Limited Company Services',
                'short_description' => 'MoA/AoA, incorporation, director/share changes, charges, annual return, and winding up support.',
                'description' => 'Professional support for RJSC matters including drafting constitutional documents (MoA/AoA), end-to-end incorporation of private/public limited companies, director changes, share transfer/allotment, conversion from private to public, mortgage & charge creation/registration, annual return preparation and submission, and full winding up & liquidation formalities.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>',
                'features' => [
                    'Drafting MoA & AoA tailored to business objectives',
                    'Private & Public Limited Company incorporation',
                    'Appointment/resignation/removal of directors',
                    'Share transfer & share allotment processing',
                    'Conversion: Private Limited to Public Limited',
                    'Mortgage deed drafting and charge registration',
                    'Annual return preparation and submission to RJSC',
                    'Winding up & liquidation (including voluntary winding up)',
                ],
                'image_url' => '/images/seed/rjsc-limited-company-1280.webp',
                'image_width' => 1280,
                'image_height' => 851,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'slug' => 'income-tax',
                'title' => 'Income Tax Consultancy & Litigation',
                'short_description' => 'e-TIN, returns (all heads), IT-10B, corporate tax, audits, appeals/tribunal, and ADR support.',
                'description' => 'Full-spectrum income tax support for individuals, partnership firms, companies, and trusts under the Income Tax Act, 2023—from e-TIN and compliance to return preparation (all heads of income), asset & liability statement (IT-10B), investment allowance/rebates, corporate withholding tax (TDS/VDS), deferred tax computation, tax planning, and representation through audits, appeals, tribunal and High Court references including ADR.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="16" height="20" x="4" y="2" rx="2"/><line x1="8" x2="16" y1="6" y2="6"/><line x1="16" x2="16" y1="14" y2="18"/><path d="M16 10h.01"/><path d="M12 10h.01"/><path d="M8 10h.01"/><path d="M12 14h.01"/><path d="M8 14h.01"/><path d="M12 18h.01"/><path d="M8 18h.01"/></svg>',
                'features' => [
                    'E-TIN registration and updates',
                    'Tax clearance certificates (visa, bank loans, renewals)',
                    'Return preparation across all heads of income',
                    'Asset & liability statement (IT-10B)',
                    'Investment allowance & tax rebate optimization',
                    'Withholding tax (TDS/VDS) advisory and compliance',
                    'Tax audits, appeals, tribunal representation and ADR',
                    'Specialized support: transfer pricing, gift tax, capital gains, exemptions',
                ],
                'image_url' => '/images/seed/income-tax-1280.webp',
                'image_width' => 1280,
                'image_height' => 734,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'slug' => 'foundation-trust-society',
                'title' => 'Foundation, Trust & Society',
                'short_description' => 'Registration, trust deed drafting, and annual compliance for social welfare entities.',
                'description' => 'We assist in establishing and maintaining social welfare entities with high legal precision. Services include foundation registration, trust registration and trust deed drafting (private or charitable trusts), and managing annual return filing and regulatory reporting for trusts and foundations.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"/><path d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/><path d="m2 15 6 6"/><path d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z"/></svg>',
                'features' => [
                    'Foundation registration support',
                    'Trust registration and trust deed drafting',
                    'Private and charitable trust formation',
                    'Annual return filing & regulatory reporting',
                ],
                'image_url' => '/images/seed/foundation-trust-society-1280.webp',
                'image_width' => 1280,
                'image_height' => 854,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'slug' => 'rjsc-partnership-firm',
                'title' => 'Partnership Firm Services',
                'short_description' => 'Partnership deed, RJSC registration, amendments, and dissolution support.',
                'description' => 'Complete partnership support including professional partnership deed drafting, RJSC registration, legal handling of amendments (name/address/constitution changes), and managed dissolution with settlement of accounts and formal cancellation of RJSC registration.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m11 17 2 2a1 1 0 1 0 3-3"/><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/><path d="m21 3 1 11h-2"/><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"/><path d="M3 4h8"/></svg>',
                'features' => [
                    'Partnership deed drafting',
                    'Partnership firm registration with RJSC',
                    'Changes in firm name / office address / constitution',
                    'Dissolution management and formal cancellation at RJSC',
                ],
                'image_url' => '/images/seed/rjsc-partnership-firm-1280.webp',
                'image_width' => 1280,
                'image_height' => 854,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'slug' => 'audit-support',
                'title' => 'Audit Support & Financial Assurance',
                'short_description' => 'Internal audit, statutory audit readiness, reporting and IAS/IFRS financial statements support.',
                'description' => 'We act as a bridge between your business and financial transparency. We provide internal audits and full documentation support to facilitate statutory audits through our Chartered Accountancy partners. Services include audit readiness (lead schedules, reconciliations, supporting documents), liaison with CA firms, internal audit & risk management (system/operational/compliance audit), management reporting (performance analysis, budgetary control), special purpose audits (due diligence, forensic review, inventory & fixed asset verification), and accounts/financial statement preparation in line with IAS/IFRS including consolidation.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>',
                'features' => [
                    'Statutory audit readiness and document preparation',
                    'Coordination with Chartered Accountants for timely audit',
                    'Internal audit: system, operational and compliance reviews',
                    'Risk management and compliance to BFRS',
                    'Management audit, performance analysis and reporting',
                    'Budgetary control and expenditure monitoring',
                    'Due diligence, forensic review, inventory & fixed asset audit',
                    'Accounts & financial statement preparation (IAS/IFRS) and consolidation',
                ],
                'image_url' => '/images/seed/audit-support-1280.webp',
                'image_width' => 1280,
                'image_height' => 912,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'slug' => 'club-limited-company',
                'title' => 'Club Limited Company Services',
                'short_description' => 'Section 28 registration, annual return, amendments and winding up support.',
                'description' => 'Full support for registering Club Limited Companies as per Section 28 of the Companies Act, 1994, including annual return preparation/submission, amendment support (name/address/management changes), and expert handling of winding up proceedings.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                'features' => [
                    'Registration as per Section 28 (Companies Act, 1994)',
                    'Annual return preparation and submission to RJSC',
                    'Amendments: name, office address, management modifications',
                    'Winding up proceedings management',
                ],
                'image_url' => '/images/seed/club-limited-company-1280.webp',
                'image_width' => 1280,
                'image_height' => 854,
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'slug' => 'trade-organization',
                'title' => 'Trade Organization Services',
                'short_description' => 'Section 29 registration, annual return and organization amendments support.',
                'description' => 'Full support for Trade Organization registration as per Section 29 of the Companies Act, 1994, including annual return preparation/submission to RJSC and legal handling of amendments and management modifications.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 12h.01"/><path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><path d="M22 13a18.15 18.15 0 0 1-20 0"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>',
                'features' => [
                    'Registration as per Section 29 (Companies Act, 1994)',
                    'Annual return preparation and submission to RJSC',
                    'Amendments and organization management modifications',
                ],
                'image_url' => '/images/seed/trade-organization-1280.webp',
                'image_width' => 1280,
                'image_height' => 854,
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }
    }
}

