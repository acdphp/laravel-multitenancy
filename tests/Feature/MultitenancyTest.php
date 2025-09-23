<?php

use Pest\Expectation;
use Workbench\Database\Factories\CompanyFactory;
use Workbench\Database\Factories\ProductFactory;
use Workbench\Database\Factories\SiteFactory;
use Workbench\Database\Factories\UserFactory;

test('allow manually setting tenant', function () {
    $res = $this->post('users/register', [
        'name' => 'test',
        'email' => 'test@email.com',
        'password' => 'test',
        'company' => [
            'name' => 'Test',
        ],
    ])
        ->assertCreated();

    $this->assertDatabaseHas('users', [
        'id' => $res->json('id'),
        'company_id' => $res->json('company_id'),
    ]);
});

test('automatically set tenant from request', function () {
    $user = UserFactory::new()->make();

    $res = $this->actingAs($user)
        ->post('sites', [
            'foo' => 'bar',
        ])
        ->assertCreated();

    $this->assertDatabaseHas('sites', [
        'id' => $res->json('id'),
        'company_id' => $res->json('company_id'),
    ]);
});

test('should scope resource list from requesting id', function () {
    $user = UserFactory::new()->make();
    SiteFactory::new(['company_id' => $user->company_id])->count(3)->create();
    SiteFactory::new(['company_id' => CompanyFactory::new()->create()->id])->count(3)->create();

    $res = $this->actingAs($user)
        ->get('sites')
        ->assertOk()
        ->assertJsonCount(3);

    expect($res->json())
        ->each(fn (Expectation $data) => $data->toHaveKey('company_id', $user->company_id));
});

test('should deep scope resource list from requesting id', function () {
    $user = UserFactory::new()->make();
    $companySite = SiteFactory::new(['company_id' => $user->company_id])->create();
    $otherSite = SiteFactory::new(['company_id' => CompanyFactory::new()->create()->id])->create();
    ProductFactory::new(['site_id' => $companySite->id])->count(3)->create();
    ProductFactory::new(['site_id' => $otherSite->id])->count(3)->create();

    $res = $this->actingAs($user)
        ->get('products')
        ->assertOk()
        ->assertJsonCount(3);

    expect($res->json())
        ->each(fn (Expectation $data) => $data->toHaveKey('site_id', $companySite->id));
});

test('should scope a resource from requesting id', function () {
    $user = UserFactory::new()->make();
    $s1 = SiteFactory::new(['company_id' => $user->company_id])->create();
    $s2 = SiteFactory::new(['company_id' => CompanyFactory::new()->create()->id])->create();

    $this->actingAs($user)
        ->get('sites/'.$s1->id)
        ->assertOk();

    $this->actingAs($user)
        ->get('sites/'.$s2->id)
        ->assertNotFound();
});

test('allow scope bypass resources from requesting id', function () {
    $user = UserFactory::new()->make();
    SiteFactory::new(['company_id' => $user->company_id])->count(3)->create();
    SiteFactory::new(['company_id' => CompanyFactory::new()->create()->id])->count(3)->create();

    $this->actingAs($user)
        ->get('sites/all')
        ->assertOk()
        ->assertJsonCount(6);
});

test('allow scope bypass resource', function () {
    $user = UserFactory::new()->make();
    $ownSite = SiteFactory::new(['company_id' => $user->company_id])->create();
    $otherCompanySite = SiteFactory::new(['company_id' => CompanyFactory::new()->create()->id])->create();

    // Scope bypass works on self
    $this->actingAs($user)
        ->get('sites/' . $ownSite->id . '/by-pass-example')
        ->assertOk();

    // Scope bypass works on other
    $this->actingAs($user)
        ->get('sites/' . $otherCompanySite->id . '/by-pass-example')
        ->assertOk();

    // Fails on other without bypass
    $this->actingAs($user)
        ->get('sites/' . $otherCompanySite->id)
        ->assertOk();
});
