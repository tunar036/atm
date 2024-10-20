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

        // İstifadəçi yaradın
        $this->user = User::factory()->create();

        // Hesab yaradın
        $this->account = Account::factory()->create([
            'user_id' => $this->user->id,
            'balance' => 500, // Başlanğıc balans
        ]);

        // ATMService mockunu yaradın
        $this->atmServiceMock = Mockery::mock(ATMService::class);
        $this->app->instance(ATMService::class, $this->atmServiceMock);
    }

    public function test_withdraw_successful()
    {
        // Mock-nu konfiqurasiya edin
        $this->atmServiceMock->shouldReceive('calculateNotes')
            ->with(100) // Çıxış məbləği
            ->andReturn(['50' => 2]); // Gözlənilən nəticə
    
        // İstifadəçi kimi avtorizasiya olun
        $response = $this->actingAs($this->user)->post('/api/withdraw', [
            'account_id' => $this->account->id, // Yaradılan hesabın id-si
            'amount' => 100, // Çıxış məbləği
        ]);
    
        // Cavab statusunun uğurlu olduğunu təsdiqləyirik
        $response->assertStatus(200);
    
        // Balansın yeniləndiyini yoxlayırıq
        $this->assertEquals(400, $this->account->fresh()->balance);
    
        // Transaction bazasında qeyd olunub-olunmadığını yoxlayırıq
        $this->assertDatabaseHas('transactions', [
            'account_id' => $this->account->id,
            'amount' => -100, // Mənfi çıxış miqdarı
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close(); // Mockery-ni bağlayın
        parent::tearDown();
    }
}
