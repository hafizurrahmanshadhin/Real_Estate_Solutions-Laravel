<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\CMS\OtherPageHeroSectionRequest;
use App\Services\Web\Backend\CMS\OtherPageHeroSectionService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OtherPageHeroSectionController extends Controller {
    protected OtherPageHeroSectionService $heroSectionService;

    public function __construct(OtherPageHeroSectionService $heroSectionService) {
        $this->heroSectionService = $heroSectionService;
    }

    /**
     * Display the hero section of the other page.
     *
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function index(): RedirectResponse | View {
        try {
            $heroSection = $this->heroSectionService->getOrCreateHeroSection();
            return view('backend.layouts.cms.others-page.hero-section', compact('heroSection'));
        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('t-error', 'Failed to load hero section: ' . $e->getMessage());
        }
    }

    /**
     * Update the hero section of the other page.
     *
     * @param OtherPageHeroSectionRequest $request
     * @return RedirectResponse
     */
    public function update(OtherPageHeroSectionRequest $request): RedirectResponse {
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

            return redirect()->route('other-page.hero-section.index')
                ->with('t-success', 'Hero Section updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('t-error', 'Failed to update hero section: ' . $e->getMessage())
                ->withInput();
        }
    }
}
