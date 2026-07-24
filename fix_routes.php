<?php
// Fix localized routes

$routes = [
    'about', 'faqs', 'blog', 'careers', 'services',
    'testimonials', 'appointment', 'track', 'cargo',
    'privacy-policy', 'terms', 'refund-policy',
    'news', 'news.detail', 'blog.detail', 'careers.detail',
    'services.umrah', 'services.visa', 'services.airticket', 'services.hotel',
    'labour-law', 'visa-checker'
];

function fixRoutes($dir, $routes) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($files as $file) {
        if ($file->getExtension() !== 'blade.php') continue;
        
        $content = file_get_contents($file->getPathname());
        $original = $content;
        
        foreach ($routes as $route) {
            // Replace route('routeName') with locale_route('routeName')
            $content = preg_replace("/route\('$route'\)/", "locale_route('$route')", $content);
            // Also handle route("routeName")
            $content = preg_replace("/route\(\"$route\"\)/", "locale_route('$route')", $content);
        }
        
        if ($content !== $original) {
            file_put_contents($file->getPathname(), $content);
            echo "Fixed: " . $file->getPathname() . "\n";
        }
    }
}

fixRoutes('resources/views', $routes);
echo "Done!\n";
