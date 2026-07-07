<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeoRedirectRequest;
use App\Http\Requests\UpdateSeoRedirectRequest;
use App\Models\SeoRedirect;
use App\Services\SeoRedirectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoRedirectController extends Controller
{
    protected SeoRedirectService $redirectService;

    public function __construct(SeoRedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    /**
     * Display redirection rules.
     */
    public function index(): View
    {
        $redirects = SeoRedirect::paginate(20);
        return view('admin.seo.redirects.index', compact('redirects'));
    }

    /**
     * Show route redirection wizard.
     */
    public function create(): View
    {
        return view('admin.seo.redirects.create');
    }

    /**
     * Store a redirect entry.
     */
    public function store(StoreSeoRedirectRequest $request): RedirectResponse
    {
        try {
            $this->redirectService->createRedirect($request->validated());
            return redirect()->route('admin.seo.redirects.index')
                ->with('success', 'URL redirection rule configured successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['target_url' => $e->getMessage()]);
        }
    }

    /**
     * Show rule editor.
     */
    public function edit(SeoRedirect $redirect): View
    {
        return view('admin.seo.redirects.edit', compact('redirect'));
    }

    /**
     * Update redirection configuration.
     */
    public function update(UpdateSeoRedirectRequest $request, SeoRedirect $redirect): RedirectResponse
    {
        try {
            $this->redirectService->updateRedirect($redirect, $request->validated());
            return redirect()->route('admin.seo.redirects.index')
                ->with('success', 'URL redirection rule updated successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['target_url' => $e->getMessage()]);
        }
    }

    /**
     * Delete redirect.
     */
    public function destroy(SeoRedirect $redirect): RedirectResponse
    {
        $redirect->delete();
        $this->redirectService->clearRedirectCache();

        return redirect()->route('admin.seo.redirects.index')
            ->with('success', 'URL redirection rule deleted successfully.');
    }

    /**
     * Test redirection routing targets.
     */
    public function test(Request $request): View
    {
        $path = $request->input('path');
        $match = null;

        if ($path) {
            $match = $this->redirectService->findRedirectMatch($path);
        }

        return view('admin.seo.redirects.test', compact('path', 'match'));
    }
}
