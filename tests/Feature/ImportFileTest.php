<?php

namespace Tests\Feature;

use App\Phone;
use App\Person;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImportFileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake();
    }

    /** @test */
    public function it_requires_a_document()
    {
        $this->post('files')->assertSessionHasErrors('document');
    }

    /** @test */
    public function the_document_must_be_a_xml()
    {
        $pdf = UploadedFile::fake()->create('dummy.pdf');

        $this->post('files', ['document' => $pdf])
            ->assertSessionHasErrors(['document' => 'The document must be a file of type: xml.']);
    }

    /** @test */
    public function a_valid_xml_containing_unrecognized_values_returns_an_error()
    {
        $content = file_get_contents(resource_path('fixtures/unrecognized.xml'));
        $badXml = UploadedFile::fake()->createWithContent('unrecognized.xml', $content);

        $this->post('files', ['document' => $badXml])
            ->assertSessionHas('flash_notification.0.level', 'danger')
            ->assertSessionHas('flash_notification.0.message', "There are no handlers for the given file.");
    }

    /** @test */
    public function it_returns_an_error_if_the_xml_is_invalid()
    {
        $content = file_get_contents(resource_path('fixtures/bad_shiporders.xml'));
        $badXml = UploadedFile::fake()->createWithContent('bad_shiporders.xml', $content);

        $this->post('files', ['document' => $badXml])
            ->assertSessionHas('flash_notification.0.level', 'danger')
            ->assertSessionHas('flash_notification.0.message', "The XML is invalid: Opening and ending tag mismatch: items line 37 and shiporder\n");
    }

    /** @test */
    public function it_imports_successfully_an_valid_file_person_file()
    {
        $this->performPersonFileUpload()
            ->assertSessionHas('flash_notification.0.level', 'success')
            ->assertSessionHas('flash_notification.0.message', "File processed: 3 new persons imported and 0 persons with error.");

        $this->assertEquals(3, Person::count());
        $this->assertDatabaseHas('persons', ['id' => 1, 'name' => 'Name 1']);
        $this->assertDatabaseHas('persons', ['id' => 2, 'name' => 'Name 2']);
        $this->assertDatabaseHas('persons', ['id' => 3, 'name' => 'Name 3']);
        $this->assertEquals(5, Phone::count());
        foreach (['2345678', '1234567', '4444444', '7777777', '8888888'] as $number) {
            $this->assertDatabaseHas('phones', compact('number'));
        }
    }

    /** @test */
    public function a_file_already_imported_wont_make_any_changes()
    {
        $this->performPersonFileUpload();
        flash()->clear();

        $this->performPersonFileUpload()
            ->assertSessionHas('flash_notification.0.level', 'warning')
            ->assertSessionHas('flash_notification.0.message', "File processed: 0 new persons imported and 3 persons with error.");
        $this->assertEquals(3, Person::count());
        $this->assertEquals(5, Phone::count());
    }

    /**
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function performPersonFileUpload(): \Illuminate\Foundation\Testing\TestResponse
    {
        $content = file_get_contents(resource_path('fixtures/people.xml'));
        $people = UploadedFile::fake()->createWithContent('people.xml', $content);

        return $this->post('files', ['document' => $people]);
    }
}
