<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('media library uses ULID primary keys and morphs', function () {
    // 1. Create a user
    $user = User::factory()->create();
    expect($user->id)->toBeString()->toHaveLength(26);

    // 2. Add media item from a string (mock file)
    $media = $user->addMediaFromString('fake image data')
        ->usingFileName('avatar.png')
        ->toMediaCollection('avatars');

    // 3. Assert media was created and has a 26-char ULID primary key
    expect($media->id)->toBeString()->toHaveLength(26);

    // 4. Assert polymorphic fields use ULID
    expect($media->model_type)->toBe(User::class);
    expect($media->model_id)->toBe($user->id);

    // 5. Retrieve media back and verify it matches
    $retrievedMedia = $user->getFirstMedia('avatars');
    expect($retrievedMedia)->not->toBeNull();
    expect($retrievedMedia->id)->toBe($media->id);
    expect($retrievedMedia->model_id)->toBe($user->id);
});
