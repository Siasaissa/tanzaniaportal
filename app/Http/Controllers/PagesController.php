<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Validation\Rules\Unique;

class PagesController extends Controller
{
    public function page(){
        return view('admin.company');
    }

    public function quote(){
        return view('company.quotation.index');
    }

public function welcome()
{
    // Get distinct names only (returns only name column)
    $company = Company::distinct('name')->pluck('name');
    
    // If you need full records, you need to join or use subquery
    $company = Company::whereIn('id', function($query) {
        $query->selectRaw('MIN(id)')
              ->from('companies')
              ->groupBy('name');
    })->get();
    
    return view('welcome', compact('company'));
}

public function staff(Company $company)
{
    if ($company->status !== 'active') {
        abort(404);
    }

    return view('auth.slogin', compact('company'));
}
}
