<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class DatabaseConnectionTest extends TestCase
{
    use RefreshDatabase;

    
    /**
     * Test: Database is properly connected
     */
    public function test_database_connection(): void
    {
        $connection = DB::connection();
        $this->assertInstanceOf(SQLiteConnection::class, $connection);
    }

    /**
     * Test: Basic queries work for the database
     */
    public function test_database_can_execute_basic_query(): void
    {
        $result = DB::select('SELECT 1 as test');  
        $this->assertEquals(1, $result[0]->test);  
    }  

    /**
     * Test: Database tables exist
     */
    public function test_database_tables_exist()  
    {  
        $this->assertTrue(Schema::hasTable('users'));  
        $this->assertTrue(Schema::hasTable('pets'));  
        $this->assertTrue(Schema::hasTable('adoption_requests'));  
    }  

    /**
     * Test: Database records can be both created and retrieved
     */
    public function test_can_create_and_retrieve_database_records()  
    {  
        $user = User::factory()->create();  
        $this->assertDatabaseHas('users', ['id' => $user->id]);  
    } 

}
