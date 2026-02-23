<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'slug' => 'rjsc-limited-company',
                'title' => 'RJSC Limited Company Services',
                'short_description' => 'Complete incorporation, changes, charges, annual compliance and winding up support.',
                'description' => 'Professional support for RJSC matters including drafting constitutional documents (MoA/AoA), end-to-end incorporation of private/public limited companies, director changes, share transfer/allotment, conversion from private to public, mortgage & charge creation/registration, annual return preparation and submission, and full winding up & liquidation formalities.',
                'icon_svg' => '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
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
                'image_url' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=600&h=400&fit=crop',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'slug' => 'rjsc-partnership-firm',
                'title' => 'Partnership Firm Services',
                'short_description' => 'Partnership deed drafting, registration, amendments and dissolution support.',
                'description' => 'Complete partnership support including professional partnership deed drafting, RJSC registration, legal handling of amendments (name/address/constitution changes), and managed dissolution with settlement of accounts and formal cancellation of RJSC registration.',
                'icon_svg' => '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                'features' => [
                    'Partnership deed drafting',
                    'Partnership firm registration with RJSC',
                    'Changes in firm name / office address / constitution',
                    'Dissolution management and formal cancellation at RJSC',
                ],
                'image_url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600&h=400&fit=crop',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'slug' => 'foundation-trust-society',
                'title' => 'Foundation, Trust & Society Services',
                'short_description' => 'Formation, deed drafting, and annual compliance for welfare entities.',
                'description' => 'We assist in establishing and maintaining social welfare entities with high legal precision. Services include foundation registration, trust registration and trust deed drafting (private or charitable trusts), and managing annual return filing and regulatory reporting for trusts and foundations.',
                'icon_svg' => '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                'features' => [
                    'Foundation registration support',
                    'Trust registration and trust deed drafting',
                    'Private and charitable trust formation',
                    'Annual return filing & regulatory reporting',
                ],
                'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=400&fit=crop',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'slug' => 'club-limited-company',
                'title' => 'Club Limited Company Services',
                'short_description' => 'Section 28 registration, annual return, amendments and winding up support.',
                'description' => 'Full support for registering Club Limited Companies as per Section 28 of the Companies Act, 1994, including annual return preparation/submission, amendment support (name/address/management changes), and expert handling of winding up proceedings.',
                'icon_svg' => '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                'features' => [
                    'Registration as per Section 28 (Companies Act, 1994)',
                    'Annual return preparation and submission to RJSC',
                    'Amendments: name, office address, management modifications',
                    'Winding up proceedings management',
                ],
                'image_url' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=600&h=400&fit=crop',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'slug' => 'trade-organization',
                'title' => 'Trade Organization Services',
                'short_description' => 'Section 29 registration, annual return and organization amendments support.',
                'description' => 'Full support for Trade Organization registration as per Section 29 of the Companies Act, 1994, including annual return preparation/submission to RJSC and legal handling of amendments and management modifications.',
                'icon_svg' => '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>',
                'features' => [
                    'Registration as per Section 29 (Companies Act, 1994)',
                    'Annual return preparation and submission to RJSC',
                    'Amendments and organization management modifications',
                ],
                'image_url' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=600&h=400&fit=crop',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'slug' => 'income-tax',
                'title' => 'Income Tax Consultancy & Litigation',
                'short_description' => 'Registration, return preparation, corporate tax, planning and full appeal representation.',
                'description' => 'Full-spectrum income tax support for individuals, partnership firms, companies, and trusts under the Income Tax Act, 2023—from e-TIN and compliance to return preparation (all heads of income), asset & liability statement (IT-10B), investment allowance/rebates, corporate withholding tax (TDS/VDS), deferred tax computation, tax planning, and representation through audits, appeals, tribunal and High Court references including ADR.',
                'icon_svg' => '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
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
                'image_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'slug' => 'vat',
                'title' => 'VAT Advisory & Compliance',
                'short_description' => 'BIN registration, monthly returns, VAT books, audits, appeals and refunds.',
                'description' => 'Comprehensive VAT management under the VAT and Supplementary Duty Act, 2012. We support BIN registration/amendment, central vs branch registration strategy, price declaration (Mushak-4.3), monthly VAT return filing (Mushak 9.1) and amended returns (Mushak 9.2), VDS monitoring and Mushak 6.6 certificates, mandatory VAT books (Mushak 6.1/6.2/6.3/6.10), audit & investigation representation, demand notice defense, appeals and ADR, plus VAT refund and exemption consultancy.',
                'icon_svg' => '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
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
                'image_url' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=600&h=400&fit=crop',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'slug' => 'audit-support',
                'title' => 'Audit Support & Financial Assurance',
                'short_description' => 'Internal audit, statutory audit readiness, reporting, and financial statement support.',
                'description' => 'We act as a bridge between your business and financial transparency. We provide internal audits and full documentation support to facilitate statutory audits through our Chartered Accountancy partners. Services include audit readiness (lead schedules, reconciliations, supporting documents), liaison with CA firms, internal audit & risk management (system/operational/compliance audit), management reporting (performance analysis, budgetary control), special purpose audits (due diligence, forensic review, inventory & fixed asset verification), and accounts/financial statement preparation in line with IAS/IFRS including consolidation.',
                'icon_svg' => '<svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>',
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
                'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop',
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }
    }
}

