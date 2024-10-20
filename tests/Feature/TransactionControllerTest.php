<?php

use App\Models\User;
use App\Models\Account;
use App\Domain\Services\ATMService; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    // use RefreshDatabase;

    protected $user;
    protected $account;
    protected $atmServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->account = Account::factory()->create([
            'user_id' => $this->user->id,
            'balance' => 500, 
        ]);

        $this->atmServiceMock = Mockery::mock(ATMService::class);
        $this->app->instance(ATMService::class, $this->atmServiceMock);
    }

    public function test_withdraw_successful()
    {
        $this->atmServiceMock->shouldReceive('calculateNotes')
            ->with(100) 
            ->andReturn(['50' => 2]); 
    
        $response = $this->actingAs($this->user)->post('/api/withdraw', [
            'account_id' => $this->account->id,
            'amount' => 100, 
        ]);
    
        $response->assertStatus(200);
    
        $this->assertEquals(400, $this->account->fresh()->balance);
    
        $this->assertDatabaseHas('transactions', [
            'account_id' => $this->account->id,
            'amount' => -100, 
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close(); 
        parent::tearDown();
    }
}
