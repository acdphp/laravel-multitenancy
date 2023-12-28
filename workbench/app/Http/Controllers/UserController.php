<?php

namespace Workbench\App\Http\Controllers;

use Acdphp\Multitenancy\Facades\Tenancy;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workbench\App\Models\Company;
use Workbench\App\Models\User;

class UserController extends Controller
{
    public function register(Request $request): User
    {
        // Create company and set as tenant
        $company = Company::create($request->input('company'));

        // Set tenant from created company
        Tenancy::setTenantId($company->id);

        // Then proceed to create a user
        return User::create($request->except('company'));
    }
}
