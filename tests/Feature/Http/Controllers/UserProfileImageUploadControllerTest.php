<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileImageUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upload_image()
    {
        $user = User::factory()->employee()->create();

        Storage::fake('pictures');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)
            ->postJson('api/user/profile/image-upload', [
                'picture' => $file,
            ]);

        $response
            ->assertOk();

        $this->assertEquals($file->hashName(), $response->json('image'));
        $this->assertDatabaseHas('users', ['profile_photo_path' => $file->hashName()]);
    }

    /** @test */
    public function user_cant_upload_file_if_it_is_not_image()
    {
        $user = User::factory()->employee()->create();

        Storage::fake('profile_pics');

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($user)
            ->postJson('api/user/profile/image-upload', [
                'picture' => $file,
            ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('picture')
            ->assertJsonValidationErrorFor('picture')
            ->assertJsonValidationErrors(['picture' => 'The picture must be an image.']);

        Storage::disk('profile_pics')->assertMissing($file->hashName());
    }
}
