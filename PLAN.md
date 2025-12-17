# RTFM.guide - Project Plan

## Project Overview

**Domain**: rtfm.guide
**Tagline**: "You should have RTFM... but we did it for you."

A humorous yet genuinely helpful guide repository for web developers and system administrators. The site playfully scolds users for not reading the manual while simultaneously providing clear, actionable guides.

## Vision

Create an interactive community-driven platform where developers can quickly find solutions to common problems, submit their own guides, and engage with content in creative ways. The site balances humor with utility, making technical documentation approachable and even entertaining.

## Technical Stack (TALL + OAuth)

- **Framework**: Laravel 12
- **Frontend**: Livewire 3 + Alpine.js
- **Styling**: Tailwind CSS v4 + Flux UI (free)
- **Authentication**: OAuth 2.0 (GitHub, Google, GitLab)
- **API**: RESTful API with Laravel Sanctum (API-first for future NativePHP app)
- **Search**: Meilisearch + Laravel Scout
- **Feature Flags**: Laravel Pennant (free vs paid tiers)
- **Testing**: Pest 4 (Unit, Feature, Browser)
- **Code Quality**: Laravel Pint
- **Database**: MySQL/PostgreSQL
- **Queue**: Redis (for async operations, newsletters)
- **Storage**: Local/S3 for user avatars and custom share images
- **Internationalization**: Laravel's built-in i18n system

## Core Features

### 1. Guide System

#### Guide Structure
- **Hierarchical Categories**: `/[category]/[subcategory]/[guide-slug]`
  - Example: `/server/nginx/how-to-restart-nginx`
  - Example: `/laravel/deployment/zero-downtime-deployment`
- **Rich Content**: Markdown-based with syntax highlighting
- **Metadata**: Difficulty level, estimated time, last updated, author
- **Related Guides**: Automatic suggestions based on category/tags

#### Guide Display
- **RTFM Header**: Humorous, randomly selected "you should have RTFM" message
- **Quick Answer**: TL;DR section at the top
- **Step-by-Step Instructions**: Clear, numbered steps
- **Code Snippets**: Copy-to-clipboard functionality
- **Platform Variants**: OS-specific instructions (Linux, macOS, Windows)
- **Prerequisites**: What you need before starting
- **Troubleshooting**: Common issues and solutions

### 2. Interactive Features

#### Comments System (Livewire)
- **Threaded Comments**: Reply to specific comments
- **Reactions**: Helpful, Saved Me, Outdated, Needs Update
- **Code Sharing**: Inline code snippets in comments
- **OAuth Required**: Must be logged in to comment
- **Moderation**: Flag inappropriate content
- **Notifications**: Get notified of replies

#### Guide Submissions
- **Public Submission**: OAuth users can submit new guides
- **Markdown Editor**: Live preview with syntax highlighting
- **Category Selection**: Choose from existing or suggest new
- **Draft System**: Save work in progress
- **Review Queue**: Admin approval before publishing
- **Edit Suggestions**: Community can suggest edits to existing guides
- **Contributor Recognition**: Author bylines and contributor count

#### Custom Sharing
- **Dynamic Share Images**: Generate custom OG images with:
  - Guide title
  - Selected "RTFM" variant message
  - Color scheme customization
  - User's avatar (optional)
- **Social Templates**: Optimized for Twitter, LinkedIn, Discord
- **Share Statistics**: Track shares per guide
- **Embed Code**: Widgets for embedding guides elsewhere

### 3. OAuth Authentication

#### Supported Providers
- GitHub (primary - dev audience)
- Google (secondary)
- GitLab (dev audience)

#### User Features
- **Profile**: Avatar, bio, links
- **Contributions**: List of submitted/edited guides
- **Saved Guides**: Bookmark functionality
- **Custom "RTFM" Messages**: Users can submit their own humorous variants
- **Reputation System**: Points for contributions, helpful comments

### 4. Search & Discovery

#### Search Engine (Meilisearch + Scout)
- **Lightning-Fast Full-Text Search**: Sub-50ms response times across guide titles, content, code snippets
- **Typo Tolerance**: Automatic handling of spelling mistakes (up to 2 typos)
- **Faceted Filtering**: Category, difficulty, OS, language/framework with counts
- **Instant Search**: Real-time results as you type (Livewire integration)
- **Relevance Ranking**: Smart sorting based on multiple factors
- **Highlighted Results**: Search terms highlighted in results
- **Synonyms Support**: "restart" finds "reboot", "nginx" finds "engine-x"
- **Stop Words**: Ignore common words for better relevance
- **Recent Searches**: Personal search history (stored in session/DB)

#### Discovery Features
- **Trending Guides**: Most viewed this week
- **New Guides**: Recently published
- **Community Picks**: Highly rated by users
- **Random Guide**: "Feeling lucky" feature
- **Tag Cloud**: Visual tag exploration

### 5. API (RESTful with Sanctum)

#### Public Endpoints (No Auth)
```
GET /api/v1/guides
GET /api/v1/guides/{slug}
GET /api/v1/categories
GET /api/v1/search?q={query}
GET /api/v1/trending
```

#### Authenticated Endpoints (OAuth Token)
```
POST /api/v1/guides
PUT /api/v1/guides/{id}
DELETE /api/v1/guides/{id}
POST /api/v1/guides/{id}/comments
POST /api/v1/guides/{id}/reactions
POST /api/v1/guides/{id}/flag
GET /api/v1/user/saved
POST /api/v1/user/saved/{guide_id}
DELETE /api/v1/user/saved/{guide_id}
GET /api/v1/user/badges
GET /api/v1/user/profile
PUT /api/v1/user/profile
GET /api/v1/user/notifications
```

#### Version & Translation Endpoints
```
GET /api/v1/guides/{slug}/versions
GET /api/v1/guides/{slug}/versions/{version}
GET /api/v1/guides/{slug}/translations/{locale}
POST /api/v1/guides/{id}/translations (authenticated)
```

#### Team Endpoints (Pennant-gated)
```
GET /api/v1/teams
POST /api/v1/teams (paid tier)
GET /api/v1/teams/{id}
PUT /api/v1/teams/{id}
DELETE /api/v1/teams/{id}
POST /api/v1/teams/{id}/members/invite
GET /api/v1/teams/{id}/guides (private guides)
```

#### Template Endpoints
```
GET /api/v1/templates
GET /api/v1/templates/{slug}
```

#### Newsletter Endpoints
```
POST /api/v1/newsletter/subscribe
PUT /api/v1/newsletter/preferences
DELETE /api/v1/newsletter/unsubscribe
```

#### Webhook Endpoints
```
POST /api/v1/webhooks/github (for GitHub Actions integration)
```

## Database Schema

### Users
```
id, oauth_provider, oauth_id, name, email, avatar, bio,
github_username, gitlab_username, reputation_points,
trust_level (enum: new, member, trusted, moderator, admin),
preferred_locale (enum: en, es, fr, de, ja, etc.),
newsletter_subscribed (boolean), created_at, updated_at
```

### Guides
```
id, user_id, team_id (nullable), slug, title, tldr, content (markdown),
category_id, difficulty (enum: beginner, intermediate, advanced),
estimated_minutes, os_tags (json), status (enum: draft, pending, published),
visibility (enum: public, private), template_id (nullable),
view_count, share_count, published_at, created_at, updated_at
```

### Categories
```
id, parent_id, slug, name, description, icon,
order, created_at, updated_at
```

### Comments
```
id, guide_id, user_id, parent_id, content,
created_at, updated_at, deleted_at
```

### Reactions
```
id, guide_id, user_id, type (enum: helpful, saved_me, outdated, needs_update),
created_at, unique(guide_id, user_id, type)
```

### Saved Guides
```
id, user_id, guide_id, created_at,
unique(user_id, guide_id)
```

### RTFM Messages
```
id, user_id, message, is_approved, usage_count,
created_at, updated_at
```

### Guide Edits (Audit Trail)
```
id, guide_id, user_id, type (enum: create, update, delete),
changes (json), created_at
```

### Share Images
```
id, guide_id, user_id, config (json: colors, message, etc.),
image_path, created_at
```

### User Trust Levels
```
id, user_id, trust_level (enum: new, member, trusted, moderator, admin),
points, granted_at, granted_by_user_id
```

### Guide Versions
```
id, guide_id, version_name, version_slug, content (markdown),
is_default, deprecated_at, created_at, updated_at
```

### Translations
```
id, translatable_type, translatable_id, locale (enum: en, es, fr, de, ja, etc.),
field_name, translated_content, translated_by_user_id,
is_approved, created_at, updated_at
```

### Badges
```
id, slug, name, description, icon, rarity (enum: common, rare, epic, legendary),
criteria (json), created_at, updated_at
```

### User Badges
```
id, user_id, badge_id, earned_at,
unique(user_id, badge_id)
```

### Newsletter Subscriptions
```
id, user_id, email, is_subscribed, preferences (json: categories, frequency),
subscribed_at, unsubscribed_at
```

### Teams
```
id, name, slug, owner_user_id, plan (enum: free, paid),
logo, brand_colors (json), profanity_filter_enabled (boolean),
created_at, updated_at
```

### Team Members
```
id, team_id, user_id, role (enum: owner, admin, member),
invited_at, joined_at, invited_by_user_id,
unique(team_id, user_id)
```

### Guide Templates
```
id, name, slug, description, structure (json),
category_id, is_official, created_by_user_id,
usage_count, created_at, updated_at
```

### Content Flags
```
id, flaggable_type, flaggable_id, user_id,
reason (enum: spam, inappropriate, outdated, other),
description, status (enum: pending, resolved, dismissed),
resolved_by_user_id, resolved_at, created_at
```

## User Flows

### First-Time Visitor
1. Lands on homepage with trending/featured guides
2. Searches or browses categories
3. Reads guide with humorous RTFM header
4. Encounters "Sign in to comment/save" CTA
5. Clicks OAuth provider (GitHub recommended)
6. Redirected back, now authenticated
7. Can comment, save, submit guides

### Returning User
1. Lands on homepage (personalized with saved guides)
2. Sees notification badge for comment replies
3. Submits new guide or edit suggestion
4. Customizes share image for social media
5. Earns reputation points for helpful contributions

### Content Creator
1. OAuth login
2. Navigate to "Submit Guide"
3. Choose category/subcategory
4. Write in markdown with live preview
5. Add metadata (difficulty, OS, tags)
6. Save as draft or submit for review
7. Receive notification when published
8. Track views/reactions/comments

## Development Phases

### Phase 1: Foundation (Weeks 1-2)
- [ ] Laravel 12 setup with TALL stack
- [ ] OAuth integration (GitHub, Google, GitLab)
- [ ] Meilisearch installation and Scout configuration
- [ ] Database schema and migrations
- [ ] Seeders with sample guides
- [ ] Basic routing structure
- [ ] Livewire component architecture
- [ ] Flux UI integration

### Phase 2: Core Guide System (Weeks 3-4)
- [ ] Guide listing page with filters
- [ ] Guide detail page with markdown rendering
- [ ] Category system (hierarchical)
- [ ] Search functionality (Meilisearch + Scout with instant search)
- [ ] Configure searchable attributes, filterable attributes, synonyms
- [ ] View tracking
- [ ] Related guides algorithm
- [ ] RTFM message randomizer

### Phase 3: User Features (Weeks 5-6)
- [ ] User profiles
- [ ] Saved guides (bookmarking)
- [ ] Comments system (Livewire)
- [ ] Reactions system
- [ ] Notifications (comment replies)
- [ ] Reputation system

### Phase 4: Content Creation (Weeks 7-8)
- [ ] Guide submission form
- [ ] Markdown editor with preview
- [ ] Draft system
- [ ] Admin review queue
- [ ] Edit suggestions
- [ ] Contributor attribution

### Phase 5: Sharing & Customization (Weeks 9-10)
- [ ] Dynamic OG image generation
- [ ] Share image customization UI
- [ ] Social share buttons
- [ ] Embed widget
- [ ] Share tracking
- [ ] User-submitted RTFM messages

### Phase 6: API (Weeks 11-12)
- [ ] API routes setup
- [ ] Sanctum integration
- [ ] API resources
- [ ] Rate limiting
- [ ] API documentation (OpenAPI/Swagger)
- [ ] Webhook endpoints

### Phase 7: Polish & Launch (Weeks 13-14)
- [ ] Browser testing (Pest 4)
- [ ] Performance optimization
- [ ] SEO optimization
- [ ] Analytics integration
- [ ] Error tracking (Sentry/Bugsnag)
- [ ] Deployment pipeline
- [ ] Beta testing
- [ ] Public launch

### Phase 8: Advanced Features - Part 1 (Weeks 15-17)
- [ ] Trust & moderation system
- [ ] User trust levels and point tracking
- [ ] Automated moderation queue
- [ ] Content flagging system
- [ ] Badge system (define badges, criteria)
- [ ] Badge earning automation
- [ ] Guide versioning (UI and backend)
- [ ] Version selector component
- [ ] Guide templates (create official templates)
- [ ] Template selection during guide creation

### Phase 9: Advanced Features - Part 2 (Weeks 18-20)
- [ ] Internationalization setup (Laravel i18n)
- [ ] Translation contribution system
- [ ] Language selector UI
- [ ] Newsletter system (subscription, preferences)
- [ ] Email templates and digest generation
- [ ] Newsletter queue processing
- [ ] Laravel Pennant setup
- [ ] Team accounts (create, invite, manage)
- [ ] Private guides functionality
- [ ] Profanity filter toggle

## Testing Strategy

### Unit Tests (Pest)
- Model methods and relationships
- Service classes (search, reputation, etc.)
- API resource transformations
- Helper functions

### Feature Tests (Pest)
- OAuth authentication flow
- Guide CRUD operations
- Comment posting and threading
- Search functionality
- API endpoints
- Permissions and authorization
- Trust level progression
- Badge earning automation
- Guide versioning
- Translation submissions
- Newsletter subscriptions
- Team account management (Pennant-gated)
- Content flagging and moderation queue

### Browser Tests (Pest 4)
- Complete user journeys
- OAuth login flow
- Guide submission process
- Markdown editor interactions
- Share image customization
- Mobile responsiveness
- Version selector interaction
- Language switcher
- Badge showcase display
- Template selection during guide creation
- Team creation and invitation flow
- Content flagging workflow

### Test Coverage Goals
- Minimum 80% overall coverage
- 100% coverage for critical paths (auth, payments if added)

## Performance Targets

- **Page Load**: < 2s (LCP)
- **Time to Interactive**: < 3s
- **API Response**: < 200ms (95th percentile)
- **Search Results**: < 50ms (Meilisearch target)
- **Concurrent Users**: 10,000+

## SEO Strategy

- **URLs**: Clean, hierarchical slugs
- **Meta Tags**: Dynamic OG images per guide
- **Sitemap**: Auto-generated, updated daily
- **Structured Data**: Article schema markup
- **Canonical URLs**: Prevent duplicate content
- **RSS Feed**: For new guides

## Content Strategy

### Launch Content
- 50+ seed guides across popular topics:
  - Server administration (Nginx, Apache, MySQL)
  - Laravel development
  - Git workflows
  - Docker/containerization
  - Common CLI tools

### Community Growth
- Featured contributors
- Monthly guide of the month
- Contributor leaderboard
- Social media presence (@rtfmguide)

## Monetization Strategy

### Free Tier (Default)
- Full access to public guides
- Community features (comments, reactions, saved guides)
- Limited API rate (100 requests/hour)
- Ads displayed (tasteful, dev-focused like Carbon Ads)
- Public guide submissions
- Badge earning
- Newsletter subscription

### Paid Tier (Pennant-gated) - $19/month per team
- **Private Guides**: Create internal documentation
- **Team Accounts**: Up to 10 members included, +$5/additional member
- **Custom Branding**: Logo, colors on team guides
- **Profanity Filter Toggle**: Professional mode for business use
- **Ad-Free Experience**: No ads on any pages
- **Advanced Analytics**: Guide views, engagement metrics
- **Priority Support**: Faster response times
- **API Tier**: 1,000 requests/hour

### Additional Revenue Streams
- **Sponsorships**: Tool/service sponsors for relevant categories (e.g., "Nginx guides sponsored by DigitalOcean")
- **Featured Placements**: Promoted guides from sponsors
- **Enterprise Tier**: Custom pricing for large teams (50+ members), white-labeling options
- **Affiliate Links**: Tool recommendations with affiliate links in guides

## Success Metrics

### MVP Launch Goals (3 months post-launch)
- 100+ published guides
- 1,000+ registered users
- 10,000+ monthly unique visitors
- 500+ comments/interactions
- 20+ community-submitted guides

### Advanced Features Launch Goals (6 months)
- Guide versioning: 50+ guides with multiple versions
- Translations: 3+ languages with 30%+ content coverage
- Badges: 100+ badges earned by users
- Teams: 5+ paid team accounts
- Newsletter: 500+ subscribers
- Templates: 10+ official templates with 200+ uses

### Year 1 Goals
- 500+ guides across 50+ categories
- 10,000+ users with 500+ trusted contributors
- 100,000+ monthly visitors
- Active community contributions (50+ guides/month)
- 10+ languages with quality translations
- 50+ team accounts (mix of free/paid)
- 5,000+ newsletter subscribers

## Technical Debt Prevention

- **Documentation**: Inline PHPDoc, README updates
- **Code Reviews**: Required for all PRs
- **Automated Testing**: CI/CD pipeline
- **Performance Monitoring**: Regular audits
- **Security Audits**: Quarterly reviews
- **Dependency Updates**: Weekly checks

## Confirmed Additional Features

Based on PM approval, the following advanced features will be implemented after MVP launch. These features significantly enhance community engagement, content quality, and monetization potential.

**Summary**:
- StackOverflow-style trust & moderation system
- Multi-version guide support (Laravel 10 vs 11, OS versions)
- Full internationalization with community translations
- Weekly newsletter with personalized content
- Badge & achievement system for gamification
- Paid tier with private guides & team accounts (Laravel Pennant)
- Guide templates for consistent quality
- API-first architecture for future NativePHP mobile app

### 1. Trust & Moderation System (StackOverflow-style)
- **Trust Levels**: New, Member, Trusted, Moderator, Admin
- **Automatic Moderation**: New/flagged users have content reviewed before publishing
- **Point-Based Progression**: Earn trust through quality contributions
- **Privileges by Level**:
  - New: Submit with review, limited comments
  - Member: Direct publish drafts, full commenting
  - Trusted: Edit others' guides, skip moderation
  - Moderator: Review queue, flag management
  - Admin: Full control
- **Spam Detection**: Automated flagging of suspicious content
- **User Flagging**: Community can report spam/inappropriate content

### 2. Guide Versioning
- **Multiple Versions per Guide**: Laravel 10 vs 11, Ubuntu 20.04 vs 22.04, etc.
- **Version Selector**: Dropdown to switch between versions
- **Version-Specific Content**: Each version has its own content, code snippets
- **Default Version**: Smart detection or most recent
- **Version Metadata**: Release date, deprecation notices
- **Cross-Version Navigation**: "See this guide for Laravel 11" links
- **Use Cases**: Operating systems, frameworks, tools with breaking changes

### 3. Internationalization (i18n)
- **Multi-Language Support**: English (default), Spanish, French, German, Japanese, etc.
- **Translatable Content**: Guide titles, content, UI elements
- **Contributor Translations**: Community can submit translations
- **Language Selector**: Persistent user preference
- **Machine Translation Integration**: DeepL/Google Translate suggestions for contributors
- **Translation Status**: Track completion percentage per language
- **Fallback**: Show English if translation unavailable

### 4. Newsletter System
- **Weekly Digest**: New guides, trending content, community highlights
- **Personalized Content**: Based on saved categories/tags
- **Subscription Management**: Opt-in during signup, manage in profile
- **Email Templates**: Beautiful, responsive designs
- **Queue Processing**: Background job for bulk sending
- **Analytics**: Open rates, click-through tracking
- **Unsubscribe**: One-click unsubscribe link

### 5. Badge & Achievement System
- **Automatic Badges**: First guide, 10 guides, 100 helpful reactions, etc.
- **Special Badges**: Beta tester, top contributor, streak badges
- **Display**: Profile badge showcase, inline next to username
- **Progress Tracking**: "You're 3 guides away from 'Prolific Author'"
- **Rarity Tiers**: Common, Rare, Epic, Legendary
- **Social Sharing**: Share badge unlocks on social media
- **Easter Eggs**: Hidden badges for fun discoveries

### 6. Private Guides & Team Accounts (Pennant-gated)
- **Free Tier**: Public guides only, community features
- **Paid Tier** (Pennant feature flag):
  - Create private guides (internal documentation)
  - Team/organization accounts with multiple members
  - Team-only guide visibility
  - Custom branding (logo, colors)
  - Priority support
  - Advanced analytics
- **Profanity Filter Toggle**: Teams can enable/disable RTFM humor for professional use
- **Invite System**: Team admins invite members via email
- **Access Control**: Team owner, admin, member roles

### 7. Guide Templates
- **Pre-defined Structures**:
  - "How to Install X"
  - "How to Configure Y"
  - "Troubleshooting Z"
  - "Quick Reference"
  - "CLI Command Guide"
- **Template Prompts**: Fill-in-the-blank sections
- **Custom Templates**: Users can create and share their own
- **Template Library**: Browse/search available templates
- **Metadata Presets**: Templates include suggested categories, difficulty

### 8. Mobile App Preparation
- **API-First Architecture**: All features accessible via API
- **Comprehensive Endpoints**: Full CRUD for guides, comments, user management
- **Future NativePHP App**: Separate repository, shares backend
- **Consistent Data Models**: API resources mirror app structure
- **Offline Support Consideration**: API designed for caching/sync

## Why Meilisearch?

Meilisearch is perfect for rtfm.guide because:
- **Speed**: Sub-50ms search responses for instant-search UX
- **Developer-Friendly**: RESTful API, simple setup, Laravel Scout integration
- **Typo Tolerance**: Users searching "ngnix" will still find "nginx" guides
- **Faceted Search**: Easy category/difficulty/OS filtering with counts
- **Relevance**: Smart ranking considers multiple factors (freshness, popularity, text match)
- **Lightweight**: Can run alongside the app or as a separate service
- **Highlights**: Automatic search term highlighting in results
- **Synonyms**: Configure "restart" = "reboot", "ssl" = "tls", etc.

## Next Steps

1. **Approve Plan**: Review and sign off on scope
2. **Environment Setup**: Laravel Herd, database, OAuth apps, Meilisearch instance
3. **Design Mockups**: Wireframes for key pages (optional)
4. **Sprint Planning**: Break Phase 1 into daily tasks
5. **Repository Setup**: GitHub repo, issue templates, project board
6. **Kick-off**: Begin development!

---

**Built with**: Laravel 12, Livewire 3, Tailwind v4, Flux UI, Meilisearch, Pennant, Pest 4
**Estimated Timeline**:
- 14 weeks to MVP launch
- 20 weeks to full feature set (advanced features included)
**Team**: 1 developer (you!) + 1 PM (me!)

Let's build something the dev community will actually want to use. 🚀
