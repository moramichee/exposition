<?php

namespace App\Http\Controllers;

use App\Models\ExhibitionElement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ExhibitionController extends Controller
{
    public function home(): View
    {
        return view('exhibition.home', [
            'metaItems' => $this->elementsFor('home', 'hero_meta', $this->defaultMetaItems()),
            'highlightItems' => $this->elementsFor('home', 'highlight', $this->defaultHighlights()),
        ]);
    }

    public function about(): View
    {
        return view('exhibition.about', [
            'missionCards' => $this->elementsFor('about', 'mission_cards', $this->defaultAboutMissionCards()),
            'biographyCards' => $this->elementsFor('about', 'biography', $this->defaultAboutBiographyCards()),
        ]);
    }

    public function artists(): View
    {
        return view('exhibition.artists', [
            'artistCards' => $this->elementsFor('artists', 'artist_cards', $this->defaultArtistCards()),
        ]);
    }

    public function venue(): View
    {
        return view('exhibition.venue', [
            'infoCards' => $this->elementsFor('venue', 'info_cards', $this->defaultVenueInfoCards()),
            'supportCards' => $this->elementsFor('venue', 'support_cards', $this->defaultVenueSupportCards()),
        ]);
    }

    public function press(): View
    {
        return view('exhibition.press', [
            'partnerCards' => $this->elementsFor('press', 'partner_cards', $this->defaultPressPartnerCards()),
            'resourceCards' => $this->elementsFor('press', 'resource_cards', $this->defaultPressResourceCards()),
        ]);
    }

    public function contact(): View
    {
        return view('exhibition.contact', [
            'channelCards' => $this->elementsFor('contact', 'channel_cards', $this->defaultContactChannelCards()),
            'termsCards' => $this->elementsFor('contact', 'terms_cards', $this->defaultContactTermsCards()),
        ]);
    }

    public function privacy(): View
    {
        return view('legal.privacy');
    }

    public function terms(): View
    {
        return view('legal.terms');
    }

    public function cookies(): View
    {
        return view('legal.cookies');
    }

    public function copyright(): View
    {
        return view('legal.copyright');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'country_city' => ['required', 'string', 'max:150'],
            'artwork_types' => ['required', 'string', 'max:300'],
            'gender' => ['required', 'in:Female,Male,Non-binary,Prefer not to say'],
            'email' => ['required', 'email', 'max:150'],
            'whatsapp' => ['required', 'string', 'max:30'],
        ]);

        // Store submissions in the log until a persistence layer is added.
        logger()->info('Exhibition registration submitted.', $validated);

        return redirect()
            ->route('contact')
            ->with('status', 'Registration received. We will contact you on WhatsApp and email with the collaboration contract.');
    }

    public function pressReleasePdf(): Response
    {
        $lines = [
            'Global Alliance Exhibition - Official Press Release',
            'Venue: ExCel London Hotel, London',
            'Dates: June 8-14, 2026 | 10:00-16:00',
            'Organizer: Francois Pinault',
            'Partners: easyJet and Ryanair',
            'Contact: pinault12art@gmail.com | WhatsApp +447405854019',
        ];

        $stream = "BT\n/F1 18 Tf\n72 760 Td\n";
        $stream .= '(' . $this->escapePdfText($lines[0]) . ") Tj\n";
        $stream .= "/F1 12 Tf\n";

        foreach (array_slice($lines, 1) as $line) {
            $stream .= "0 -24 Td\n(" . $this->escapePdfText($line) . ") Tj\n";
        }

        $stream .= "ET";

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>',
            "<< /Length " . strlen($stream) . " >>\nstream\n{$stream}\nendstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $objectNumber = $index + 1;
            $pdf .= "{$objectNumber} 0 obj\n{$object}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        $pdf .= 'trailer' . "\n";
        $pdf .= '<< /Size ' . (count($objects) + 1) . ' /Root 1 0 R >>' . "\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="global-alliance-exhibition-press-release.pdf"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    private function escapePdfText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }

    /**
     * @param  array<int, array<string, mixed>>  $fallback
     * @return Collection<int, ExhibitionElement|object>
     */
    private function elementsFor(string $page, string $section, array $fallback): Collection
    {
        if (! Schema::hasTable('exhibition_elements')) {
            return $this->withFallback(collect(), $fallback);
        }

        $query = ExhibitionElement::query()
            ->active()
            ->where('section', $section);

        if (Schema::hasColumn('exhibition_elements', 'page')) {
            $query->where('page', $page);
        }

        return $this->withFallback($query->ordered()->get(), $fallback);
    }

    /**
     * @param  Collection<int, ExhibitionElement>  $items
     * @param  array<int, array<string, mixed>>  $fallback
     * @return Collection<int, ExhibitionElement|object>
     */
    private function withFallback(Collection $items, array $fallback): Collection
    {
        if ($items->isNotEmpty()) {
            return $items;
        }

        return collect($fallback)->map(static fn (array $item): object => (object) $item);
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function defaultMetaItems(): array
    {
        return [
            [
                'title' => 'Date',
                'content' => 'June 8-14, 2026',
                'secondary_content' => '10:00 to 16:00 daily',
            ],
            [
                'title' => 'Venue',
                'content' => 'ExCel London Hotel',
                'secondary_content' => 'London, United Kingdom',
            ],
            [
                'title' => 'Organizer',
                'content' => 'Francois Pinault',
                'secondary_content' => 'Global cultural patron',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaultHighlights(): array
    {
        return [
            [
                'title' => 'Clear event information',
                'content' => 'Dates, venue and participation terms are available from the first screen.',
                'secondary_content' => '',
            ],
            [
                'title' => 'Professional communication',
                'content' => 'Press, media and legal pages support international trust and transparency.',
                'secondary_content' => '',
            ],
            [
                'title' => 'Exhibitor support',
                'content' => 'Transport, accommodation, customs handling and insured artwork logistics are included.',
                'secondary_content' => '',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaultAboutMissionCards(): array
    {
        return [
            [
                'title' => 'Concept',
                'content' => 'Alliance Expo Monde / Global Alliance Exhibition is a curated international event focused on artists who are talented but still underexposed in mainstream art circuits.',
            ],
            [
                'title' => 'Theme',
                'content' => '"Visibility Without Borders": connecting creators, collectors, and cultural institutions around powerful emerging voices from multiple countries.',
            ],
            [
                'title' => 'Vision',
                'content' => 'Build a recurring global platform where young and lesser-known artists can access serious market opportunities and long-term international recognition.',
            ],
            [
                'title' => 'Objectives',
                'content' => 'Promote hidden artistic talent, support youth integration in the art world, and provide credible international exposure through a structured exhibition ecosystem.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaultAboutBiographyCards(): array
    {
        return [
            [
                'title' => 'Francois Pinault',
                'content' => 'Francois Pinault (born August 21, 1936, Rennes) is a French businessman and art collector, founder of the luxury group Kering. He began in timber trading in the 1960s, then built a major industrial and retail group formerly known as Pinault-Printemps-Redoute (PPR). Under his leadership, the company evolved into Kering, owner of leading luxury houses including Gucci, Yves Saint Laurent and Balenciaga.',
                'extra_content' => 'A major patron of contemporary art, he established renowned exhibition spaces in Venice and Paris to present works from one of the world\'s most significant private contemporary art collections.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaultArtistCards(): array
    {
        return [
            [
                'title' => 'Amara N\'Diaye',
                'secondary_content' => 'Country: Senegal',
                'content' => 'Works at the intersection of textile memory and abstract portraiture.',
                'extra_content' => 'Exhibited works: "Threads of Silence", "DakArt Echoes"',
                'image_path' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=900&q=80',
                'image_alt' => 'Portrait of Amara N\'Diaye',
            ],
            [
                'title' => 'Lucia Ferraro',
                'secondary_content' => 'Country: Italy',
                'content' => 'Contemporary figurative painter exploring migration and belonging.',
                'extra_content' => 'Exhibited works: "Transit Light", "Shoreline Fragments"',
                'image_path' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=80',
                'image_alt' => 'Portrait of Lucia Ferraro',
            ],
            [
                'title' => 'Mateo Alvarez',
                'secondary_content' => 'Country: Colombia',
                'content' => 'Uses layered acrylic and collage to map urban memory.',
                'extra_content' => 'Exhibited works: "Concrete Rhythm", "Southbound Grid"',
                'image_path' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=900&q=80',
                'image_alt' => 'Portrait of Mateo Alvarez',
            ],
            [
                'title' => 'Ayumi Sato',
                'secondary_content' => 'Country: Japan',
                'content' => 'Mixed-media artist focused on organic forms and modern minimalism.',
                'extra_content' => 'Exhibited works: "Breath Surface", "Tidal Geometry"',
                'image_path' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80',
                'image_alt' => 'Portrait of Ayumi Sato',
            ],
            [
                'title' => 'Lena Novak',
                'secondary_content' => 'Country: Croatia',
                'content' => 'Photography-based practice on collective memory and architecture.',
                'extra_content' => 'Exhibited works: "Quiet Facades", "Afterlight Atlas"',
                'image_path' => 'https://images.unsplash.com/photo-1521119989659-a83eee488004?auto=format&fit=crop&w=900&q=80',
                'image_alt' => 'Portrait of Lena Novak',
            ],
            [
                'title' => 'Ibrahim Saleh',
                'secondary_content' => 'Country: Egypt',
                'content' => 'Sculptor working with recycled metal and symbolic geometry.',
                'extra_content' => 'Exhibited works: "Axis of Dust", "Harbor Totem"',
                'image_path' => 'https://images.unsplash.com/photo-1519340333755-c24cc58d3f3a?auto=format&fit=crop&w=900&q=80',
                'image_alt' => 'Portrait of Ibrahim Saleh',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaultVenueInfoCards(): array
    {
        return [
            [
                'title' => 'Full address',
                'content' => 'ExCel London Hotel',
                'secondary_content' => 'Royal Victoria Dock, 1 Western Gateway',
                'extra_content' => 'London E16 1XL, United Kingdom',
            ],
            [
                'title' => 'Event schedule',
                'content' => 'June 8 to June 14, 2026',
                'secondary_content' => 'Open daily from 10:00 to 16:00',
                'extra_content' => 'Registration desk opens at 09:00',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaultVenueSupportCards(): array
    {
        return [
            [
                'title' => 'Transport',
                'content' => 'Closest airports: London City Airport and Heathrow Airport. DLR and Elizabeth line connections are available for ExCel London.',
            ],
            [
                'title' => 'Nearby stays',
                'content' => 'Recommended options: Aloft London ExCeL, DoubleTree by Hilton ExCeL, Crowne Plaza London Docklands.',
            ],
            [
                'title' => 'Logistics support',
                'content' => 'Accommodation, selected meal plans, and artwork transport are covered under event logistics with partner airlines.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaultPressPartnerCards(): array
    {
        return [
            [
                'title' => 'easyJet',
                'content' => 'Official transport partner for artist and cargo logistics.',
            ],
            [
                'title' => 'Ryanair',
                'content' => 'Official airline partner supporting international routing and handling.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaultPressResourceCards(): array
    {
        return [
            [
                'title' => 'Press resources',
                'extra_content' => "Official event identity and partner references\nExhibition overview for editors and media desks\nContact channel for interviews and accreditation",
            ],
            [
                'title' => 'Downloadable documents',
                'content' => 'Access the official PDF communication package.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaultContactChannelCards(): array
    {
        return [
            [
                'title' => 'Official channels',
                'content' => 'Email: pinault12art@gmail.com',
                'secondary_content' => 'WhatsApp: +44 7405 854019',
                'extra_content' => 'Partners: easyJet & Ryanair',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function defaultContactTermsCards(): array
    {
        return [
            [
                'title' => 'Participation terms (summary)',
                'extra_content' => "Recommended max artwork size: 150 cm x 200 cm\nRecommended max weight: 50 kg\nCommission on sold artworks: 15%\nParticipation fee: 2000 EUR\nDamages are covered from boarding to exhibition end",
            ],
        ];
    }
}
