<?php

namespace Core\Support;

class File
{
    /**
     * Upload a file with validation
     * @param array $file The file array from $_FILES
     * @param string $destination Directory to upload to
     * @param array $options Upload options (allowed_types, max_size, filename)
     * @return array|false Array with success status and file info, or false on failure
     */
    public static function upload(array $file, string $destination, array $options = [], $maxSize = 2097152): bool|array
    {
        // Default options
        $defaults = [
            'allowed_types' => [
                // Images
                'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'tiff',

                // Documents
                'pdf', 'doc', 'docx',       // Word
                'xls', 'xlsx',              // Excel
                'ppt', 'pptx',              // PowerPoint
                'txt', 'rtf',               // Text & Rich Text
                'odt', 'ods', 'odp'         // OpenDocument (LibreOffice / OpenOffice)
            ],
            'max_size' => $maxSize, // 2MB
            'filename' => null, // Custom filename (without extension)
            'overwrite' => false
        ];

        $options = array_merge($defaults, $options);

        // Validate file
        if (!self::isValidUpload($file)) {
            return ['success' => false, 'error' => 'Invalid file upload'];
        }

        // Check file size
        if ($file['size'] > $options['max_size']) {
            return ['success' => false, 'error' => 'File size exceeds limit'];
        }

        // Get file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $options['allowed_types'])) {
            return ['success' => false, 'error' => 'File type not allowed'];
        }

        // Create destination directory if it doesn't exist
        if (!is_dir($destination)) {
            if (!mkdir($destination, 0755, true)) {
                return ['success' => false, 'error' => 'Could not create upload directory'];
            }
        }

        // Generate filename
        $filename = pathinfo($file['name'], PATHINFO_FILENAME);
        $filename = self::sanitizeFilename($filename);
        $finalName = time() .'_'. $filename . '.' . $extension;

        // Handle filename conflicts
        if (!$options['overwrite'] && file_exists($destination . '/' . $finalName)) {
            $counter = 1;
            $nameWithoutExt = $filename;
            while (file_exists($destination . '/' . $finalName)) {
                $finalName = $nameWithoutExt . '_' . $counter . '.' . $extension;
                $counter++;
            }
        }

        $filePath = $destination . '/' . $finalName;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return [
                'success' => true,
                'filename' => $finalName,
                'path' => $filePath,
                'size' => $file['size'],
                'type' => $file['type']
            ];
        }

        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }

    /**
     * Delete a file
     * @param string $filePath Path to the file
     * @return bool True if deleted, false otherwise
     */
    public static function delete(string $filePath): bool
    {
        if (file_exists($filePath) && is_file($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Check if file exists
     * @param string $filePath Path to the file
     * @return bool True if exists, false otherwise
     */
    public static function exists(string $filePath): bool
    {
        return file_exists($filePath) && is_file($filePath);
    }

    /**
     * Get file size
     * @param string $filePath Path to the file
     * @return int|false File size in bytes, or false if file doesn't exist
     */
    public static function size(string $filePath)
    {
        if (self::exists($filePath)) {
            return filesize($filePath);
        }
        return false;
    }

    /**
     * Get file MIME type
     * @param string $filePath Path to the file
     * @return string|false MIME type, or false if file doesn't exist
     */
    public static function mimeType(string $filePath): bool|string
    {
        if (self::exists($filePath)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            return $finfo->file($filePath);
        }
        return false;
    }

    /**
     * Copy a file
     * @param string $source Source file path
     * @param string $destination Destination file path
     * @return bool True if copied, false otherwise
     */
    public static function copy(string $source, string $destination): bool
    {
        if (self::exists($source)) {
            $destinationDir = dirname($destination);
            if (!is_dir($destinationDir)) {
                mkdir($destinationDir, 0755, true);
            }
            return copy($source, $destination);
        }
        return false;
    }

    /**
     * Move a file
     * @param string $source Source file path
     * @param string $destination Destination file path
     * @return bool True if moved, false otherwise
     */
    public static function move(string $source, string $destination): bool
    {
        if (self::exists($source)) {
            $destinationDir = dirname($destination);
            if (!is_dir($destinationDir)) {
                mkdir($destinationDir, 0755, true);
            }
            return rename($source, $destination);
        }
        return false;
    }

    /**
     * Get file extension
     * @param string $filePath Path to the file
     * @return string|false File extension, or false if file doesn't exist
     */
    public static function extension(string $filePath)
    {
        if (self::exists($filePath)) {
            return strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        }
        return false;
    }

    /**
     * Get file name without extension
     * @param string $filePath Path to the file
     * @return string|false File name without extension, or false if file doesn't exist
     */
    public static function name(string $filePath)
    {
        if (self::exists($filePath)) {
            return pathinfo($filePath, PATHINFO_FILENAME);
        }
        return false;
    }

    /**
     * Get file name with extension
     * @param string $filePath Path to the file
     * @return string|false File name with extension, or false if file doesn't exist
     */
    public static function basename(string $filePath)
    {
        if (self::exists($filePath)) {
            return basename($filePath);
        }
        return false;
    }

    /**
     * Validate uploaded file
     * @param array $file The file array from $_FILES
     * @return bool True if valid, false otherwise
     */
    private static function isValidUpload($file): bool
    {
        return isset($file['error']) &&
            $file['error'] === UPLOAD_ERR_OK &&
            is_uploaded_file($file['tmp_name']);
    }

    /**
     * Sanitize filename
     * @param string $filename Original filename
     * @return string Sanitized filename
     */
    private static function sanitizeFilename(string $filename): string
    {
        // Remove special characters and spaces
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);
        // Remove leading/trailing underscores
        $filename = trim($filename, '_');
        return $filename;
    }

    /**
     * Create directory if it doesn't exist
     * @param string $path Directory path
     * @param int $permissions Directory permissions
     * @return bool True if created or exists, false otherwise
     */
    public static function makeDirectory(string $path, int $permissions = 0755): bool
    {
        if (!is_dir($path)) {
            return mkdir($path, $permissions, true);
        }
        return true;
    }

    /**
     * Delete directory and its contents
     * @param string $path Directory path
     * @return bool True if deleted, false otherwise
     */
    public static function deleteDirectory(string $path): bool
    {
        if (!is_dir($path)) {
            return false;
        }

        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $path . '/' . $file;
            if (is_dir($filePath)) {
                self::deleteDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }

        return rmdir($path);
    }

    /**
     * Get file info
     * @param string $filePath Path to the file
     * @return array|false File info array, or false if file doesn't exist
     */
    public static function info(string $filePath): bool|array
    {
        if (!self::exists($filePath)) {
            return false;
        }

        return [
            'name' => self::name($filePath),
            'basename' => self::basename($filePath),
            'extension' => self::extension($filePath),
            'size' => self::size($filePath),
            'mime_type' => self::mimeType($filePath),
            'modified' => filemtime($filePath),
            'created' => filectime($filePath)
        ];
    }
} 