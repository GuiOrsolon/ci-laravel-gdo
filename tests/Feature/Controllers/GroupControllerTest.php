<?php

namespace Tests\Feature\Controllers;

use App\Models\Group;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    //use DatabaseMigrations;
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndexMethodWithExpectedAllResults()
    {
        Group::factory(10)->create();

        $response = $this->get('/api/groups');
        $response->assertStatus(200);

        $response->assertJsonCount(10);

        $response->assertJsonStructure([
            '*' =>[
                'id',
                'name',
                'cnpj',
                'type',
                'active',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function testStoreMethodWithExpectedSuccessResult(){
        $data = [
            'code'   => 200,
            'name'   => 'FBS Sistemas',
            'cnpj'   => '66.545.911/0001-02',
            'type'   => 1,
            'active' => 1
        ];

        $response = $this->post('/api/groups', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
                'id',
                'name',
                'cnpj',
                'type',
                'active',
                'created_at',
                'updated_at'
        ]);

        $responseArray = json_decode($response->content(), true);

        $this->assertArrayHasKey('id',$responseArray);
        $this->assertEquals(1, $responseArray['id']);

        foreach($data as $key => $value){
            if($key == 'cnpj'){
                $value = preg_replace('/\D/','',$value);
            }
            $this->assertEquals($value, $responseArray[$key]);
        }

        
    }

    public function testStoreMethodWithExpectedRequiredFieldValidation(){
        $data = [];
        $response = $this->post('/api/groups', $data);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'name',
            'cnpj'
        ]);

        $response->assertExactJson([
            'name' => ['O campo é obrigatório!'],
            'cnpj' => ['O campo é obrigatório!']
        ]);
    }

}
