<?php

namespace Tests\Feature\API;

use App\User;
use App\Menuplan;
use App\Purchase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PurchaseTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_get_the_purchases_of_his_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $ownPurchases = factory(Purchase::class, 3)->create(['menuplan_id' => $menuplan->id]);
        $otherPurchases = factory(Purchase::class, 2)->create();

        $getDateAndTimeFromPurchase = function ($purchase) {
            return [
                'time' => $purchase->time->format('H:i'),
                'date' => $purchase->time->format('Y-m-d'),
            ];
        };

        $this->actingAs($user)
            ->get('/api/menuplan/'.$menuplan->id.'/purchases')
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJson([
                $getDateAndTimeFromPurchase($ownPurchases[0]),
                $getDateAndTimeFromPurchase($ownPurchases[1]),
                $getDateAndTimeFromPurchase($ownPurchases[2]),
            ])->assertJsonMissing([
                $getDateAndTimeFromPurchase($otherPurchases[0]),
                $getDateAndTimeFromPurchase($otherPurchases[1]),
            ]);
    }

    /** @test */
    public function a_user_can_create_a_purchase()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $validPurchaseData = $this->getValidPurchaseData($menuplan);

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/purchases', $validPurchaseData)
            ->assertStatus(201)
            ->assertJson($validPurchaseData);

        $this->assertDatabaseHasPurchase($validPurchaseData);
    }

    /** @test */
    public function a_user_can_only_add_a_purchases_to_his_menuplans()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create();
        $validPurchaseData = $this->getValidPurchaseData($menuplan);

        $this->actingAs($user)
            ->post('/api/menuplan/'.$menuplan->id.'/purchases', $validPurchaseData)
            ->assertStatus(403);

        $this->assertDatabaseMissingPurchase($validPurchaseData);
    }

    /** @test */
    public function a_user_can_get_a_purchase()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $purchase = factory(Purchase::class)->create(['menuplan_id' => $menuplan->id]);

        $this->actingAs($user)
            ->get('/api/purchase/'.$purchase->id)
            ->assertStatus(200)
            ->assertJson([
                'menuplan_id' => $menuplan->id
            ]);
    }

    /** @test */
    public function a_user_can_get_only_his_purchases()
    {
        $user = factory(User::class)->create();
        $purchase = factory(Purchase::class)->create();
    
        $this->actingAs($user)
                ->get('/api/purchase/'.$purchase->id)
                ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_update_a_purchase()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $purchase = factory(Purchase::class)->create(['menuplan_id' => $menuplan->id]);
        $validPurchaseData = $this->getValidPurchaseData($menuplan);

        $this->assertDatabaseMissingPurchase($validPurchaseData);

        $this->actingAs($user)
            ->put('/api/purchase/'.$purchase->id, $validPurchaseData)
            ->assertStatus(200)
            ->assertJson($validPurchaseData);

        $this->assertDatabaseHasPurchase($validPurchaseData);
    }

    /** @test */
    public function a_user_can_update_only_his_purchases()
    {
        $user = factory(User::class)->create();
        $purchase = factory(Purchase::class)->create();
        $validPurchaseData = $this->getValidPurchaseData($purchase->menuplan);

        $this->actingAs($user)
            ->put('/api/purchase/'.$purchase->id, $validPurchaseData)
            ->assertStatus(403);

        $this->assertDatabaseMissingPurchase($validPurchaseData);
    }

    /** @test */
    public function a_user_can_delete_a_purchase()
    {
        $user = factory(User::class)->create();
        $menuplan = factory(Menuplan::class)->create(['user_id' => $user->id]);
        $purchase = factory(Purchase::class)->create(['menuplan_id' => $menuplan->id]);

        $this->assertDatabaseHas('purchases', ['id' => $purchase->id]);

        $this->actingAs($user)
            ->delete('/api/purchase/'.$purchase->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('purchases', ['id' => $purchase->id]);
    }

    /** @test */
    public function a_user_can_delete_only_his_purchases()
    {
        $user = factory(User::class)->create();
        $purchase = factory(Purchase::class)->create();

        $this->assertDatabaseHas('purchases', ['id' => $purchase->id]);

        $this->actingAs($user)
        ->delete('/api/purchase/'.$purchase->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('purchases', ['id' => $purchase->id]);
    }

    private function getValidPurchaseData(Menuplan $menuplan)
    {
        return [
            'date' => $menuplan->start->format('Y-m-d'),
            'time' => '09:00',
        ];
    }

    private function assertDatabaseHasPurchase($purchaseData)
    {
        $this->assertDatabaseHas('purchases', [
            'time' => $purchaseData['date'].' '.$purchaseData['time'].':00',
        ]);
    }

    private function assertDatabaseMissingPurchase($purchaseData)
    {
        $this->assertDatabaseMissing('purchases', [
            'time' => $purchaseData['date'].' '.$purchaseData['time'].':00',
        ]);
    }
}
