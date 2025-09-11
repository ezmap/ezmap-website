# EZ Map - Google Maps Made Easy

EZ Map is a Laravel 9 web application that provides a GUI for configuring Google Maps with zero programming knowledge required. Users can create custom maps with themes, markers, heatmaps, and various controls, then generate embeddable HTML/JavaScript code.

**Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.**

## Working Effectively

### Bootstrap and Setup (Fresh Clone)
Run these commands in sequence to set up a working development environment:

```bash
# Install PHP dependencies - NEVER CANCEL: Takes 30-60 seconds
composer install --no-interaction --prefer-dist
# Timeout: Set to 10+ minutes to ensure completion

# Set up environment configuration  
cp .env.example .env

# Configure SQLite database in .env file
sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
sed -i '/DB_HOST=/d; /DB_PORT=/d; /DB_USERNAME=/d; /DB_PASSWORD=/d' .env
sed -i 's|DB_DATABASE=laravel|DB_DATABASE='$(pwd)'/database/database.sqlite|' .env

# Create SQLite database file
touch database/database.sqlite

# Run database migrations - NEVER CANCEL: Takes <1 second
php artisan migrate --force
# Timeout: Set to 5+ minutes

# Generate application encryption key - Takes <1 second
php artisan key:generate

# Seed database with sample data (optional) - Takes <1 second  
php artisan db:seed
```

### Development Server
```bash
# Start Laravel development server - NEVER CANCEL: Runs continuously
php artisan serve --host=0.0.0.0 --port=8000
# Access application at: http://localhost:8000
# Server starts in 1-2 seconds, runs until stopped
```

### Build and Asset Management
The application uses Laravel Mix for asset compilation. Pre-compiled assets exist in `public/`:
- `public/js/all.js` - Main JavaScript bundle (Vue.js, jQuery, components)
- `public/css/app.css` - Main stylesheet
- `public/mix-manifest.json` - Asset manifest for cache busting

**No build step required** - assets are pre-compiled and ready to use.

## Testing

### Manual Validation Scenarios
After making changes, **ALWAYS** test these core user workflows:

1. **Map Configuration Test**:
   - Navigate to http://localhost:8000
   - Modify map settings (dimensions, zoom, map type)  
   - Verify live preview updates (may show placeholder due to missing Google API key)
   - Check generated code in "Your map code" section

2. **Theme Selection Test**:
   - Scroll to "Themes from Snazzy Maps" section
   - Click filter links (colorful, dark, etc.)
   - Verify theme filtering works (shows "Showing X of Y themes")

3. **User Authentication Test**:
   - Click "Log In" or "Register" links
   - Verify authentication pages load correctly
   - Test login/registration forms

### Automated Tests
```bash
# PHPUnit tests exist but are for older Laravel version
# Current test files in /tests/:
# - ExampleTest.php (Laravel 5 syntax - needs updating)  
# - MapExportTest.php (functionality tests)
# - AdminDeleteUserTest.php (admin features)
# - AdminUserPaginationTest.php (admin pagination)

# Run tests (currently needs fixes for Laravel 9):
vendor/bin/phpunit
# Expected: Tests need updating for Laravel 9 compatibility
```

## Validation Requirements

### NEVER CANCEL Warnings
- **Composer install**: 3-5 minutes - DO NOT CANCEL, set timeout to 10+ minutes
- **Database migrations**: 10-15 seconds - Set timeout to 5+ minutes  
- **Development server**: Runs continuously - DO NOT stop unless switching tasks

### Essential Validation Steps
Before considering work complete, **ALWAYS**:
1. Start the development server successfully 
2. Load http://localhost:8000 and verify UI appears correctly
3. Test at least one core user scenario (map configuration)
4. Check browser console for critical JavaScript errors
5. Verify database operations work (user registration/login)

## Key Application Components

### Important Files and Directories
```
app/
├── Http/Controllers/
│   ├── GeneralController.php - Main homepage and map interface
│   ├── AdminController.php - Admin functionality  
│   └── Auth/ - Authentication controllers
├── Models/
│   ├── User.php - User authentication
│   ├── Map.php - Map configurations
│   ├── Theme.php - Snazzy Maps themes
│   └── Icon.php - Map marker icons
└── Providers/AppServiceProvider.php - Loads themes in boot()

resources/views/
├── layouts/ - Base templates
├── index.blade.php - Main map configuration interface
├── help/ - Help documentation  
└── auth/ - Authentication pages

public/
├── js/all.js - Main JavaScript (Vue.js components)
├── css/app.css - Main styles
└── mix-manifest.json - Asset cache busting

config/
├── database.php - Database configuration
├── app.php - Application settings
└── services.php - Third-party services (Google Maps API)
```

### Database Schema
Key tables:
- `users` - User accounts and API keys
- `maps` - Saved map configurations  
- `themes` - Snazzy Maps themes and styling
- `icons` - Custom map marker icons

### API Endpoints
- `/api` - API documentation and key management
- `/api/maps` - Map CRUD operations  
- `/api/themes` - Theme management

## Common Development Tasks

### Adding New Map Features
1. Update `app/Http/Controllers/GeneralController.php`
2. Modify `resources/views/index.blade.php` for UI
3. Update JavaScript in `public/js/all.js` or source files
4. Test map generation and code output

### Debugging Database Issues
```bash
# Check database connection
php artisan tinker
# In tinker: DB::connection()->getPdo()

# Reset database completely
php artisan migrate:fresh --seed

# Check specific tables
php artisan tinker  
# In tinker: App\Models\Theme::count()
```

### Working with Themes
The application loads Snazzy Maps themes during boot. Key locations:
- Theme data seeded via migrations
- Filter logic in `app/Providers/AppServiceProvider.php` 
- Frontend filtering in main JavaScript bundle

## Environment Configuration

### Required Environment Variables (.env)
```
APP_NAME=Laravel
APP_ENV=local  
APP_KEY=<generated-key>
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# Google Maps (optional for basic testing)
GOOGLE_MAPS_API_KEY=<your-api-key>
```

### Dependencies
- **PHP**: 8.0.2+ (tested with 8.3.6)
- **Composer**: 2.x (tested with 2.8.11)  
- **SQLite**: Built into PHP (or MySQL/PostgreSQL)
- **Laravel**: 9.52.17
- **Node.js**: Not required - assets pre-compiled

## Troubleshooting

### Application Key Missing
If you see "No application encryption key has been specified":
```bash
php artisan key:generate
```

### Database Connection Errors  
Verify SQLite file exists and is writable:
```bash
ls -la database/database.sqlite
php artisan migrate:status
```

### Asset Loading Issues
Assets are pre-compiled. If missing:
```bash
# Check manifest file
cat public/mix-manifest.json
# Verify files exist  
ls -la public/js/all.js public/css/app.css
```

### Google Maps Not Loading
This is expected in local development without a valid API key. The application core functionality works without it.

## Performance Notes
- **Composer install**: 30-60 seconds (downloads ~119 packages)
- **Server startup**: 1-2 seconds
- **Database migrations**: <1 second (creates 13 tables)
- **App key generation**: <1 second
- **Database seeding**: <1 second
- **Page load**: <1 second (local development)

The application is optimized for quick development iteration with pre-compiled assets and SQLite for fast database operations.