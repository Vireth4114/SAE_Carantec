<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\AcnDivesController;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Testing\TestView;


class DivesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_is_authenticated()
    {

        $response = $this->followingRedirects()->post('login', [
            'MEM_NUM_MEMBER' => 'U-50-2345678',
            'password' => 'MarianaTrench']);

        $this -> assertAuthenticated();

        //$response->assertSee('Août');
        
    }
}
