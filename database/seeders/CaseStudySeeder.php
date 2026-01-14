<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\Service;
use Illuminate\Database\Seeder;

class CaseStudySeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all()->keyBy('slug');

        $caseStudies = [
            [
                'title' => 'Successful Private Company Registration for Tech Startup',
                'slug' => 'successful-private-company-registration-tech-startup',
                'challenge' => 'A group of entrepreneurs wanted to establish a private limited company in Bangladesh to launch their technology startup. They were unfamiliar with the complex registration process, required documentation, and compliance procedures. The founders needed to complete registration quickly to secure funding and begin operations.',
                'solution' => 'Our team provided comprehensive guidance throughout the entire registration process. We helped prepare all necessary documents including Memorandum of Association, Articles of Association, and required forms. We facilitated name clearance, obtained necessary certificates, and ensured compliance with all regulatory requirements. Our experts coordinated with relevant authorities to expedite the process.',
                'outcome' => 'The company was successfully registered within 20 working days, well ahead of the typical timeline. The founders secured their funding and launched operations on schedule. The company is now operating successfully and has expanded its team. Our client expressed satisfaction with our efficient service and professional approach.',
                'client_name' => 'Ahmed Rahman',
                'client_testimonial' => 'MBSC made our company registration process incredibly smooth. Their expertise and attention to detail saved us weeks of time. Highly recommended for anyone looking to start a business in Bangladesh.',
                'service_slug' => 'company-registration',
                'is_published' => true,
            ],
            [
                'title' => 'Streamlined Payroll Management for Manufacturing Company',
                'slug' => 'streamlined-payroll-management-manufacturing-company',
                'challenge' => 'A medium-sized manufacturing company with 150+ employees was struggling with payroll management. They faced challenges with accurate salary calculations, tax deductions, provident fund contributions, and compliance with labor laws. The manual process was time-consuming and prone to errors.',
                'solution' => 'We implemented a comprehensive payroll management system tailored to their needs. Our team set up automated payroll processing, ensured accurate tax calculations, managed provident fund contributions, and maintained compliance with all labor regulations. We provided training to their HR team and established regular reporting mechanisms.',
                'outcome' => 'Payroll processing time was reduced by 70%, eliminating errors and ensuring 100% compliance. The company saved significant administrative costs and improved employee satisfaction. Our client now has a reliable, automated system that handles all payroll requirements efficiently.',
                'client_name' => 'Fatima Khan',
                'client_testimonial' => 'The payroll services provided by MBSC have been exceptional. They handle everything professionally and ensure full compliance. Our team can focus on business growth while they manage our payroll efficiently.',
                'service_slug' => 'payroll-services',
                'is_published' => true,
            ],
            [
                'title' => 'Tax Compliance and VAT Registration Success',
                'slug' => 'tax-compliance-vat-registration-success',
                'challenge' => 'An export trading company needed to register for VAT and ensure full tax compliance. They were facing challenges understanding complex tax regulations, VAT requirements, and filing procedures. The company was at risk of penalties due to non-compliance.',
                'solution' => 'Our tax experts conducted a comprehensive review of their business operations and tax obligations. We completed VAT registration, set up proper accounting systems, and ensured compliance with all tax regulations. We provided ongoing support for monthly VAT returns and annual tax filings, along with regular compliance audits.',
                'outcome' => 'The company successfully obtained VAT registration and achieved full tax compliance. All filings are now completed accurately and on time, eliminating the risk of penalties. The company has saved significant time and resources while maintaining complete compliance with tax regulations.',
                'client_name' => 'Mohammad Hassan',
                'client_testimonial' => 'Their tax and VAT compliance services are outstanding. They helped us navigate complex regulations and saved us significant time and money. The team is knowledgeable, responsive, and always available when we need them.',
                'service_slug' => 'tax-vat-compliance',
                'is_published' => true,
            ],
            [
                'title' => 'Complete Accounting System Implementation',
                'slug' => 'complete-accounting-system-implementation',
                'challenge' => 'A growing service company was using manual bookkeeping methods that were no longer sustainable. They needed a modern accounting system to handle increasing transaction volumes, generate accurate financial reports, and ensure compliance with accounting standards.',
                'solution' => 'We implemented a comprehensive accounting system including proper chart of accounts, automated bookkeeping processes, and financial reporting capabilities. Our team migrated existing data, trained staff on the new system, and established internal controls. We also set up regular review processes to ensure accuracy and compliance.',
                'outcome' => 'The company now has a fully automated accounting system that provides real-time financial insights. Financial reporting time was reduced by 80%, and accuracy improved significantly. The management team can now make informed decisions based on accurate, timely financial data.',
                'client_name' => 'Sara Ahmed',
                'client_testimonial' => 'We\'ve been using their accounting services for over two years now. The quality of work is consistently excellent, and they provide valuable insights that help us make better financial decisions.',
                'service_slug' => 'accounting-services',
                'is_published' => true,
            ],
            [
                'title' => 'Corporate Secretarial Services for Compliance',
                'slug' => 'corporate-secretarial-services-compliance',
                'challenge' => 'A public limited company was struggling to maintain corporate compliance requirements. They needed assistance with board meetings, annual general meetings, filing requirements, and maintaining proper corporate records. Non-compliance was risking penalties and legal issues.',
                'solution' => 'We provided comprehensive secretarial services including preparation of meeting minutes, filing of required documents, maintenance of corporate records, and ensuring compliance with all corporate governance requirements. Our team established a systematic approach to handle all secretarial obligations.',
                'outcome' => 'The company achieved 100% compliance with all corporate requirements. All filings are completed on time, and corporate records are properly maintained. The company has avoided penalties and legal issues while maintaining good corporate governance practices.',
                'client_name' => 'Rashid Ali',
                'client_testimonial' => 'The secretarial services team is professional and thorough. They ensure all our corporate compliance requirements are met on time, every time. It\'s a relief to have such reliable partners.',
                'service_slug' => 'secretarial-services',
                'is_published' => true,
            ],
            [
                'title' => 'Business Licensing and Registration Support',
                'slug' => 'business-licensing-registration-support',
                'challenge' => 'A new business needed multiple licenses and registrations including trade license, TIN certificate, IRC, and ERC. The founders were overwhelmed by the complex requirements and lengthy processes. They needed to start operations quickly but were delayed by licensing issues.',
                'solution' => 'Our team provided end-to-end support for all licensing requirements. We prepared all necessary documents, coordinated with relevant authorities, and expedited the application processes. We ensured compliance with all requirements and provided guidance throughout each step.',
                'outcome' => 'All required licenses and registrations were obtained within the expected timeframe. The business was able to start operations without delays. The client saved significant time and avoided common pitfalls in the licensing process.',
                'client_name' => 'Nadia Islam',
                'client_testimonial' => 'As a startup, we needed help with licensing and compliance. MBSC guided us through every step, making what seemed complicated very straightforward. Their support has been invaluable.',
                'service_slug' => 'licensing',
                'is_published' => true,
            ],
            [
                'title' => 'Actuarial Valuation for Employee Benefits',
                'slug' => 'actuarial-valuation-employee-benefits',
                'challenge' => 'A large corporation needed actuarial valuation services for their employee provident fund and gratuity fund. They required accurate valuations to ensure proper fund management and compliance with regulatory requirements. The company lacked in-house expertise for such specialized valuations.',
                'solution' => 'Our actuarial experts conducted comprehensive valuations of both provident and gratuity funds. We analyzed fund assets, projected liabilities, and provided detailed valuation reports. We also recommended strategies for optimal fund management and ensured compliance with all regulatory requirements.',
                'outcome' => 'The company received accurate actuarial valuations that enabled proper fund management. The valuations helped the company make informed decisions about fund contributions and investment strategies. Regulatory compliance was maintained, and the company gained confidence in their employee benefit programs.',
                'client_name' => 'Karim Uddin',
                'client_testimonial' => 'The actuarial valuation services provided were comprehensive and accurate. Their expertise helped us properly structure our employee benefits program. Excellent service delivery.',
                'service_slug' => 'actuarial-valuation',
                'is_published' => true,
            ],
            [
                'title' => 'Comprehensive Financial Audit Services',
                'slug' => 'comprehensive-financial-audit-services',
                'challenge' => 'A mid-sized company required annual financial audit services. They needed thorough audits to ensure accuracy of financial statements, compliance with accounting standards, and identification of areas for improvement. Previous audits had identified several compliance issues.',
                'solution' => 'Our audit team conducted a comprehensive financial audit following international auditing standards. We reviewed all financial records, tested internal controls, verified transactions, and prepared detailed audit reports. We provided recommendations for improving financial processes and ensuring better compliance.',
                'outcome' => 'The audit was completed successfully with all financial statements certified as accurate. The company addressed all identified issues and improved their financial processes. The management gained confidence in their financial reporting and compliance practices.',
                'client_name' => 'Laila Chowdhury',
                'client_testimonial' => 'Their auditing services are thorough and professional. The audit reports are detailed and provide clear recommendations for improvement. We trust them completely with our financial audits.',
                'service_slug' => 'auditing-services',
                'is_published' => true,
            ],
        ];

        foreach ($caseStudies as $caseStudyData) {
            $serviceSlug = $caseStudyData['service_slug'] ?? null;
            unset($caseStudyData['service_slug']);

            $service = $serviceSlug && isset($services[$serviceSlug]) 
                ? $services[$serviceSlug] 
                : null;

            CaseStudy::firstOrCreate(
                ['slug' => $caseStudyData['slug']],
                [
                    ...$caseStudyData,
                    'service_id' => $service?->id,
                ]
            );
        }
    }
}
