<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExhibitionElement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ExhibitionElementController extends Controller
{
    /**
     * @var array<string, array<string, string>>
     */
    private const PAGE_SECTION_MAP = [
        'home' => [
            'hero_meta' => 'Cartes Hero (date, lieu, organisateur...)',
            'highlight' => 'Highlights (cartes du bas)',
        ],
        'about' => [
            'mission_cards' => 'Blocs Concept / Theme / Vision / Objectifs',
            'biography' => 'Biographie organisateur',
        ],
        'artists' => [
            'artist_cards' => 'Cartes artistes',
        ],
        'venue' => [
            'info_cards' => 'Informations lieu/date',
            'support_cards' => 'Transport et hebergement',
        ],
        'press' => [
            'partner_cards' => 'Partenaires',
            'resource_cards' => 'Ressources presse',
        ],
        'contact' => [
            'channel_cards' => 'Canaux officiels',
            'terms_cards' => 'Conditions de participation',
        ],
    ];

    public function index(): View
    {
        $elements = ExhibitionElement::query()
            ->orderBy('page')
            ->orderBy('section')
            ->ordered()
            ->get();

        return view('admin.elements.index', [
            'elements' => $elements,
            'pageLabels' => $this->pageLabels(),
            'pageSectionMap' => self::PAGE_SECTION_MAP,
        ]);
    }

    public function create(Request $request): View
    {
        $element = new ExhibitionElement();
        $queryPage = $request->query('page');
        $querySection = $request->query('section');

        if (is_string($queryPage) && array_key_exists($queryPage, self::PAGE_SECTION_MAP)) {
            $element->page = $queryPage;

            if (is_string($querySection) && array_key_exists($querySection, self::PAGE_SECTION_MAP[$queryPage])) {
                $element->section = $querySection;
            } else {
                $defaultSection = array_key_first(self::PAGE_SECTION_MAP[$queryPage]);
                if (is_string($defaultSection)) {
                    $element->section = $defaultSection;
                }
            }

            if (is_string($element->section)) {
                $element->sort_order = $this->nextSortOrder($queryPage, $element->section);
            }
        }

        return view('admin.elements.create', [
            'element' => $element,
            'pageLabels' => $this->pageLabels(),
            'pageSectionMap' => self::PAGE_SECTION_MAP,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        ExhibitionElement::query()->create($this->payload($request));

        return redirect()
            ->route('admin.elements.index')
            ->with('status', 'Element ajoute avec succes.');
    }

    public function edit(ExhibitionElement $element): View
    {
        return view('admin.elements.edit', [
            'element' => $element,
            'pageLabels' => $this->pageLabels(),
            'pageSectionMap' => self::PAGE_SECTION_MAP,
        ]);
    }

    public function update(Request $request, ExhibitionElement $element): RedirectResponse
    {
        $element->update($this->payload($request, $element));

        return redirect()
            ->route('admin.elements.index')
            ->with('status', 'Element modifie avec succes.');
    }

    public function destroy(ExhibitionElement $element): RedirectResponse
    {
        $this->deleteStoredImageIfLocal($element->image_path);
        $element->delete();

        return redirect()
            ->route('admin.elements.index')
            ->with('status', 'Element supprime avec succes.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'page' => ['required', 'in:home,about,artists,venue,press,contact'],
            'section' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:150'],
            'content' => ['nullable', 'string', 'max:500'],
            'secondary_content' => ['nullable', 'string', 'max:500'],
            'extra_content' => ['nullable', 'string', 'max:1000'],
            'image_alt' => ['nullable', 'string', 'max:180'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];

        $allowedSections = array_keys(self::PAGE_SECTION_MAP[$validated['page']]);
        if (! in_array($validated['section'], $allowedSections, true)) {
            throw ValidationException::withMessages([
                'section' => 'Section invalide pour cette page.',
            ]);
        }

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(Request $request, ?ExhibitionElement $element = null): array
    {
        $validated = $this->validatedData($request);

        unset($validated['image'], $validated['remove_image']);

        if ($request->boolean('remove_image')) {
            $this->deleteStoredImageIfLocal($element?->image_path);
            $validated['image_path'] = null;
            $validated['image_alt'] = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteStoredImageIfLocal($element?->image_path);
            $validated['image_path'] = $request->file('image')->store('exhibition-elements', 'public');
            if (empty($validated['image_alt'])) {
                $validated['image_alt'] = $validated['title'];
            }
        }

        return $validated;
    }

    /**
     * @return array<string, string>
     */
    private function pageLabels(): array
    {
        return [
            'home' => 'Home',
            'about' => 'About',
            'artists' => 'Artists',
            'venue' => 'Venue',
            'press' => 'Press',
            'contact' => 'Contact',
        ];
    }

    private function deleteStoredImageIfLocal(?string $imagePath): void
    {
        if (! $imagePath) {
            return;
        }

        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return;
        }

        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    private function nextSortOrder(string $page, string $section): int
    {
        $maxSortOrder = ExhibitionElement::query()
            ->where('page', $page)
            ->where('section', $section)
            ->max('sort_order');

        return is_numeric($maxSortOrder) ? ((int) $maxSortOrder + 1) : 1;
    }
}
