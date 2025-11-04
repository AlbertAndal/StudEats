<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfilePhotoController extends Controller
{
    /**
     * Upload and process profile photo.
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file. Please upload a valid image (JPEG, PNG, JPG, GIF) under 5MB.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Delete old profile photo if exists
            if ($user->profile_photo) {
                $user->deleteProfilePhoto();
            }

            $file = $request->file('photo');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Ensure directories exist
            $tempDir = storage_path('app/public/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $profilePhotosDir = storage_path('app/public/profile_photos');
            if (!file_exists($profilePhotosDir)) {
                mkdir($profilePhotosDir, 0755, true);
            }
            
            // Store original file temporarily
            $tempPath = $file->storeAs('temp', $filename, 'public');
            $fullTempPath = storage_path('app/public/' . $tempPath);
            
            // Try to get image dimensions using basic functions
            $imageInfo = @getimagesize($fullTempPath);
            $width = $imageInfo ? $imageInfo[0] : 800;
            $height = $imageInfo ? $imageInfo[1] : 600;
            
            return response()->json([
                'success' => true,
                'message' => 'Photo uploaded successfully. Please crop your image.',
                'data' => [
                    'temp_path' => $tempPath,
                    'preview_url' => asset('storage/' . $tempPath),
                    'width' => $width,
                    'height' => $height,
                ]
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Profile photo upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crop and save the profile photo.
     */
    public function crop(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'temp_path' => 'required|string',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'width' => 'required|numeric|min:50',
            'height' => 'required|numeric|min:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid crop data.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $tempPath = $request->temp_path;
            $fullTempPath = storage_path('app/public/' . $tempPath);
            
            if (!file_exists($fullTempPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Temporary file not found. Please upload again.'
                ], 404);
            }

            // Check if Intervention Image is available and GD/Imagick is installed
            if (class_exists('Intervention\Image\ImageManager') && 
                (extension_loaded('gd') || extension_loaded('imagick'))) {
                
                // Use Intervention Image for advanced processing
                $manager = new \Intervention\Image\ImageManager(
                    extension_loaded('imagick') ? 
                    new \Intervention\Image\Drivers\Imagick\Driver() : 
                    new \Intervention\Image\Drivers\Gd\Driver()
                );
                
                $image = $manager->read($fullTempPath);
                
                // Crop the image
                $image->crop(
                    (int) $request->width,
                    (int) $request->height,
                    (int) $request->x,
                    (int) $request->y
                );
                
                // Resize to standard profile photo size (400x400)
                $image->resize(400, 400);
                
                // Create final filename
                $finalFilename = 'profile_photos/profile_' . $user->id . '_' . time() . '.jpg';
                $finalPath = storage_path('app/public/' . $finalFilename);
                
                // Ensure directory exists
                $directory = dirname($finalPath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Save the cropped image
                $image->toJpeg(85)->save($finalPath);
                
            } else {
                // Fallback: Just copy the original file (basic functionality)
                $finalFilename = 'profile_photos/profile_' . $user->id . '_' . time() . '.' . 
                                pathinfo($fullTempPath, PATHINFO_EXTENSION);
                $finalPath = storage_path('app/public/' . $finalFilename);
                
                // Ensure directory exists
                $directory = dirname($finalPath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Copy the original file
                copy($fullTempPath, $finalPath);
            }
            
            // Update user's profile photo
            $user->update(['profile_photo' => $finalFilename]);
            
            // Clean up temporary file
            if (file_exists($fullTempPath)) {
                unlink($fullTempPath);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Profile photo updated successfully!',
                'data' => [
                    'photo_url' => $user->getProfilePhotoUrlAttribute(),
                    'avatar_url' => $user->getAvatarUrl(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the user's profile photo.
     */
    public function delete(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user->hasProfilePhoto()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No profile photo to delete.'
                ], 404);
            }
            
            $user->deleteProfilePhoto();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile photo deleted successfully.',
                'data' => [
                    'avatar_url' => $user->getAvatarUrl(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current profile photo status.
     */
    public function status(Request $request)
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'has_photo' => $user->hasProfilePhoto(),
                'photo_url' => $user->getProfilePhotoUrlAttribute(),
                'avatar_url' => $user->getAvatarUrl(),
            ]
        ]);
    }
}
