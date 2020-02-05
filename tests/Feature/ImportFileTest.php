<?php

namespace Tests\Feature;

use App\Order;
use App\Person;
use App\Phone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImportFileTest extends TestCase
{
    use RefreshDatabase;

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
        $unrecognizedXml = $this->makeDummyUploadedFileFrom('unrecognized.xml');

        $this->post('files', ['document' => $unrecognizedXml])
            ->assertSessionHas('flash_notification.0.level', 'danger')
            ->assertSessionHas('flash_notification.0.message', 'There are no handlers for the given file.');
    }

    /** @test */
    public function it_returns_an_error_if_the_xml_is_invalid()
    {
        $badXml = $this->makeDummyUploadedFileFrom('bad_shiporders.xml');

        $this->post('files', ['document' => $badXml])
            ->assertSessionHas('flash_notification.0.level', 'danger')
            ->assertSessionHas('flash_notification.0.message', "The XML is invalid: Opening and ending tag mismatch: items line 37 and shiporder\n");
    }

    /** @test */
    public function it_imports_successfully_a_valid_person_file()
    {
        $this->performPersonFileUpload()
            ->assertSessionHas('flash_notification.0.level', 'success')
            ->assertSessionHas('flash_notification.0.message', 'File processed: 3 new persons imported and 0 persons with error.');

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
    public function a_person_file_already_imported_wont_make_any_changes()
    {
        $this->performPersonFileUpload();
        flash()->clear();

        $this->performPersonFileUpload()
            ->assertSessionHas('flash_notification.0.level', 'warning')
            ->assertSessionHas('flash_notification.0.message', 'File processed: 0 new persons imported and 3 persons with error.');
        $this->assertEquals(3, Person::count());
        $this->assertEquals(5, Phone::count());
    }

    /** @test */
    public function it_imports_successfully_a_valid_orders_file()
    {
        create(Person::class, [], 3);
        $this->performOrdersFileUpload()
            ->assertSessionHas('flash_notification.0.level', 'success')
            ->assertSessionHas('flash_notification.0.message', 'File processed: 3 new orders imported and 0 orders with errors.');

        $this->assertDatabaseHas('orders', ['id' => 1, 'destination' => 'Name 1', 'person_id' => 1]);
        $this->assertDatabaseHas('orders', ['id' => 2, 'destination' => 'Name 2', 'person_id' => 2]);
        $this->assertDatabaseHas('orders', ['id' => 3, 'destination' => 'Name 3', 'person_id' => 3]);
        $this->assertEquals(1, Order::find(1)->items()->count());
        $this->assertEquals(1, Order::find(2)->items()->count());
        $this->assertEquals(2, Order::find(3)->items()->count());
    }

    protected function performPersonFileUpload(): \Illuminate\Foundation\Testing\TestResponse
    {
        $people = $this->makeDummyUploadedFileFrom('people.xml');

        return $this->post('files', ['document' => $people]);
    }

    protected function performOrdersFileUpload(): \Illuminate\Foundation\Testing\TestResponse
    {
        $orders = $this->makeDummyUploadedFileFrom('shiporders.xml');

        return $this->post('files', ['document' => $orders]);
    }

    /**
     * @param string $file the filename located at resources/fixtures folder
     */
    protected function makeDummyUploadedFileFrom(string $file): \Illuminate\Http\Testing\File
    {
        $content = file_get_contents(resource_path("fixtures/{$file}"));

        return UploadedFile::fake()->createWithContent("{$file}", $content);
    }
}
