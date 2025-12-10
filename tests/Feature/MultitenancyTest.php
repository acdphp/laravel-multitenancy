<?php

namespace Acdphp\Multitenancy\Tests\Feature;

use Acdphp\Multitenancy\Tests\TestCase;
use Workbench\Database\Factories\CompanyFactory;
use Workbench\Database\Factories\ProductFactory;
use Workbench\Database\Factories\SiteFactory;
use Workbench\Database\Factories\UserFactory;

class MultitenancyTest extends TestCase
{
    public function test_allow_manually_setting_tenant(): void
    {
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
    }

    public function test_automatically_set_tenant_from_request(): void
    {
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
    }

    public function test_should_scope_resource_list_from_requesting_id(): void
    {
        $user = UserFactory::new()->make();
        SiteFactory::new(['company_id' => $user->company_id])->count(3)->create();
        SiteFactory::new(['company_id' => CompanyFactory::new()->create()->id])->count(3)->create();

        $res = $this->actingAs($user)
            ->get('sites')
            ->assertOk()
            ->assertJsonCount(3);

        $data = $res->json();
        foreach ($data as $item) {
            $this->assertArrayHasKey('company_id', $item);
            $this->assertEquals($user->company_id, $item['company_id']);
        }
    }

    public function test_should_deep_scope_resource_list_from_requesting_id(): void
    {
        $user = UserFactory::new()->make();
        $companySite = SiteFactory::new(['company_id' => $user->company_id])->create();
        $otherSite = SiteFactory::new(['company_id' => CompanyFactory::new()->create()->id])->create();
        ProductFactory::new(['site_id' => $companySite->id])->count(3)->create();
        ProductFactory::new(['site_id' => $otherSite->id])->count(3)->create();

        $res = $this->actingAs($user)
            ->get('products')
            ->assertOk()
            ->assertJsonCount(3);

        $data = $res->json();
        foreach ($data as $item) {
            $this->assertArrayHasKey('site_id', $item);
            $this->assertEquals($companySite->id, $item['site_id']);
        }
    }

    public function test_should_scope_a_resource_from_requesting_id(): void
    {
        $user = UserFactory::new()->make();
        $s1 = SiteFactory::new(['company_id' => $user->company_id])->create();
        $s2 = SiteFactory::new(['company_id' => CompanyFactory::new()->create()->id])->create();

        $this->actingAs($user)
            ->get('sites/'.$s1->id)
            ->assertOk();

        $this->actingAs($user)
            ->get('sites/'.$s2->id)
            ->assertNotFound();
    }

    public function test_allow_scope_bypass_resources_from_requesting_id(): void
    {
        $user = UserFactory::new()->make();
        SiteFactory::new(['company_id' => $user->company_id])->count(3)->create();
        SiteFactory::new(['company_id' => CompanyFactory::new()->create()->id])->count(3)->create();

        $this->actingAs($user)
            ->get('sites/all')
            ->assertOk()
            ->assertJsonCount(6);
    }

    public function test_allow_scope_bypass_resource(): void
    {
        $user = UserFactory::new()->make();
        $ownSite = SiteFactory::new(['company_id' => $user->company_id])->create();
        $otherCompanySite = SiteFactory::new(['company_id' => CompanyFactory::new()->create()->id])->create();

        // Scope bypass works on self
        $this->actingAs($user)
            ->get('sites/'.$ownSite->id.'/by-pass-example')
            ->assertOk();

        // Scope bypass works on other
        $this->actingAs($user)
            ->get('sites/'.$otherCompanySite->id.'/by-pass-example')
            ->assertOk();

        // Fails on other without bypass
        $this->actingAs($user)
            ->get('sites/'.$otherCompanySite->id)
            ->assertOk();
    }
}
