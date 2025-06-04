<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Name;

class AddNameTest extends TestCase
{
    // public function test_add_name_successfully()
    // {

    //     $name = 'เนย1';


    //     $response = $this->post('/addname', [
    //         'name' => $name,
    //     ]);

    //     // ตรวจสอบว่ามีชื่อในฐานข้อมูลแล้ว
    //     $this->assertDatabaseHas('names', [
    //         'name' => $name,
    //     ]);

    //     // ตรวจสอบว่า redirect กลับหลัง submit
    //     $response->assertRedirect();

    // }

    public function test_add_name_validation_error()
    {
        $response = $this->post('/addname', [
            'name' => '', // เว้นว่างเพื่อให้เกิด validation error
        ]);

        // คาดว่าจะเกิด validation error
        $response->assertSessionHasErrors('name');
    }
}
