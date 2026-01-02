# RTFM.guide

> **"You should have RTFM... but we did it for you."**

A humorous yet genuinely helpful community-driven platform for web developers and system administrators. RTFM.guide playfully scolds users for not reading the manual while simultaneously providing clear, actionable guides for common technical problems.

[![Tests](https://github.com/Evie-Software/rtfm.guide/actions/workflows/tests.yml/badge.svg)](https://github.com/Evie-Software/rtfm.guide/actions/workflows/tests.yml)
[![Lint](https://github.com/Evie-Software/rtfm.guide/actions/workflows/lint.yml/badge.svg)](https://github.com/Evie-Software/rtfm.guide/actions/workflows/lint.yml)

## ✨ Features

### Core Features
- 📚 **Comprehensive Guide System** - Hierarchical categories with rich markdown content, syntax highlighting, and code snippets
- 🔍 **Lightning-Fast Search** - Powered by Meilisearch with typo tolerance, instant results, and faceted filtering
- 💬 **Interactive Comments** - Threaded discussions with reactions (Helpful, Saved Me, Outdated, Needs Update)
- 🔖 **Save & Bookmark** - Personal collection of favorite guides
- 📝 **Community Submissions** - OAuth users can submit and edit guides with approval workflow
- 🎨 **Custom Sharing** - Generate shareable links with customizable messages
- 🔄 **Moderation System** - Review queue for guide submissions and edits

### User Features
- 🔐 **OAuth Authentication** - GitHub, Google, and GitLab integration via Laravel Fortify
- 👤 **User Profiles** - Track contributions, saved guides, and activity
- 🔔 **Notifications** - Stay updated on comment replies and guide updates
- 🎯 **Personalized Dashboard** - Quick access to your guides and saved content
- ⚙️ **Preferences** - Customizable theme (light/dark) and content mode (safe/NSFW)

### Technical Highlights
- 🚀 **Modern Stack** - Laravel 12, Inertia.js, Vue 3, Tailwind CSS v4
- 📱 **Responsive Design** - Mobile-first, fully responsive interface
- 🎨 **Beautiful UI** - Clean, modern design with smooth animations
- 🔒 **Secure** - Laravel Sanctum for API authentication, Laravel Fortify for OAuth
- 📊 **RESTful API** - Comprehensive API for future mobile apps

## 🛠 Tech Stack

### Backend
- **Framework**: Laravel 12
- **Authentication**: Laravel Fortify (OAuth), Laravel Sanctum (API)
- **Search**: Meilisearch + Laravel Scout
- **Database**: MySQL/PostgreSQL (SQLite for development)
- **Queue**: Redis/Database
- **Storage**: Local/S3

### Frontend
- **Framework**: Inertia.js + Vue 3
- **Styling**: Tailwind CSS v4
- **Components**: Headless UI, Heroicons
- **Build Tool**: Vite
- **State Management**: Pinia

### Development
- **Testing**: Pest 4 (Unit, Feature, Browser)
- **Code Quality**: Laravel Pint
- **Package Manager**: Composer (PHP), npm (JavaScript)

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL 8.0+ or PostgreSQL 13+ (SQLite works for development)
- Redis (optional, for queue and cache)
- Meilisearch (optional, for search functionality)

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/Evie-Software/rtfm.guide.git
cd rtfm.guide
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create database file (if using SQLite)
touch database/database.sqlite
```

### 4. Configure environment

Edit `.env` and configure your database and other settings:

```env
APP_NAME="RTFM.guide"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# Or for MySQL/PostgreSQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=rtfm_guide
# DB_USERNAME=root
# DB_PASSWORD=

# Optional: Configure OAuth providers
# GITHUB_CLIENT_ID=your_github_client_id
# GITHUB_CLIENT_SECRET=your_github_client_secret
# GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback

# Optional: Configure Meilisearch
# MEILISEARCH_HOST=http://127.0.0.1:7700
# MEILISEARCH_KEY=your_master_key
```

### 5. Run migrations and seed the database

```bash
php artisan migrate --seed
```

### 6. Build frontend assets

```bash
npm run build
# Or for development with hot reload:
npm run dev
```

### 7. Start the application

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 🔧 Development

### Quick Start

The easiest way to start all services for development:

```bash
composer dev
```

This command starts:
- Laravel development server (port 8000)
- Queue worker
- Log viewer (Laravel Pail)
- Vite development server with HMR

### Individual Commands

```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (separate terminal)
npm run dev

# Run queue worker (if using queues)
php artisan queue:work

# Watch logs
php artisan pail
```

### Code Quality

```bash
# Run Laravel Pint (code style fixer)
./vendor/bin/pint

# Run tests
php artisan test

# Run specific test suites
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
php artisan test --testsuite=Browser

# Run tests with coverage
php artisan test --coverage
```

## 🧪 Testing

The project uses Pest 4 for testing with three test suites:

- **Unit Tests**: Test individual classes and methods
- **Feature Tests**: Test HTTP endpoints and workflows
- **Browser Tests**: End-to-end testing with Playwright

```bash
# Run all tests
php artisan test

# Run with coverage report
php artisan test --coverage --min=80

# Run specific test file
php artisan test tests/Feature/GuideTest.php

# Run tests in parallel
php artisan test --parallel
```

## 📁 Project Structure

```
.
├── app/
│   ├── Actions/          # Reusable action classes
│   ├── Console/          # Artisan commands
│   ├── Enums/           # PHP enumerations
│   ├── Http/
│   │   ├── Controllers/ # Request handlers
│   │   └── Middleware/  # HTTP middleware
│   ├── Jobs/            # Queued jobs
│   ├── Models/          # Eloquent models
│   ├── Notifications/   # User notifications
│   ├── Policies/        # Authorization policies
│   ├── Providers/       # Service providers
│   └── Services/        # Business logic services
├── bootstrap/           # Framework bootstrap files
├── config/              # Configuration files
├── database/
│   ├── factories/       # Model factories
│   ├── migrations/      # Database migrations
│   └── seeders/         # Database seeders
├── public/              # Public assets
├── resources/
│   ├── css/            # Stylesheets
│   ├── js/
│   │   ├── Components/ # Vue components
│   │   ├── Layouts/    # Page layouts
│   │   ├── Pages/      # Inertia pages
│   │   └── app.js      # Main JS entry point
│   ├── lang/           # Localization files
│   └── views/          # Blade templates
├── routes/
│   ├── api.php         # API routes
│   ├── console.php     # Console routes
│   └── web.php         # Web routes
├── storage/            # Application storage
├── tests/
│   ├── Feature/        # Feature tests
│   ├── Unit/           # Unit tests
│   └── Pest.php        # Pest configuration
└── vendor/             # Composer dependencies
```

## 🗂 Key Models

- **User** - Users with OAuth authentication, preferences, and reputation
- **Guide** - Technical guides with markdown content, categories, and metadata
- **Category** - Hierarchical category system for organizing guides
- **Comment** - Threaded comments on guides
- **Reaction** - User reactions to guides (helpful, saved me, outdated, needs update)
- **SavedGuide** - User's bookmarked guides
- **GuideRevision** - Version history and moderation queue
- **RtfmMessage** - Pool of humorous "RTFM" messages
- **ShareLink** - Custom shareable links with tracking
- **ContentFlag** - User-reported content for moderation
- **Setting** - Application-wide settings

## 🔌 API

The application provides a RESTful API for future mobile applications and integrations. Authentication is handled via Laravel Sanctum.

### Public Endpoints

```
GET  /api/v1/guides           - List all guides
GET  /api/v1/guides/{slug}    - Get guide details
GET  /api/v1/categories       - List categories
GET  /api/v1/search?q={query} - Search guides
GET  /api/v1/trending         - Trending guides
```

### Authenticated Endpoints (requires OAuth token)

```
POST   /api/v1/guides                    - Create guide
PUT    /api/v1/guides/{id}               - Update guide
DELETE /api/v1/guides/{id}               - Delete guide
POST   /api/v1/guides/{id}/comments      - Add comment
POST   /api/v1/guides/{id}/reactions     - React to guide
GET    /api/v1/user/saved                - Get saved guides
POST   /api/v1/user/saved/{guide_id}     - Save guide
DELETE /api/v1/user/saved/{guide_id}     - Unsave guide
GET    /api/v1/user/profile              - Get user profile
PUT    /api/v1/user/profile              - Update profile
GET    /api/v1/notifications             - Get notifications
```

See the [API documentation](docs/) for more details.

## 🔐 Authentication

RTFM.guide uses OAuth 2.0 for authentication via Laravel Fortify, supporting:

- **GitHub** (recommended for developers)
- **Google** 
- **GitLab**

To configure OAuth providers:

1. Create OAuth applications with your providers
2. Add credentials to `.env`:
   ```env
   GITHUB_CLIENT_ID=your_client_id
   GITHUB_CLIENT_SECRET=your_client_secret
   GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
   ```
3. Configure callback URLs in your OAuth provider settings

## 🔍 Search Configuration

RTFM.guide uses Meilisearch for lightning-fast search functionality.

### Installing Meilisearch

```bash
# macOS
brew install meilisearch

# Linux
curl -L https://install.meilisearch.com | sh

# Or use Docker
docker run -p 7700:7700 -v $(pwd)/data.ms:/data.ms getmeili/meilisearch
```

### Configuring Search

```bash
# Update .env
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=your_master_key

# Import existing data
php artisan scout:import "App\Models\Guide"
```

The search engine indexes:
- Guide titles
- Guide content
- Categories
- Tags
- Code snippets

Features:
- Typo tolerance (up to 2 typos)
- Instant results (sub-50ms)
- Faceted filtering (category, difficulty, OS)
- Highlighted search terms
- Synonym support

## 🚢 Deployment

### Building for Production

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader
npm install

# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force
```

### Environment Configuration

Ensure your production `.env` includes:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://rtfm.guide

# Use proper database
DB_CONNECTION=mysql

# Configure queue
QUEUE_CONNECTION=redis

# Configure cache
CACHE_STORE=redis

# Configure session
SESSION_DRIVER=redis

# Configure mail
MAIL_MAILER=smtp

# Configure search
SCOUT_DRIVER=meilisearch
```

### Web Server Configuration

See `DEPLOYMENT.md` for detailed deployment instructions including:
- Nginx/Apache configuration
- SSL setup
- Process management (Supervisor)
- Queue workers
- Scheduled tasks

## 🤝 Contributing

We welcome contributions from the community! Here's how you can help:

### Guide Submissions

1. Sign in with GitHub/Google/GitLab
2. Click "Submit Guide" in the dashboard
3. Write your guide in markdown
4. Submit for review

### Code Contributions

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests (`php artisan test`)
5. Run code style fixer (`./vendor/bin/pint`)
6. Commit your changes (`git commit -m 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

### Contribution Guidelines

- Follow PSR-12 coding standards (enforced by Pint)
- Write tests for new features
- Update documentation as needed
- Keep commits atomic and well-described
- Ensure all tests pass before submitting PR

## 📝 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com/)
- UI components from [Headless UI](https://headlessui.dev/)
- Icons from [Heroicons](https://heroicons.com/)
- Search powered by [Meilisearch](https://www.meilisearch.com/)
- Testing with [Pest](https://pestphp.com/)

## 📞 Support

- 📖 [Documentation](docs/)
- 🐛 [Issue Tracker](https://github.com/Evie-Software/rtfm.guide/issues)
- 💬 [Discussions](https://github.com/Evie-Software/rtfm.guide/discussions)

---

**Made with ❤️ by the developer community, for the developer community.**

*Remember: RTFM, but if you didn't, we've got you covered.* 😉
