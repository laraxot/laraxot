<?php

declare(strict_types=1);

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('employee::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        /** @var view-string $view */
        $view = 'employee::create';

        return view($view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        // TODO: Implement store logic
        return response()->noContent();
    }

    /**
     * Show the specified resource.
     */
    public function show(int $id): View
    {
        /** @var view-string $view */
        $view = 'employee::show';

        return view($view);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        /** @var view-string $view */
        $view = 'employee::edit';

        return view($view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): Response
    {
        // TODO: Implement update logic
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): Response
    {
        // TODO: Implement destroy logic
        return response()->noContent();
    }
}
