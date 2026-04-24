<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AnakYatimController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;

class AnakYatimControllerHelperTest extends TestCase
{
    use RefreshDatabase;

    protected AnakYatimController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AnakYatimController();
    }

    /**
     * Test handlePhotoUpload saves photo with unique name.
     */
    public function test_handle_photo_upload_saves_with_unique_name(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        $path = $this->invokePrivateMethod('handlePhotoUpload', [$photo]);

        $this->assertNotNull($path);
        $this->assertStringStartsWith('photos/', $path);
        $this->assertStringEndsWith('test.jpg', $path);
        Storage::disk('public')->assertExists($path);
    }

    /**
     * Test handlePhotoUpload returns correct path format.
     */
    public function test_handle_photo_upload_returns_correct_path_format(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->create('myimage.png', 100, 'image/png');

        $path = $this->invokePrivateMethod('handlePhotoUpload', [$photo]);

        $this->assertMatchesRegularExpression('/^photos\/\d+_myimage\.png$/', $path);
    }

    /**
     * Test handlePhotoUpload with different file types.
     */
    public function test_handle_photo_upload_with_different_file_types(): void
    {
        Storage::fake('public');

        $fileTypes = [
            ['name' => 'photo.jpg', 'mime' => 'image/jpeg'],
            ['name' => 'photo.jpeg', 'mime' => 'image/jpeg'],
            ['name' => 'photo.png', 'mime' => 'image/png'],
        ];

        foreach ($fileTypes as $fileType) {
            $photo = UploadedFile::fake()->create($fileType['name'], 100, $fileType['mime']);
            $path = $this->invokePrivateMethod('handlePhotoUpload', [$photo]);

            $this->assertStringEndsWith($fileType['name'], $path);
            Storage::disk('public')->assertExists($path);
        }
    }

    /**
     * Test deletePhoto removes existing file.
     */
    public function test_delete_photo_removes_existing_file(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');
        $photoPath = $photo->storeAs('photos', 'test_photo.jpg', 'public');

        Storage::disk('public')->assertExists($photoPath);

        $this->invokePrivateMethod('deletePhoto', [$photoPath]);

        Storage::disk('public')->assertMissing($photoPath);
    }

    /**
     * Test deletePhoto handles null path gracefully.
     */
    public function test_delete_photo_handles_null_path(): void
    {
        Storage::fake('public');

        // Should not throw exception
        $this->invokePrivateMethod('deletePhoto', [null]);

        $this->assertTrue(true); // If we reach here, no exception was thrown
    }

    /**
     * Test deletePhoto handles non-existent file gracefully.
     */
    public function test_delete_photo_handles_non_existent_file(): void
    {
        Storage::fake('public');

        $nonExistentPath = 'photos/non_existent.jpg';

        // Should not throw exception
        $this->invokePrivateMethod('deletePhoto', [$nonExistentPath]);

        $this->assertTrue(true); // If we reach here, no exception was thrown
    }

    /**
     * Test handlePhotoUpload creates filenames with timestamp prefix.
     */
    public function test_handle_photo_upload_creates_filenames_with_timestamp_prefix(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->create('same.jpg', 100, 'image/jpeg');

        $path = $this->invokePrivateMethod('handlePhotoUpload', [$photo]);

        // Verify the path contains a timestamp prefix (numeric value followed by underscore)
        $this->assertMatchesRegularExpression('/^photos\/\d+_same\.jpg$/', $path);
        Storage::disk('public')->assertExists($path);
    }

    /**
     * Helper method to invoke private methods for testing.
     */
    protected function invokePrivateMethod(string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(AnakYatimController::class);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->controller, $parameters);
    }
}
