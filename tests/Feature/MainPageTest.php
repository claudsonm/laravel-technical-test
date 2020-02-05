<?php

namespace Tests\Feature;

use Tests\TestCase;

class MainPageTest extends TestCase
{
    /** @test */
    public function access_to_the_root_is_redirect_to_the_import_page()
    {
        $this->get('/')
            ->assertRedirect('files/new');
    }

    /** @test */
    public function it_renders_the_form_correctly()
    {
        $this->get('files/new')
            ->assertSee('Import a new file')
            ->assertSee('Process');
    }
}
