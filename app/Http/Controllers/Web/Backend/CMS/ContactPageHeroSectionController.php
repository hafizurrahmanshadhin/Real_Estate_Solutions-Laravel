<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\CMS\ContactPageHeroSectionRequest;
use App\Services\Web\Backend\CMS\ContactPageHeroSectionService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ContactPageHeroSectionController extends Controller {
    protected ContactPageHeroSectionService $heroSectionService;

    public function __construct(ContactPageHeroSectionService $heroSectionService) {
        $this->heroSectionService = $heroSectionService;
    }

    /**
     * Display the hero section of the contact page.
     *
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function index(): RedirectResponse | View {
        try {
            $heroSection = $this->heroSectionService->getOrCreateHeroSection();
            return view('backend.layouts.cms.contact-page.index', compact('heroSection'));
        } catch (Exception $e) {
            Log::error('Contact Page Hero Section Index Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('t-error', 'Failed to load hero section: ' . $e->getMessage());
        }
    }

    /**
     * Update the hero section of the contact page.
     *
     * @param ContactPageHeroSectionRequest $request
     * @return RedirectResponse
     */
    public function update(ContactPageHeroSectionRequest $request): RedirectResponse {
        try {
            // Get validated data
            $validatedData = $request->validated();

            // Add banner file to validated data if present
            if ($request->hasFile('banner')) {
                $validatedData['banner'] = $request->file('banner');
            }

            // Add image file to validated data if present
            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image');
            }

            // Add remove flags
            $validatedData['remove_banner'] = $request->boolean('remove_banner');
            $validatedData['remove_image']  = $request->boolean('remove_image');

            $this->heroSectionService->updateHeroSection($validatedData);

            return redirect()->route('contact-page.hero-section.index')
                ->with('t-success', 'Contact Page Hero Section updated successfully.');

        } catch (Exception $e) {
            Log::error('Contact Page Hero Section Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('t-error', 'Failed to update hero section: ' . $e->getMessage())
                ->withInput();
        }
    }
}
