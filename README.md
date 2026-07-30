# Document Scanner - CamScanner Clone

A Laravel 12 Document Scanner application with automatic edge detection, perspective correction, and various image enhancement effects.

## Features

- **Image Upload**: Drag & drop or file input with support for JPG, PNG, and HEIC formats
- **Auto Edge Detection**: Automatic document boundary detection using Cropper.js
- **Manual Corner Adjustment**: Draggable corner points for precise selection
- **Perspective Correction**: Perspective warp/crop to straighten skewed documents
- **Image Effects**: 8 different enhancement effects:
  - Original - No modification
  - No Shadow - Remove shadows for uniform background
  - Lighten - Increase brightness
  - Magic Color - Enhanced contrast, saturation, and sharpness
  - Magic Pro - Maximum enhancement for clarity
  - B&W - Pure black & white
  - Eco - Light grayscale for ink saving
  - Grayscale - Standard grayscale
- **Export Options**: Download as PNG, JPG, or PDF
- **Multi-page PDF**: Merge multiple scans into a single PDF document

## Requirements

### Server Requirements

- **PHP 8.2+** (Tested with PHP 8.4)
- **MySQL 5.7+** or **MariaDB 10.3+**
- **ImageMagick**: Required for Imagick PHP extension
- **Web Server**: Apache/Nginx

### Hosting Requirements

For production/shared hosting deployment, ensure the following:

#### 1. PHP Extensions

Your hosting `php.ini` must include:

```ini
extension=imagick
extension=gd
extension=mbstring
extension=curl
extension=xml
extension=zip
```

#### 2. ImageMagick Library

ImageMagick must be installed on the server:

```bash
# Ubuntu/Debian
sudo apt-get install imagemagick

# CentOS/RHEL
sudo yum install ImageMagick
```

#### 3. Directory Permissions

Ensure these directories are writable:

```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod -R 775 public/storage (symlink to storage/app/public)
```

#### 4. Database Setup

Create a MySQL database and user:

```sql
CREATE DATABASE document_scanner CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'scanner_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON document_scanner.* TO 'scanner_user'@'localhost';
FLUSH PRIVILEGES;
```

## Installation

### Local Development

1. Clone the repository:
```bash
git clone <repository-url>
cd document-scanner
```

2. Install dependencies:
```bash
composer install
npm install
npm run build
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Update `.env` with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=document_scanner
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

6. Create storage link:
```bash
php artisan storage:link
```

7. Start the development server:
```bash
php artisan serve
```

### Production Deployment

1. Upload files to your hosting
2. Configure `.env` with production database credentials
3. Set `APP_ENV=production` and `APP_DEBUG=false`
4. Run `php artisan migrate --force`
5. Run `php artisan storage:link`
6. Configure your web server (Apache/Nginx) to point to the `public/` directory

## Configuration Notes

### File Upload Limits

The application validates:
- Maximum file size: 10MB
- Allowed mime types: `image/jpeg`, `image/png`, `image/heic`, `image/heif`

To adjust, modify the validation in `ScanController.php` and your web server's upload limits.

### Storage

All uploaded and processed images are stored in `storage/app/public/scans/` directory. The `public/storage` symlink makes them accessible via the web.

## Security Notes

- Each scan is tied to a unique session UUID
- No user authentication required (public access)
- File validation prevents malicious uploads
- Session isolation ensures users cannot access others' scans

## Fallback for Missing Imagick

If the hosting server does not have Imagick installed, the application will display an error. Contact your hosting provider to enable the Imagick extension, or consider using a hosting environment that supports it.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + Alpine.js + Tailwind CSS
- **Image Processing**: ImageMagick (via PHP Imagick extension)
- **Client-side**: Cropper.js for edge detection UI
- **Database**: MySQL/MariaDB
- **File Storage**: Laravel Filesystem (local disk)

## License

MIT License - Feel free to use and modify for your own projects.
