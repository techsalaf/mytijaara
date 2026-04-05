---
description: MyTijaara Workspace Repository Information
alwaysApply: true
---

# MyTijaara Workspace Overview

## Workspace Summary
MyTijaara is a multi-platform e-commerce and food delivery ecosystem consisting of three interconnected applications:
1. **Flutter Mobile App** (`mytijaara-user-app`) - Multi-vendor food, grocery, pharmacy, and parcel delivery platform
2. **Next.js Web Frontend** (`mytijaara-react`) - Modern web interface for customers
3. **Laravel API Backend** (`mytijaara`) - REST API, admin panel, and real-time services

All three projects share unified Firebase authentication, payment processing, and real-time communication infrastructure.

## Workspace Structure
```
htdocs/
├── mytijaara/                    # Laravel Backend API
├── mytijaara-react/              # Next.js Web Frontend
├── mytijaara-user-app/           # Flutter Mobile App
└── [muzaksfood/]                 # Temporary third-party (to be removed)
```

### Core Components
- **Firebase Services**: Authentication, Cloud Messaging, Real-time Database
- **Payment Integration**: Stripe, Razorpay, Mercado Pago, PhonePe, Xendit, Iyzico
- **Real-time Communication**: Laravel Reverb (WebSocket), Firebase Messaging
- **Maps & Geolocation**: Google Maps API, spatial database queries
- **Admin Dashboard**: Vendor management, analytics, order processing

---

## Project 1: MyTijaara User App (Flutter Mobile)

**Location**: `mytijaara-user-app/`  
**Type**: Cross-platform mobile app (iOS, Android, Web)

### Language & Runtime
- **Language**: Dart
- **SDK**: 3.2.0+
- **Flutter**: 3.35.6
- **Package Manager**: Pub
- **Platforms**: iOS, Android, Flutter Web

### Key Dependencies
- **State Management**: GetX (custom fork)
- **Local Storage**: SharedPreferences
- **Authentication**: Firebase Auth (6.1.0), Google, Facebook, Apple Sign-In
- **Maps**: Google Maps Flutter (2.13.1)
- **Real-time**: Firebase Messaging (16.0.2), Pusher Channels
- **Media**: Image Picker, Video Player, Lottie, SVG support
- **Database**: Drift ORM (2.28.2)
- **Location**: Geolocator (14.0.2), Location (8.0.1)
- **Notifications**: Firebase Messaging, Local Notifications

### Build Commands
```bash
flutter pub get
flutter run -d android              # Android
flutter run -d ios                  # iOS
flutter build apk --release         # APK build
flutter build ios --release         # iOS build
flutter build web --release         # Web build
flutter test                        # Run tests
```

### Assets & Configuration
- **Assets**: Images, language files, maps, JSON data
- **Custom Fonts**: Roboto family (Regular, Medium, Bold, Black)
- **Configuration**: `pubspec.yaml`, `analysis_options.yaml`

---

## Project 2: MyTijaara React Frontend (Next.js)

**Location**: `mytijaara-react/`  
**Type**: Server-side rendered web application

### Language & Runtime
- **Language**: JavaScript/React
- **Framework**: Next.js 16.0.10
- **React**: 19.1.0
- **Node.js**: 16+
- **Package Manager**: npm / yarn

### Key Dependencies
- **UI Framework**: Material-UI (MUI) with Emotion styling
- **State**: Redux Toolkit (1.9.1), React Query (3.39.2)
- **Forms**: Formik + Yup validation
- **Maps**: Google Maps API, React Google Maps (2.17.1)
- **Date/Time**: date-fns (4.1.0), Dayjs, MUI Date Pickers
- **Auth**: Firebase (9.15.0), JWT decode, social login
- **i18n**: i18next with React plugin
- **Real-time**: Firebase Messaging, React Hot Toast
- **Media**: React Player, React Lottie, Image Magnifiers
- **Utils**: Axios, simplebar-react, nprogress

### Build Commands
```bash
npm install                  # Install dependencies
npm run dev                  # Development server (localhost:3000)
npm run build                # Production build
npm start                    # Start production server
npm run lint                 # Code linting
```

### Configuration Files
- `next.config.js` - Next.js setup with webpack customization
- `jsconfig.json` - JavaScript configuration
- `middleware.js` - Route middleware
- `vercel.json` - Vercel deployment config
- `.env.local.example` - Environment template

### Deployment
- **Platform**: Vercel
- **Output**: Standalone (Docker-compatible)
- **Image Optimization**: Remote pattern support for external images

---

## Project 3: MyTijaara Backend (Laravel API)

**Location**: `mytijaara/`  
**Type**: REST API backend with admin dashboard

### Language & Runtime
- **Language**: PHP
- **Version**: 8.2, 8.3, 8.4 compatible
- **Framework**: Laravel 12.0
- **Build System**: Laravel Mix (Webpack)
- **Package Manager**: Composer

### Core Dependencies
**Framework**:
- laravel/framework (12.0)
- laravel/passport (12.0) - API OAuth2
- laravel/reverb (1.0) - WebSocket server
- nwidart/laravel-modules (12.0) - Modular architecture

**Payments**:
- stripe/stripe-php (10.10)
- razorpay/razorpay (2.8)
- mercadopago/dx-php (3.0.7)
- phonepe/phonepe-pg-php-sdk (1.0)
- xendit/xendit-php (2.19)
- iyzico/iyzipay-php (2.0)

**Firebase & Real-time**:
- kreait/firebase-php (7.12)
- firebase/php-jwt (6.4)

**Data & Files**:
- maatwebsite/excel (3.1)
- intervention/image (3.11)
- mpdf/mpdf (8.1)
- league/flysystem-aws-s3-v3 (3.0)

**Geospatial**:
- matanyadaev/laravel-eloquent-spatial (4.5.0)

**Other**:
- openai-php/laravel (0.17) - AI integration
- guzzlehttp/guzzle (7.10)
- twilio/sdk (6.39)

### Build & Installation
```bash
composer install              # PHP dependencies
npm install                  # Frontend dependencies
php artisan key:generate     # Generate app key
php artisan migrate          # Run migrations
php artisan db:seed          # Seed database

npm run dev                  # Dev assets
npm run production           # Production assets
npm run watch                # Watch assets

php artisan serve            # Start server
php artisan reverb:start     # Start WebSocket server
```

### Configuration
**Key Files**:
- `config/auth.php` - Authentication
- `config/database.php` - Database
- `config/app.php` - Application
- `.env.example` - Environment template
- `phpunit.xml` - Test configuration

**Core Directories**:
- `app/CentralLogics/` - Business logic
- `app/Library/` - Constants & utilities
- `Modules/` - Feature modules
- `routes/api/` - API endpoints
- `routes/admin.php` - Admin routes

### Testing
- **Framework**: PHPUnit 11.5.3
- **Location**: `tests/Unit`, `tests/Feature`
- **Command**: `php artisan test`

### Key Features
- **REST API** with Passport authentication
- **Real-time WebSocket** via Reverb
- **Multi-vendor** management system
- **Payment** processing (6+ gateways)
- **AI Integration** (OpenAI content generation)
- **File Management** with AWS S3 support
- **Spatial Queries** for geolocation features
- **Admin Dashboard** for system management
- **Excel Operations** for bulk data handling
- **Modular Architecture** for scalability

---

## Shared Infrastructure

### Firebase Setup
- **Projects**: One shared Firebase project across all platforms
- **Services**: Auth, Firestore, Cloud Messaging, Crashes
- **Configuration**: 
  - Web: `firebase.json` in web/mobile apps
  - Mobile: `google-services.json` (Android), `GoogleService-Info.plist` (iOS)

### Authentication Flow
1. User logs in via mobile/web (Firebase Auth or social)
2. Receives ID token from Firebase
3. Exchanges token with Laravel backend for API token
4. Uses API token for subsequent requests

### Payment Processing
- **Primary**: Stripe, Razorpay (most implementations)
- **Regional**: PhonePe (India), Xendit (Southeast Asia)
- **Fallback**: Multiple provider support for reliability

### Real-time Features
- **WebSocket**: Laravel Reverb handles live order updates
- **Push Notifications**: Firebase Cloud Messaging
- **Chat/Messaging**: Pusher Channels (fallback)

---

## Development Setup

### One-time Setup
```bash
# Clone workspace
git clone <workspace-repo>
cd mytijaara

# Setup Backend
composer install
npm install
cp .env.example .env
php artisan migrate

# Setup Frontend (Web)
cd ../mytijaara-react
npm install

# Setup Mobile
cd ../mytijaara-user-app
flutter pub get
```

### Running All Services
```bash
# Terminal 1: Backend API
cd mytijaara
php artisan serve                    # :8000

# Terminal 2: WebSocket
php artisan reverb:start

# Terminal 3: Web Frontend
cd ../mytijaara-react
npm run dev                          # :3000

# Terminal 4: Mobile Dev
cd ../mytijaara-user-app
flutter run -d chrome               # or emulator/device
```

### Environment Variables
**Backend (.env)**:
```
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=mytijaara
DB_USERNAME=root
DB_PASSWORD=

STRIPE_SECRET_KEY=sk_test_...
FIREBASE_PROJECT_ID=mytijaara-xxx
```

**Web (.env.local)**:
```
NEXT_PUBLIC_API_URL=http://localhost:8000
NEXT_PUBLIC_FIREBASE_CONFIG={...}
```

**Mobile (pubspec.yaml + Firebase)**:
- Firebase project linked via Flutter CLI
- Google Maps API key in AndroidManifest.xml / Info.plist

---

## Deployment Architecture

### Production Stack
- **Backend**: Laravel on VPS/Dedicated Server (Reverb on separate instance)
- **Web**: Vercel (Next.js platform)
- **Mobile**: App Store & Google Play (via CI/CD)
- **Database**: MySQL 8.0+
- **Cache**: Redis (optional, for performance)
- **Storage**: AWS S3 (file uploads)
- **Firebase**: Google Cloud hosted

### CI/CD Considerations
- Backend: Deploy via Git hooks or CI/CD (Laravel Sail for local dev)
- Web: Auto-deploy from Vercel on git push
- Mobile: GitHub Actions or similar for app signing and distribution
- Database Migrations: Run on backend deployment
- WebSocket: Keep-alive and clustering for multiple servers

---

## Notes
- **MuzaksFood Directory**: Third-party project included for module reference, should be removed from workspace
- **Codebase Size**: Large workspace - estimated 500MB+ dependencies across all projects
- **Development Time**: Expect 10-15 min for full environment setup
- **Testing Coverage**: Each project has independent test suite
