<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfilePhotoController extends Controller
{
    /**
     * Upload and process profile photo directly to S3.
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
            $filename = 'profile_photos/profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store file directly to S3
            $path = $file->storeAs('profile_photos', 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension(), 's3');
            
            // Update user's profile photo
            $user->update(['profile_photo' => $path]);
            
            return response()->json([
                'success' => true,
                'message' => 'Profile photo uploaded and updated successfully!',
                'data' => [
                    'photo_url' => $user->getProfilePhotoUrlAttribute(),
                    'avatar_url' => $user->getAvatarUrl(),
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
     * Crop method - simplified since we're using direct S3 upload
     * This method is kept for backward compatibility but now just returns the current photo
     */
    public function crop(Request $request)
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'message' => 'Photo processing completed.',
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
