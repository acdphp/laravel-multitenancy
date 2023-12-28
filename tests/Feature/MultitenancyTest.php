<?php

use Pest\Expectation;
use Workbench\Database\Factories\CompanyFactory;
use Workbench\Database\Factories\SomethingFactory;
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
        ->post('somethings', [
            'foo' => 'bar',
        ])
        ->assertCreated();

    $this->assertDatabaseHas('somethings', [
        'id' => $res->json('id'),
        'company_id' => $res->json('company_id'),
    ]);
});

test('should scope resource list from requesting id', function () {
    $user = UserFactory::new()->make();
    SomethingFactory::new(['company_id' => $user->company_id])->count(3)->create();
    SomethingFactory::new(['company_id' => CompanyFactory::new()->create()->id])->count(3)->create();

    $res = $this->actingAs($user)
        ->get('somethings')
        ->assertOk()
        ->assertJsonCount(3);

    expect($res->json())
        ->each(fn (Expectation $data) => $data->toHaveKey('company_id', $user->company_id));
});

test('should scope a resource from requesting id', function () {
    $user = UserFactory::new()->make();
    $s1 = SomethingFactory::new(['company_id' => $user->company_id])->create();
    $s2 = SomethingFactory::new(['company_id' => CompanyFactory::new()->create()->id])->create();

    $this->actingAs($user)
        ->get('somethings/'.$s1->id)
        ->assertOk();

    $this->actingAs($user)
        ->get('somethings/'.$s2->id)
        ->assertNotFound();
});

test('allow scope bypass resources from requesting id', function () {
    $user = UserFactory::new()->make();
    SomethingFactory::new(['company_id' => $user->company_id])->count(3)->create();
    SomethingFactory::new(['company_id' => CompanyFactory::new()->create()->id])->count(3)->create();

    $this->actingAs($user)
        ->get('somethings/all')
        ->assertOk()
        ->assertJsonCount(6);
});
