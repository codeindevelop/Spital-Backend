# Spital CMS

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![GitHub Issues](https://img.shields.io/github/issues/codeindevelop/spital-cms)](https://github.com/codeindevelop/spital-cms/issues)
[![GitHub Stars](https://img.shields.io/github/stars/codeindevelop/spital-cms)](https://github.com/codeindevelop/spital-cms/stargazers)

**Spital CMS** is a comprehensive, open-source content management system designed for centralized management of websites and business operations. Developed by **Abreall** ([abreall.com](https://abreall.com)), the project is led by **Hadi Mousavi** ([haddi.ir](https://haddi.ir) | [GitHub](https://github.com/codeindevelop)). Spital CMS provides a unified platform to manage articles, e-commerce, education, CRM, ERP, accounting, payroll, and social media analytics.

> **Note**: Spital CMS is actively under development and has not yet reached its final stable version. We warmly welcome contributions from developers worldwide to help shape this project!

## About Spital CMS

Spital CMS is a powerful and flexible content management system built with **Laravel**, optimized for delivering robust and scalable RESTful APIs. It enables businesses and developers to manage all aspects of their digital presence and operations through a single, centralized platform. Designed as a backend solution, Spital CMS seamlessly integrates with various frontends (e.g., React, Vue.js, or mobile apps) via its API-driven architecture.

### Key Features

- **Article Management**: Create, edit, and publish articles with advanced categorization, comments, and SEO optimization.
- **Online Store**: Manage products, store settings (e.g., shipping, taxes, coupons), and shopping carts.
- **Education System**: Sell educational packages with course management and multimedia content support.
- **CRM (Customer Relationship Management)**: Tools for customer management, lead tracking, and marketing automation.
- **ERP (Enterprise Resource Planning)**: Streamline resource management, supply chain, and internal processes.
- **Online Accounting**: Handle transactions, invoices, and financial reporting.
- **Payroll Management**: Automate employee salary calculations and payment processing.
- **Social Media Analytics**: Analyze social media performance to optimize marketing strategies.
- **Open Source**: Fully accessible source code for customization and collaboration.
- **API-Driven**: Comprehensive APIs for interacting with all system components.

## Project Status

Spital CMS is under active development and is not yet complete. We are continuously adding new features, improving performance, and fixing bugs. Contributions are highly encouraged to help us achieve a stable and feature-rich release.

## Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- Laravel >= 10.x
- MySQL/PostgreSQL
- Node.js and NPM (for frontend, if needed)
- Git

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/Spital-CMS/spital-backend.git
   cd spital-cms
   ```

2. Install dependencies:

    ```bash
    composer install
    npm install
    ```
   
3.Set up the environment file:

     
    cp .env.example .env

Configure the database and other settings in .env:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spital_cms
DB_USERNAME=root
DB_PASSWORD=
```


### API Documentation
Spital CMS provides RESTFULL APIs for all its features. To explore and test the APIs, you can use the Postman collection included in the project:

Copy the SpitalCMS.postman_collection.json file from the /postman directory.
Import it into Postman (File > Import).
Configure environment variables (e.g., base_url, auth_token) in Postman.
The collection includes APIs for managing articles, store settings, education, CRM, and more.


```
spital-cms/
├── Modules/
│   ├── Blog/                # Article and blog content management
│   ├── Settings/            # General system and store settings
│   ├── Eshop/               # Online store module
│   ├── Education/           # Education and course management
│   ├── CRM/                 # Customer relationship management
│   ├── ERP/                 # Enterprise resource planning
│   ├── Accounting/          # Online accounting system
│   ├── Payroll/             # Payroll and employee salary management
│   └── SocialAnalytics/     # Social media performance analytics
├── postman/                 # Postman collection for API testing
├── app/                     # Core Laravel code
├── config/                  # Project configurations
├── database/                # Migrations and seeders
└── routes/                  # API and web routes
```


### Technologies

- Backend: Laravel 10.x, PHP 8.1
- Database: MySQL/PostgreSQL
- Authentication: Laravel Sanctum
- Permissions: Spatie Laravel Permission
- Activity Logging: Spatie Laravel Activity Log
- Modular Structure: Nwidart Laravel Modules
- API Documentation: Postman Collection
- Open Source: Licensed under MIT
- 
### License
Spital CMS is released under the MIT License. You are free to use, modify, and distribute this software for both commercial and non-commercial purposes, provided the license terms are followed.

### Contact
For inquiries, support, or collaboration, please reach out:

- Project Lead: Hadi Mousavi
- Website: haddi.ir
- GitHub: github.com/codeindevelop
- Email: ceo@abreall.com
- Development Company: Abreall
- Website: abreall.com
- To report bugs or suggest features, please create an Issue on GitHub.

#### Acknowledgments
We extend our gratitude to all developers, contributors, and users who support the growth of Spital CMS. Our mission is to build a robust and user-friendly CMS that meets the needs of modern businesses.


