<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Name;

class AddNameTest extends TestCase
{


    public function test_add_name_validation_error()
    {
        //ลองส่งข้อมูลว่างไป
        $response = $this->post('/addname', [
            'name' => '', // เว้นว่างเพื่อให้เกิด validation error
        ]);

        // คิดว่าจะเกิด validation error
        $response->assertSessionHasErrors('name');
    }
}
