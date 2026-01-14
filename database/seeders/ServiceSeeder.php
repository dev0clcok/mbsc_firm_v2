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
                'title' => 'Company Registration',
                'slug' => 'company-registration',
                'description' => 'Expert company registration services in Bangladesh. We help you establish your business legally and efficiently.',
                'icon' => 'building',
                'sort_order' => 1,
                'children' => [
                    ['title' => 'Private Company', 'slug' => 'private-company', 'description' => 'Register a private limited company in Bangladesh'],
                    ['title' => 'Public Company', 'slug' => 'public-company', 'description' => 'Register a public limited company'],
                    ['title' => 'Branch Office', 'slug' => 'branch-office', 'description' => 'Set up a branch office in Bangladesh'],
                    ['title' => 'Joint Venture', 'slug' => 'joint-venture', 'description' => 'Establish a joint venture company'],
                    ['title' => 'Special Purpose Vehicle (SPV)', 'slug' => 'spv', 'description' => 'Create a special purpose vehicle for specific projects'],
                    ['title' => 'Liaison/Representative Office', 'slug' => 'liaison-office', 'description' => 'Set up a liaison or representative office'],
                ],
            ],
            [
                'title' => 'Payroll Services',
                'slug' => 'payroll-services',
                'description' => 'Comprehensive payroll management solutions for your business.',
                'icon' => 'users',
                'sort_order' => 2,
                'children' => [
                    ['title' => 'Payroll Outsourcing', 'slug' => 'payroll-outsourcing', 'description' => 'Outsource your payroll processing'],
                    ['title' => 'Payroll Tax', 'slug' => 'payroll-tax', 'description' => 'Payroll tax compliance and filing'],
                    ['title' => 'HR Outsourcing', 'slug' => 'hr-outsourcing', 'description' => 'Complete HR management services'],
                    ['title' => 'EOR - Employer of Record', 'slug' => 'eor', 'description' => 'Employer of Record services'],
                    ['title' => 'PEO - Professional Employer Organization', 'slug' => 'peo', 'description' => 'Professional Employer Organization services'],
                ],
            ],
            [
                'title' => 'Accounting Services',
                'slug' => 'accounting-services',
                'description' => 'Expert accounting services tailored to your business needs.',
                'icon' => 'calculator',
                'sort_order' => 3,
                'children' => [
                    ['title' => 'Bookkeeping', 'slug' => 'bookkeeping', 'description' => 'Professional bookkeeping services'],
                    ['title' => 'Accounting Manual', 'slug' => 'accounting-manual', 'description' => 'Create comprehensive accounting manuals'],
                    ['title' => 'Operation Manual', 'slug' => 'operation-manual', 'description' => 'Develop operation manuals'],
                    ['title' => 'Liquidation in Bangladesh', 'slug' => 'liquidation', 'description' => 'Company liquidation services'],
                    ['title' => 'BPO', 'slug' => 'bpo', 'description' => 'Business Process Outsourcing'],
                    ['title' => 'CPA Services', 'slug' => 'cpa-services', 'description' => 'Certified Public Accountant services'],
                    ['title' => 'Accounting Software', 'slug' => 'accounting-software', 'description' => 'Accounting software implementation'],
                ],
            ],
            [
                'title' => 'Tax and VAT Compliance',
                'slug' => 'tax-vat-compliance',
                'description' => 'Ensure full compliance with tax and VAT regulations in Bangladesh.',
                'icon' => 'file-text',
                'sort_order' => 4,
                'children' => [
                    ['title' => 'Tax Advisory Services', 'slug' => 'tax-advisory', 'description' => 'Expert tax advisory and planning'],
                    ['title' => 'Tax Litigation Services', 'slug' => 'tax-litigation', 'description' => 'Tax dispute resolution and litigation'],
                    ['title' => 'VAT Compliance Services', 'slug' => 'vat-compliance', 'description' => 'VAT registration and compliance'],
                ],
            ],
            [
                'title' => 'Actuarial Valuation Services',
                'slug' => 'actuarial-valuation',
                'description' => 'Professional actuarial valuation for pension plans and employee benefits.',
                'icon' => 'trending-up',
                'sort_order' => 5,
            ],
            [
                'title' => 'Secretarial Services',
                'slug' => 'secretarial-services',
                'description' => 'Comprehensive corporate secretarial services to maintain compliance.',
                'icon' => 'briefcase',
                'sort_order' => 6,
            ],
            [
                'title' => 'Licensing',
                'slug' => 'licensing',
                'description' => 'Assistance with all types of business licenses and permits.',
                'icon' => 'file-check',
                'sort_order' => 7,
                'children' => [
                    ['title' => 'Trade License', 'slug' => 'trade-license', 'description' => 'Obtain trade licenses'],
                    ['title' => 'TIN Certificate', 'slug' => 'tin-certificate', 'description' => 'Tax Identification Number certificate'],
                    ['title' => 'IRC', 'slug' => 'irc', 'description' => 'Import Registration Certificate'],
                    ['title' => 'ERC', 'slug' => 'erc', 'description' => 'Export Registration Certificate'],
                ],
            ],
            [
                'title' => 'Attestation & Asset Valuation',
                'slug' => 'attestation-asset-valuation',
                'description' => 'Document attestation and professional asset valuation services.',
                'icon' => 'shield-check',
                'sort_order' => 8,
                'children' => [
                    ['title' => 'Attestation Services', 'slug' => 'attestation', 'description' => 'Document attestation and notarization'],
                    ['title' => 'Asset Valuation for Visa Purposes', 'slug' => 'asset-valuation-visa', 'description' => 'Asset valuation for visa applications'],
                ],
            ],
            [
                'title' => 'Employee Benefits',
                'slug' => 'employee-benefits',
                'description' => 'Comprehensive employee benefits solutions including provident and gratuity funds.',
                'icon' => 'heart',
                'sort_order' => 9,
                'children' => [
                    ['title' => 'Provident Funds', 'slug' => 'provident-funds', 'description' => 'Provident fund management'],
                    ['title' => 'Gratuity Funds', 'slug' => 'gratuity-funds', 'description' => 'Gratuity fund administration'],
                ],
            ],
            [
                'title' => 'Auditing Services',
                'slug' => 'auditing-services',
                'description' => 'Professional auditing services to ensure financial transparency and compliance.',
                'icon' => 'clipboard-check',
                'sort_order' => 10,
            ],
        ];

        foreach ($services as $serviceData) {
            $children = $serviceData['children'] ?? [];
            unset($serviceData['children']);

            $service = Service::firstOrCreate(
                ['slug' => $serviceData['slug']],
                $serviceData
            );

            foreach ($children as $child) {
                Service::firstOrCreate(
                    ['slug' => $child['slug']],
                    [
                        ...$child,
                        'parent_id' => $service->id,
                        'is_active' => true,
                        'sort_order' => 0,
                    ]
                );
            }
        }
    }
}
