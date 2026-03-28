<?php
/**
 * Get the correct image path for blog images
 * @param string $path The image path from database
 * @param string $location Where the image is being displayed 
 *        - 'frontend': for pages in frontend folder (index.php, blog-detail.php)
 *        - 'public': for pages in public folder (public/new&events/, public/partner/, etc.)
 *        - 'admin': for admin pages
 * @return string The correct relative path
 */
function getBlogImagePath($path, $location = 'frontend') {
    // If no path or empty, return placeholder
    if (empty($path)) {
        // Return appropriate placeholder based on location
        if ($location === 'public') {
            return './../../images/placeholder.png';
        } elseif ($location === 'admin') {
            return '../images/placeholder.png';
        }
        return './images/placeholder.png';
    }
    
    // If it's an absolute URL (http:// or https://)
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return $path;
    }
    
    // Clean the path - remove leading ./, ../
    $clean_path = ltrim($path, './');
    $clean_path = ltrim($clean_path, '../');
    
    // Get just the filename for fallback
    $filename = basename($clean_path);
    
    // Based on where the image is being displayed
    if ($location === 'frontend') {
        // For frontend pages: index.php, blog-detail.php (in frontend folder)
        
        // Check if path already includes images/
        if (strpos($clean_path, 'images/') === 0) {
            return './' . $clean_path;
        }
        
        // If path includes admin/uploads/
        if (strpos($clean_path, 'admin/uploads/') === 0) {
            return './../' . $clean_path;
        }
        
        // If path includes uploads/
        if (strpos($clean_path, 'uploads/') === 0) {
            return './../admin/' . $clean_path;
        }
        
        // If it's just a filename, assume it's in new&events folder
        if (strpos($clean_path, '/') === false) {
            // Check if file exists in new&events folder
            $test_path = './images/new&events/' . $filename;
            if (file_exists($test_path)) {
                return $test_path;
            }
            return './images/new&events/' . $filename;
        }
        
        // If path includes new&events/
        if (strpos($clean_path, 'new&events/') === 0) {
            return './images/' . $clean_path;
        }
        
        // Default: assume it's in new&events folder
        return './images/new&events/' . $filename;
        
    } elseif ($location === 'public') {
        // For public pages: public/new&events/, public/partner/, etc.
        
        // Check if path already includes images/
        if (strpos($clean_path, 'images/') === 0) {
            return './../../' . $clean_path;
        }
        
        // If path includes admin/uploads/
        if (strpos($clean_path, 'admin/uploads/') === 0) {
            return './../../' . $clean_path;
        }
        
        // If path includes uploads/
        if (strpos($clean_path, 'uploads/') === 0) {
            return './../../admin/' . $clean_path;
        }
        
        // If path includes new&events/
        if (strpos($clean_path, 'new&events/') === 0) {
            return './../../images/' . $clean_path;
        }
        
        // If it's just a filename, assume it's in new&events folder
        if (strpos($clean_path, '/') === false) {
            $test_path = './../../images/new&events/' . $filename;
            if (file_exists($test_path)) {
                return $test_path;
            }
            return './../../images/new&events/' . $filename;
        }
        
        // Default fallback
        return './../../images/new&events/' . $filename;
        
    } elseif ($location === 'admin') {
        // For admin pages
        
        // Check if path already includes images/
        if (strpos($clean_path, 'images/') === 0) {
            return '../' . $clean_path;
        }
        
        // If it's just a filename, assume it's in uploads folder
        if (strpos($clean_path, '/') === false) {
            return './uploads/' . $filename;
        }
        
        // Default
        return './' . $clean_path;
    }
    
    // Fallback for unknown location
    return './images/placeholder.png';
}

/**
 * Legacy function for backward compatibility
 * Use this for pages that don't specify location
 */
function getImagePath($path) {
    // Try to detect location based on current file path
    $current_file = $_SERVER['PHP_SELF'];
    
    if (strpos($current_file, '/public/') !== false) {
        // We're in a public subdirectory
        return getBlogImagePath($path, 'public');
    } elseif (strpos($current_file, '/admin/') !== false) {
        // We're in admin
        return getBlogImagePath($path, 'admin');
    } else {
        // We're in frontend root
        return getBlogImagePath($path, 'frontend');
    }
}

/**
 * Get placeholder image based on location
 */
function getPlaceholderImage($location = 'frontend') {
    if ($location === 'public') {
        return './../../images/placeholder.png';
    } elseif ($location === 'admin') {
        return '../images/placeholder.png';
    }
    return './images/placeholder.png';
}

/**
 * Check if an image exists and return the correct path
 */
function getValidImagePath($path, $location = 'frontend') {
    $resolved_path = getBlogImagePath($path, $location);
    
    // Remove query string for file check
    $test_path = explode('?', $resolved_path)[0];
    
    // Check if file exists
    if (file_exists($test_path)) {
        return $resolved_path;
    }
    
    // Return placeholder if file doesn't exist
    return getPlaceholderImage($location);
}
?>