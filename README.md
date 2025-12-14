# Your Story Community

> **A modern full-stack social storytelling platform** - Share stories, connect with others, and build your community.

[![Backend](https://img.shields.io/badge/Backend-Laravel%2011-FF2D20)]()
[![Frontend](https://img.shields.io/badge/Frontend-React%2019-61DAFB)]()
[![Database](https://img.shields.io/badge/Database-MySQL%208.0-4479A1)]()
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED)]()
[![License](https://img.shields.io/badge/License-MIT-green)]()

**Community Platform:** https://github.com/Bangkah/yourstory-community  
**Company:** [YourStory Portfolio](https://yourstory-portfolio.vercel.app/)  
**Main Product:** [YourStory App](https://yourstory-orcin.vercel.app/)

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Quick Start](#quick-start)
- [Installation](#installation)
- [Documentation](#documentation)
- [Project Structure](#project-structure)
- [API Reference](#api-reference)
- [Development](#development)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

---

## 🌟 Overview

**Your Story Community** is a community platform where users can share their stories, connect with others, and engage through comments, likes, and follows. This is the community hub for [YourStory](https://yourstory-orcin.vercel.app/), built to foster a vibrant storytelling community.

### About YourStory

- **Company Portfolio:** [yourstory-portfolio.vercel.app](https://yourstory-portfolio.vercel.app/)
- **Main Product:** [yourstory-orcin.vercel.app](https://yourstory-orcin.vercel.app/)
- **Community Platform:** This repository (yourstory-community)

### Why Your Story Community?

- 🎨 **Beautiful UI** - Modern, responsive design with Tailwind CSS
- ⚡ **Fast & Smooth** - React 19 with Vite for lightning-fast performance
- 🔐 **Secure** - Token-based authentication with Laravel Sanctum
- 📱 **Mobile-First** - Responsive design that works on all devices
- 🚀 **Production-Ready** - Docker support, comprehensive testing, and documentation
- 🤝 **Community-Driven** - Built to connect storytellers and readers

---

## ✨ Features

### Core Functionality
- ✅ **User Authentication** - Secure login/register with token-based auth
- ✅ **Story Management** - Create, read, update, delete stories with rich text
- ✅ **Comments & Replies** - Nested comments with threading support
- ✅ **Likes System** - Like stories and track engagement
- ✅ **Follow System** - Follow users and build your network
- ✅ **Notifications** - Real-time notifications for interactions
- ✅ **User Profiles** - Customizable profiles with avatars
- ✅ **Search & Filter** - Find stories by title, author, or tags
- ✅ **Admin Panel** - Content moderation and user management

### User Experience
- ✅ **Modern UI** - Gradient designs, smooth animations, intuitive layout
- ✅ **Responsive Design** - Works perfectly on mobile, tablet, and desktop
- ✅ **Dark Mode** - Eye-friendly dark theme support
- ✅ **Loading States** - Visual feedback for all async operations
- ✅ **Error Handling** - User-friendly error messages
- ✅ **Form Validation** - Client and server-side validation

### Technical Features
- ✅ **REST API** - 32+ well-documented endpoints
- ✅ **Role-Based Access** - Admin, Moderator, Member roles
- ✅ **Rate Limiting** - Protection against abuse
- ✅ **Soft Deletes** - Content recovery capability
- ✅ **Event-Driven** - Asynchronous notifications via queue
- ✅ **Database Optimization** - Indexes, eager loading, caching
- ✅ **Comprehensive Tests** - Feature and unit tests
- ✅ **Docker Support** - Easy deployment with containers

---

## 🛠 Tech Stack

### Frontend
| Technology | Version | Purpose |
|------------|---------|---------|
| **React** | 19.x | UI library |
| **TypeScript** | 5.x | Type safety |
| **Inertia.js** | 2.x | Server-side routing |
| **Tailwind CSS** | 3.4.3 | Styling framework |
| **Vite** | 7.2.7 | Build tool |
| **Axios** | 1.x | HTTP client |

### Backend
| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 11.x | PHP framework |
| **PHP** | 8.4 | Programming language |
| **MySQL** | 8.0 | Database |
| **Redis** | Latest | Cache & queue |
| **Sanctum** | 4.x | Authentication |
| **PHPUnit** | 11 | Testing |

### Infrastructure
| Technology | Purpose |
|------------|---------|
| **Docker** | Containerization |
| **Nginx** | Web server |
| **Composer** | PHP dependencies |
| **NPM** | JavaScript dependencies |

---

## 🚀 Quick Start

### Prerequisites
- Docker & Docker Compose
- Git
- Node.js 18+ (if building frontend locally)

### Installation

```bash
# 1. Clone repository
git clone https://github.com/Bangkah/yourstory-community.git
cd yourstory-community

# 2. Start Docker containers
docker-compose up -d

# 3. Install backend dependencies
docker-compose exec app composer install

# 4. Setup environment
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate

# 5. Configure database (update .env in container)
docker-compose exec app sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
docker-compose exec app sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=db/' .env
docker-compose exec app sed -i 's/# DB_PORT=3306/DB_PORT=3306/' .env
docker-compose exec app sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=yourstory_comunity/' .env
docker-compose exec app sed -i 's/# DB_USERNAME=root/DB_USERNAME=root/' .env
docker-compose exec app sed -i 's/# DB_PASSWORD=/DB_PASSWORD=secret/' .env

# 6. Run migrations
docker-compose exec app php artisan migrate --seed

# 7. Install frontend dependencies
npm install

# 8. Build frontend assets
npm run build

# 9. Clear caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear

# Done! 🎉
# Application: http://localhost:8080
# API: http://localhost:8080/api
```

### Test the Application

**Via Web Browser:**
```
Open: http://localhost:8080
- Register a new account
- Login with your credentials
- Create your first story!
```

**Via API:**
```bash
# Register user
curl -X POST http://localhost:8080/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Get all stories
curl http://localhost:8080/api/stories
```

---

## 📖 Documentation

We provide comprehensive documentation for all aspects of the project:

### Main Documentation
- **[README.md](README.md)** - This file, overview and quick start
- **[FRONTEND_DOCUMENTATION.md](FRONTEND_DOCUMENTATION.md)** - Complete frontend guide
- **[BACKEND_DOCUMENTATION.md](BACKEND_DOCUMENTATION.md)** - Complete backend guide
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - API endpoints reference

### Additional Guides
- **[SETUP_GUIDE.md](SETUP_GUIDE.md)** - Detailed installation instructions
- **[TESTING_GUIDE.md](TESTING_GUIDE.md)** - Testing procedures
- **[DATABASE_SCHEMA.md](DATABASE_SCHEMA.md)** - Database structure
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Production deployment guide
- **[REGISTRATION_FIX_SUMMARY.md](REGISTRATION_FIX_SUMMARY.md)** - Recent fixes log

### API Tools
- **[postman_collection.json](postman_collection.json)** - Postman collection
- **[postman_environment.json](postman_environment.json)** - Postman environment
- **[api-test.sh](api-test.sh)** - Bash script for API testing

---

## � Project Structure

```
yourstory-community/
├── app/                          # Backend application code
│   ├── Http/
│   │   ├── Controllers/         # API controllers
│   │   │   └── Api/
│   │   │       ├── Auth/        # Authentication controllers
│   │   │       ├── Admin/       # Admin controllers
│   │   │       └── ...          # Story, Comment, Like controllers
│   │   ├── Middleware/          # Custom middleware
│   │   └── Requests/            # Form request validation
│   ├── Models/                  # Eloquent models
│   ├── Events/                  # Event classes
│   ├── Listeners/               # Event listeners
│   ├── Policies/                # Authorization policies
│   └── Services/                # Business logic services
│
├── resources/                    # Frontend resources
│   ├── js/
│   │   ├── Pages/               # React page components
│   │   ├── Layouts/             # Layout components
│   │   ├── Context/             # React context (auth)
│   │   ├── Services/            # API client service
│   │   └── app.tsx              # React entry point
│   ├── css/
│   │   └── app.css              # Tailwind CSS
│   └── views/
│       └── app.blade.php        # Laravel view template
│
├── routes/
│   ├── api.php                  # API routes (32+ endpoints)
│   ├── web.php                  # Web routes
│   └── console.php              # Artisan commands
│
├── database/
│   ├── migrations/              # Database migrations
│   ├── seeders/                 # Database seeders
│   └── factories/               # Model factories
│
├── tests/
│   ├── Feature/                 # Feature tests
│   └── Unit/                    # Unit tests
│
├── public/
│   └── build/                   # Compiled frontend assets
│
├── docker/                       # Docker configuration
│   └── nginx/                   # Nginx config
│
├── config/                       # Laravel configuration
├── storage/                      # Application storage
└── vendor/                       # PHP dependencies
```

---

## � API Reference

### Authentication Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/auth/register` | Register new user | No |
| POST | `/api/auth/login` | Login user | No |
| POST | `/api/auth/logout` | Logout user | Yes |
| GET | `/api/auth/me` | Get current user | Yes |

### Story Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/stories` | Get all stories | No |
| GET | `/api/stories/{id}` | Get single story | No |
| POST | `/api/stories` | Create story | Yes |
| PUT | `/api/stories/{id}` | Update story | Yes |
| DELETE | `/api/stories/{id}` | Delete story | Yes |

### Comment Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/stories/{id}/comments` | Get story comments | No |
| POST | `/api/stories/{id}/comments` | Create comment | Yes |
| DELETE | `/api/comments/{id}` | Delete comment | Yes |

### Like Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/stories/{id}/like` | Toggle like | Yes |

### Follow Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/users/{id}/follow` | Follow user | Yes |
| DELETE | `/api/users/{id}/unfollow` | Unfollow user | Yes |
| GET | `/api/users/{id}/followers` | Get followers | No |
| GET | `/api/users/{id}/following` | Get following | No |

### Notification Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/notifications` | Get notifications | Yes |
| GET | `/api/notifications/unread-count` | Get unread count | Yes |
| PUT | `/api/notifications/{id}/read` | Mark as read | Yes |

**Complete API documentation:** See [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

**Postman Collection:** Import [postman_collection.json](postman_collection.json)

---

## 💻 Development

### Backend Development

**Run Laravel development server:**
```bash
docker-compose exec app php artisan serve --host=0.0.0.0 --port=8000
```

**Run migrations:**
```bash
docker-compose exec app php artisan migrate
```

**Seed database:**
```bash
docker-compose exec app php artisan db:seed
```

**Clear caches:**
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
```

**Run queue worker:**
```bash
docker-compose exec app php artisan queue:work
```

### Frontend Development

**Run development server with HMR:**
```bash
npm run dev
# Vite dev server at http://localhost:5173
# Hot Module Replacement enabled
```

**Build for production:**
```bash
npm run build
# Output: public/build/
# CSS: ~28.32 KB (gzipped: ~5.12 KB)
# JS: ~382.32 KB (gzipped: ~124.53 KB)
```

**Type checking:**
```bash
npx tsc --noEmit
```

### Code Quality

**PHP Code Style (Pint):**
```bash
docker-compose exec app ./vendor/bin/pint
```

**ESLint (if configured):**
```bash
npm run lint
```

### Database Management

**Access MySQL CLI:**
```bash
docker-compose exec db mysql -u root -psecret yourstory_comunity
```

**Export database:**
```bash
docker-compose exec db mysqldump -u root -psecret yourstory_comunity > backup.sql
```

**Import database:**
```bash
docker-compose exec -T db mysql -u root -psecret yourstory_comunity < backup.sql
```

---

## 🧪 Testing

### Run All Tests
```bash
docker-compose exec app php artisan test
```

### Run Specific Test Suite
```bash
# Feature tests only
docker-compose exec app php artisan test --testsuite=Feature

# Unit tests only
docker-compose exec app php artisan test --testsuite=Unit

# Specific test file
docker-compose exec app php artisan test tests/Feature/AuthTest.php
```

### Test Coverage
```bash
docker-compose exec app php artisan test --coverage
```

### Current Test Status
- **Total Tests:** 31
- **Passing:** 25 (80%)
- **Feature Tests:** Auth, Story, Comment, Like, Follow, Notification
- **Unit Tests:** Model relationships, helpers

**Detailed testing guide:** See [TESTING_GUIDE.md](TESTING_GUIDE.md)

**Total: 32+ endpoints** - All documented with examples

---

---

## 🚀 Deployment

### Production Checklist

**Before deploying to production:**

1. **Environment Configuration**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. **Database Configuration**
   - Use production MySQL credentials
   - Set `DB_CONNECTION=mysql`
   - Configure backup strategy

3. **Caching**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Queue Workers**
   ```bash
   php artisan queue:restart
   # Setup supervisor for queue workers
   ```

5. **Security**
   - Update all secrets (`APP_KEY`, database passwords)
   - Configure CORS properly
   - Set up SSL/TLS certificate
   - Enable rate limiting

6. **Assets**
   ```bash
   npm run build
   php artisan storage:link
   ```

### Docker Production

**Build production image:**
```bash
docker build -t yourstory-community:latest .
```

**Run with docker-compose:**
```bash
docker-compose -f docker-compose.prod.yml up -d
```

**Complete deployment guide:** See [DEPLOYMENT.md](DEPLOYMENT.md)

---

## 🔧 Configuration

### Environment Variables

**Required:**
```env
APP_NAME="Your Story Community"
APP_ENV=local|production
APP_KEY=base64:...
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=yourstory_comunity
DB_USERNAME=root
DB_PASSWORD=secret

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

**Optional:**
```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Docker Services

| Service | Port | Description |
|---------|------|-------------|
| app | 8080 | Laravel application (Nginx) |
| db | 3306 | MySQL database |
| redis | 6379 | Redis cache/queue |

**Access services:**
```bash
# Application
http://localhost:8080

# MySQL (from host)
mysql -h 127.0.0.1 -P 3306 -u root -psecret yourstory_comunity

# Redis (from host)
redis-cli -h 127.0.0.1 -p 6379
```

**Expected Result:**
```
Tests:    25 passed, 4 failed, 2 skipped
Duration: 0.41s
```

### Run Specific Tests
```bash
# Feature tests only
docker-compose exec app php artisan test --testsuite=Feature

# Specific test class
docker-compose exec app php artisan test tests/Feature/StoryTest.php

# Specific test method
docker-compose exec app php artisan test --filter=testStoryCreation
```

### Test Coverage
- **Authentication:** 4/5 tests (80%)
- **Stories:** 5/8 tests (63%)
- **Comments:** 4/4 tests (100%)
- **Likes:** 3/3 tests (100%)
- **Followers:** 6/6 tests (100%)
- **Notifications:** 2/4 tests (50%, 2 skipped)
- **Overall:** 25/31 (80.6%)

See [TESTING_GUIDE.md](./TESTING_GUIDE.md) for complete testing documentation.

---

## 💻 Development

### Making Changes

---

## 🐛 Troubleshooting

### Common Issues

**1. Database Connection Failed**
```bash
# Check database is running
docker-compose ps db

# Verify .env configuration
docker-compose exec app cat .env | grep DB_

# Test connection
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

**2. "Too Many Requests" (429 Error)**
- Throttle limit reached (5 requests/minute for auth endpoints)
- Wait 1 minute or clear rate limit cache
```bash
docker-compose exec app php artisan cache:clear
```

**3. Frontend Assets Not Loading**
```bash
# Rebuild frontend
npm run build

# Clear Laravel cache
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan config:clear
```

**4. Migrations Failing**
```bash
# Check migration status
docker-compose exec app php artisan migrate:status

# Fresh migrations
docker-compose exec app php artisan migrate:fresh --seed
```

**5. Queue Jobs Not Processing**
```bash
# Start queue worker
docker-compose exec app php artisan queue:work

# Check failed jobs
docker-compose exec app php artisan queue:failed
```

**6. Permission Errors (Storage)**
```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

**Complete troubleshooting:** See [BACKEND_DOCUMENTATION.md](BACKEND_DOCUMENTATION.md#troubleshooting)

---

## 🤝 Contributing

We welcome contributions! Here's how you can help:

### Getting Started

1. **Fork the repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/yourstory-community.git
   cd yourstory-community
   ```

2. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make your changes**
   - Follow existing code style
   - Write tests for new features
   - Update documentation

4. **Run tests**
   ```bash
   docker-compose exec app php artisan test
   npm run build
   ```

5. **Commit your changes**
   ```bash
   git add .
   git commit -m "Add: your feature description"
   ```

6. **Push and create PR**
   ```bash
   git push origin feature/your-feature-name
   ```
   Then open a Pull Request on GitHub

### Contribution Guidelines

**Code Style:**
- PHP: Follow PSR-12 standards
- JavaScript/TypeScript: Use ESLint rules
- Run `./vendor/bin/pint` for PHP formatting

**Commit Messages:**
- Use clear, descriptive messages
- Format: `Type: Description`
- Types: `Add`, `Fix`, `Update`, `Remove`, `Refactor`

**Pull Requests:**
- Include description of changes
- Reference related issues
- Ensure all tests pass
- Update documentation if needed

**What to Contribute:**
- 🐛 Bug fixes
- ✨ New features
- 📝 Documentation improvements
- ✅ Test coverage
- 🎨 UI/UX enhancements
- ⚡ Performance optimizations

### Development Setup for Contributors

```bash
# 1. Clone your fork
git clone https://github.com/YOUR_USERNAME/yourstory-community.git

# 2. Add upstream remote
git remote add upstream https://github.com/Bangkah/yourstory-community.git

# 3. Create .env from example
cp .env.example .env

# 4. Start development environment
docker-compose up -d
docker-compose exec app composer install
npm install

# 5. Run migrations
docker-compose exec app php artisan migrate --seed

# 6. Build frontend
npm run dev
```

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

```
MIT License

Copyright (c) 2025 Your Story Community

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 📞 Contact & Support

**Community Repository:** https://github.com/Bangkah/yourstory-community  
**Company Website:** https://yourstory-portfolio.vercel.app/  
**Main Product:** https://yourstory-orcin.vercel.app/  
**Issues:** https://github.com/Bangkah/yourstory-community/issues  
**Discussions:** https://github.com/Bangkah/yourstory-community/discussions

### Need Help?

1. **Check Documentation** - Read the docs first
2. **Search Issues** - Your question might be answered
3. **Open an Issue** - Describe your problem clearly
4. **Join Discussions** - Ask questions, share ideas

---

## 🙏 Acknowledgments

### Part of YourStory Ecosystem

This community platform is part of the YourStory family:
- **[YourStory App](https://yourstory-orcin.vercel.app/)** - Main storytelling platform
- **[YourStory Portfolio](https://yourstory-portfolio.vercel.app/)** - Company website
- **YourStory Community** - This repository (community hub)

### Built with Amazing Technologies

- **[Laravel](https://laravel.com)** - The PHP Framework
- **[React](https://react.dev)** - UI Library
- **[Inertia.js](https://inertiajs.com)** - Modern Monolith
- **[Tailwind CSS](https://tailwindcss.com)** - Utility-first CSS
- **[Vite](https://vitejs.dev)** - Next Generation Frontend Tooling
- **[Docker](https://www.docker.com)** - Containerization Platform

---

## 🗺️ Roadmap

### Version 1.1 (Planned)
- [ ] Real-time notifications with WebSockets
- [ ] Story tags and categories
- [ ] Advanced search with Elasticsearch
- [ ] Story bookmarking
- [ ] User mentions in comments

### Version 1.2 (Planned)
- [ ] Story drafts and scheduling
- [ ] Rich text editor (WYSIWYG)
- [ ] Image uploads for stories
- [ ] User reputation system
- [ ] Content reporting system

### Version 2.0 (Future)
- [ ] Mobile apps (iOS/Android)
- [ ] Story analytics dashboard
- [ ] Social media sharing
- [ ] Email notifications
- [ ] Multi-language support

**See full roadmap:** [ROADMAP.md](ROADMAP.md)

---

<div align="center">

**Made with ❤️ by the YourStory Team**

Part of the [YourStory](https://yourstory-orcin.vercel.app/) ecosystem

⭐ Star us on GitHub if you find this project useful!

[Main Product](https://yourstory-orcin.vercel.app/) · [Company Portfolio](https://yourstory-portfolio.vercel.app/) · [Report Bug](https://github.com/Bangkah/yourstory-community/issues) · [Documentation](https://github.com/Bangkah/yourstory-community#documentation)

</div>
```

**"No such table" error**
```bash
# Run migrations
docker-compose exec app php artisan migrate --seed
```

**Rate limiting too strict**
```bash
# Adjust in routes/api.php
Route::post('/stories', [...])
    ->middleware('throttle:60,1');  # 60 requests per minute
```

**Queue not processing notifications**
```bash
# Start queue worker
docker-compose exec app php artisan queue:work

# Monitor queue
docker-compose logs -f queue
```

See [SETUP_GUIDE.md](./SETUP_GUIDE.md#troubleshooting) for more issues and solutions.

---

## 🔐 Security

### Built-in Security Features
- ✅ **CSRF Protection** - Laravel middleware
- ✅ **SQL Injection Prevention** - Prepared statements via Eloquent
- ✅ **XSS Prevention** - Automatic escaping
- ✅ **Rate Limiting** - Per-action throttling
- ✅ **Authentication** - Sanctum tokens with expiration
- ✅ **Authorization** - Role-based policies
- ✅ **Password Hashing** - bcrypt with salt
- ✅ **CORS Support** - Configurable cross-origin

### Production Recommendations
1. Set `APP_DEBUG=false` in production
2. Use HTTPS for all traffic
3. Rotate API tokens regularly
4. Monitor rate limiting
5. Set up error monitoring (Sentry)
6. Configure proper logging
7. Use environment-specific secrets

---

## 📊 Status & Metrics

| Metric | Value | Status |
|--------|-------|--------|
| **Backend Status** | Production Ready | ✅ |
| **API Endpoints** | 32+ | ✅ |
| **Test Coverage** | 80.6% (25/31) | ✅ |
| **Database Tables** | 9 | ✅ |
| **Seeded Records** | 100+ | ✅ |
| **Documentation** | Complete | ✅ |
| **Error Handling** | Global middleware | ✅ |
| **Rate Limiting** | Per-action | ✅ |
| **Caching** | Redis enabled | ✅ |
| **Queue System** | Redis enabled | ✅ |

---

## 🗺️ Roadmap

### Phase 1: ✅ Complete
- ✅ Core API endpoints
- ✅ Authentication & authorization
- ✅ Database schema
- ✅ Event-driven notifications
- ✅ Comprehensive testing
- ✅ Full documentation

### Phase 2: Frontend Development
- [ ] Next.js/React frontend
- [ ] User authentication UI
- [ ] Story creation & editing
- [ ] Comment system UI
- [ ] Notification system UI
- [ ] Admin dashboard

### Phase 3: Advanced Features
- [ ] Real-time WebSocket updates
- [ ] Firebase Firestore integration
- [ ] Advanced search with Elasticsearch
- [ ] User recommendations engine
- [ ] Email notifications
- [ ] Two-factor authentication
- [ ] Social login (OAuth)

---

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

---

## 👥 Contributing

Contributions are welcome! Please follow the guidelines in [CONTRIBUTING.md](CONTRIBUTING.md) (if available).

---

## 📞 Support

For issues and questions:
1. Check [TESTING_GUIDE.md](./TESTING_GUIDE.md) for API testing help
2. Review [BACKEND_DOCUMENTATION.md](./BACKEND_DOCUMENTATION.md) for comprehensive guide
3. See [SETUP_GUIDE.md](./SETUP_GUIDE.md) for installation/troubleshooting
4. Check logs: `docker-compose logs -f app`

---

## 🎉 Getting Started Checklist

- [ ] Clone repository
- [ ] Run `docker-compose up -d`
- [ ] Run `docker-compose exec app composer install`
- [ ] Run `docker-compose exec app php artisan migrate --seed`
- [ ] Test API with `curl http://localhost:8080/api/stories`
- [ ] Read [BACKEND_DOCUMENTATION.md](./BACKEND_DOCUMENTATION.md)
- [ ] Import [postman_collection.json](./postman_collection.json) into Postman
- [ ] Run `docker-compose exec app php artisan test`
- [ ] Start frontend development

---

**Last Updated:** January 2024
**Version:** 1.0.0
**Status:** 🚀 Production Ready

For detailed information, see the comprehensive documentation files in the project root.
