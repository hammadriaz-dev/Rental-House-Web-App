<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller {
    /**
    * Display a listing of the resource.
    */

    public function index() {
        $properties = Auth::user()->properties()
        ->with( 'images' )
        ->latest()
        ->get();
        return view( 'landlord.property.index', compact( 'properties' ) );
    }

    /**
    * Show the form for creating a new resource.
    */

    public function create() {
        $statuses = Property::statuses();

        return view( 'landlord.property.create', compact( 'statuses' ) );
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( Request $request ) {
        $validated = $request->validate( [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'rent' => 'required|numeric|min:50|max:99999.99',
            'status' => [ 'required', 'string', 'in:' . implode( ',', Property::statuses() ) ],
            'images' => [ 'required', 'array', 'min:1', 'max:10' ],
            'images.*' => [ 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120' ],
            'video' => [ 'nullable', 'mimes:mp4,mov,ogg,webm', 'max:10240' ],
        ] );

        // Handle video upload
        $videoPath = null;
        if ( $request->hasFile( 'video' ) ) {
            $videoPath = $request->file( 'video' )->store( 'properties/videos', 'public' );
        }

        $property = Auth::user()->properties()->create( array_merge( $validated, [
            'video' => $videoPath,
            'moderation_status' => 'pending',
        ] ) );

        // Handle images upload via relation
        if ( $request->hasFile( 'images' ) ) {
            $images = $request->file( 'images' );
            $isPrimary = true;

            foreach ( $images as $image ) {
                if ( $image->isValid() ) {
                    $imagePath = $image->store( 'properties/gallery', 'public' );

                    $imagesData[] = [
                        'image_path' => $imagePath,
                        'is_primary' => $isPrimary,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $isPrimary = false;

                }
            }
            // Save all images at once
            $property->images()->createMany( $imagesData );
        }

        return redirect()->route( 'properties.index' )->with( 'success', 'Property listed successfully!' );
    }

    /**
    * Display the specified resource.
    */

    public function show( Property $property ) {
        if ( $property->user_id != Auth::id() ) {
            abort( 403 );
        }

        $property->load( 'images' );
        return view( 'landlord.property.show', compact( 'property' ) );
    }

    /**
    * Show the form for editing the specified resource.
    */

    public function edit( Property $property ) {
        if ( $property->moderation_status === 'pending' ) {
            return redirect()->route( 'properties.index' )->with( 'error', 'This property is currently pending admin review and cannot be edited.' );
        }
        if ( $property->user_id !== Auth::id() ) {
            abort( 403 );
        }
        $property->load( 'images' );
        $statuses = Property::statuses();
        return view( 'landlord.property.edit', compact( 'property', 'statuses' ) );
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, Property $property ) {
        if ( $property->user_id !== Auth::id() ) {
            abort( 403 );
        }

        $validated = $request->validate( [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'address'     => 'required|string|max:255',
            'city'        => 'required|string|max:100',
            'rent'        => 'required|numeric|min:50|max:99999.99',
            'status'      => [ 'required', 'string', 'in:' . implode( ',', Property::statuses() ) ],
            'images'      => [ 'nullable', 'array', 'min:1', 'max:10' ],
            'images.*'    => [ 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048' ],
            'video'       => [ 'nullable', 'mimes:mp4,mov,ogg,webm', 'max:10240' ],
        ] );

        // Track if a field requiring re-moderation has changed
        $needsReModeration =
            $property->title !== $validated['title'] ||
            $property->description !== $validated['description'] ||
            $property->address !== $validated['address'] ||
            $property->rent != $validated['rent']; // Note: use != for decimal comparison

        // Handle video upload
        if ( $request->hasFile( 'video' ) ) {
            if ( $property->video && Storage::disk( 'public' )->exists( $property->video ) ) {
                Storage::disk( 'public' )->delete( $property->video );
            }
            $validated[ 'video' ] = $request->file( 'video' )->store( 'properties/videos', 'public' );
            $needsReModeration = true;
        }

        // --- CHANGE 3: Reset moderation_status to 'pending' if key data is changed or new images/video are uploaded ---
        if ($needsReModeration && $property->moderation_status === 'approved') {
            $validated['moderation_status'] = 'pending';
            $message = 'Property updated successfully! Changes require re-approval and the listing is now pending.';
        } else {
            // Keep the existing moderation_status if only rental status, etc., was changed
            $message = 'Property updated successfully!';
        }

        // Update property
        $property->update( $validated );

        // Handle images update via relation
        if ( $request->hasFile( 'images' ) ) {
            // New images always trigger re-moderation if the property was approved
            if ($property->moderation_status === 'approved') {
                 $property->update(['moderation_status' => 'pending']);
                 $message = 'Property updated successfully! New images require re-approval and the listing is now pending.';
            }

            // Delete Old Images from Storage
            foreach ( $property->images as $oldImage ) {
                if ( $oldImage->image_path && Storage::disk( 'public' )->exists( $oldImage->image_path ) ) {
                    Storage::disk( 'public' )->delete( $oldImage->image_path );
                }
            }

            // Delete old records
            $property->images()->delete();

            $imagesData = [];
            $isPrimary  = true;

            foreach ( $request->file( 'images' ) as $image ) {
                if ( $image->isValid() ) {
                    $imagePath = $image->store( 'properties/gallery', 'public' );

                    $imagesData[] = [
                        'image_path' => $imagePath,
                        'is_primary' => $isPrimary,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $isPrimary = false;
                }
            }

            // Save all images at once
            $property->images()->createMany( $imagesData );
        }

        return redirect()
        ->route( 'properties.index' )
        ->with( 'success', $message );
    }


    /**
    * Remove the specified resource from storage.
    */

    public function destroy( Property $property ) {
        if ( $property->user_id !== Auth::id() ) {
            abort( 403 );
        }
        // Delete video
        if ( $property->video && Storage::disk( 'public' )->exists( $property->video ) ) {
            Storage::disk( 'public' )->delete( $property->video );
        }
        // Delete Image
        foreach ( $property->images as $image ) {
            if ( $image->image_path && Storage::disk( 'public' )->exists( $image->image_path ) ) {
                Storage::disk( 'public' )->delete( $image->image_path );
            }
        }
        $property->images()->delete();
        $property->delete();

        return redirect()->route( 'properties.index' )->with( 'success', 'Property Deleted Successfully' );
    }

    public function Adminshow(Property $property)
    {
    $property->load('images', 'user');

    if ($property->moderation_status !== 'pending' && $property->moderation_status !== 'rejected') {
         // You might still want to allow viewing approved listings, but this ensures it's only the relevant ones.
    }

    return view('admin.property_moderation.show', compact('property'));
    }

    public function AdminIndex(){
        $properties = Property::where('moderation_status', 'pending')
                              ->with('user')
                              ->orderBy('created_at', 'asc')
                              ->paginate(20);

        return view('admin.property_moderation.index', compact('properties'));
    }

    public function approve(Property $property)
    {
        // Only allow status change if the property is currently pending moderation
        if ($property->moderation_status !== 'pending') {
            return back()->with('error', 'Property status is already set to ' . $property->moderation_status . '.');
        }

        // Update the new moderation column
        $property->update(['moderation_status' => 'approved']);

        return redirect()->route('moderation.index')->with('success', 'Property "' . $property->title . '" has been successfully approved.');
    }

    /**
     * Reject the property.
     */
    public function reject(Request $request, Property $property)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        if ($property->moderation_status !== 'pending') {
            return back()->with('error', 'Property status is already set to ' . $property->moderation_status . '.');
        }

        // Update the new moderation column
        $property->update(['moderation_status' => 'rejected']);

        return redirect()->route('moderation.index')->with('success', 'Property "' . $property->title . '" has been rejected.');
    }
}
