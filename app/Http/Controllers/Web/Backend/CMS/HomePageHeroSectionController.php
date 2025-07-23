<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\CMS\HomePageHeroSectionRequest;
use App\Services\Web\Backend\CMS\HomePageHeroSectionService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HomePageHeroSectionController extends Controller {
    protected HomePageHeroSectionService $heroSectionService;

    public function __construct(HomePageHeroSectionService $heroSectionService) {
        $this->heroSectionService = $heroSectionService;
    }

    /**
     * Display the hero section of the home page.
     *
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function index(): RedirectResponse | View {
        try {
            $heroSection = $this->heroSectionService->getOrCreateHeroSection();
            return view('backend.layouts.cms.home-page.hero-section', compact('heroSection'));
        } catch (Exception $e) {
            Log::error('Hero Section Index Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('t-error', 'Failed to load hero section: ' . $e->getMessage());
        }
    }

    /**
     * Update the hero section of the home page.
     *
     * @param HomePageHeroSectionRequest $request
     * @return RedirectResponse
     */
    public function update(HomePageHeroSectionRequest $request): RedirectResponse {
        try {
            // Get validated data including image
            $validatedData = $request->validated();

            // Add the uploaded file to validated data if present
            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image');
            }

            // Add remove_image flag
            $validatedData['remove_image'] = $request->boolean('remove_image');

            $this->heroSectionService->updateHeroSection($validatedData);

            return redirect()->route('home-page.hero-section.index')
                ->with('t-success', 'Hero Section updated successfully.');

        } catch (Exception $e) {
            Log::error('Hero Section Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('t-error', 'Failed to update hero section: ' . $e->getMessage())
                ->withInput();
        }
    }
}
