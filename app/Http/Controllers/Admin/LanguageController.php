<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('sort_order')->get();
        
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:languages',
            'name' => 'required|string|max:100',
            'native_name' => 'required|string|max:100',
            'flag_icon' => 'nullable|string|max:10',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'sort_order' => 'integer|min:1'
        ]);

        // If this is set as default, unset others
        if ($validated['is_default'] ?? false) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        // Set sort order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = Language::max('sort_order') + 1;
        }

        Language::create($validated);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language created successfully.');
    }

    public function show(Language $language)
    {
        return view('admin.languages.show', compact('language'));
    }

    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:languages,code,' . $language->id,
            'name' => 'required|string|max:100',
            'native_name' => 'required|string|max:100',
            'flag_icon' => 'nullable|string|max:10',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'sort_order' => 'integer|min:1'
        ]);

        // If this is set as default, unset others
        if ($validated['is_default'] ?? false) {
            Language::where('id', '!=', $language->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $language->update($validated);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated successfully.');
    }

    public function destroy(Language $language)
    {
        // Prevent deletion of default language
        if ($language->is_default) {
            return redirect()->route('admin.languages.index')
                ->with('error', 'Cannot delete the default language.');
        }

        // Check if language has translations
        $hasTranslations = $language->postTranslations()->exists() || 
                          $language->categoryTranslations()->exists();

        if ($hasTranslations) {
            return redirect()->route('admin.languages.index')
                ->with('error', 'Cannot delete language that has translations. Please remove translations first.');
        }

        $language->delete();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language deleted successfully.');
    }

    public function toggleStatus(Language $language)
    {
        // Prevent deactivating the default language
        if ($language->is_default && $language->is_active) {
            return redirect()->route('admin.languages.index')
                ->with('error', 'Cannot deactivate the default language.');
        }

        $language->update(['is_active' => !$language->is_active]);
        
        $status = $language->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.languages.index')
            ->with('success', "Language {$status} successfully.");
    }
}
