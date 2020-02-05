<?php

namespace Tests\Feature;

use Tests\TestCase;

class MainPageTest extends TestCase
{
    /** @test */
    public function access_to_the_root_is_redirect_to_the_import_page()
    {
        $this->get('/')
            ->assertRedirect('files/create');
    }

    /** @test */
    public function it_renders_the_form_correctly()
    {
        $this->get('files/create')
            ->assertSee('Import a new file')
            ->assertSee('Process');
    }
}
