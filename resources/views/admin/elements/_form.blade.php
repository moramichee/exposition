@csrf

<div class="admin-form-grid">
    <label for="page">
        Page
        <select id="page" name="page" required>
            @foreach($pageLabels as $value => $label)
                <option value="{{ $value }}" @selected(old('page', $element->page) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
    @error('page')
        <p class="error">{{ $message }}</p>
    @enderror

    <label for="section">
        Section
        <select id="section" name="section" required>
            @foreach($pageSectionMap as $pageKey => $sections)
                <optgroup label="{{ $pageLabels[$pageKey] }}">
                    @foreach($sections as $value => $label)
                        <option value="{{ $value }}" data-page="{{ $pageKey }}" @selected(old('section', $element->section) === $value)>{{ $label }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </label>
    @error('section')
        <p class="error">{{ $message }}</p>
    @enderror

    <label for="title">
        Titre
        <input id="title" type="text" name="title" value="{{ old('title', $element->title) }}" maxlength="150" required>
    </label>
    @error('title')
        <p class="error">{{ $message }}</p>
    @enderror

    <label for="content">
        Contenu principal
        <textarea id="content" name="content" rows="3" maxlength="500">{{ old('content', $element->content) }}</textarea>
    </label>
    @error('content')
        <p class="error">{{ $message }}</p>
    @enderror

    <label for="secondary_content">
        Contenu secondaire
        <textarea id="secondary_content" name="secondary_content" rows="3" maxlength="500">{{ old('secondary_content', $element->secondary_content) }}</textarea>
    </label>
    @error('secondary_content')
        <p class="error">{{ $message }}</p>
    @enderror

    <label for="extra_content">
        Contenu complementaire
        <textarea id="extra_content" name="extra_content" rows="3" maxlength="1000">{{ old('extra_content', $element->extra_content) }}</textarea>
    </label>
    @error('extra_content')
        <p class="error">{{ $message }}</p>
    @enderror

    <label for="image">
        Image (upload)
        <input id="image" type="file" name="image" accept="image/*">
    </label>
    @error('image')
        <p class="error">{{ $message }}</p>
    @enderror

    <label for="image_alt">
        Texte alternatif image
        <input id="image_alt" type="text" name="image_alt" value="{{ old('image_alt', $element->image_alt) }}" maxlength="180">
    </label>
    @error('image_alt')
        <p class="error">{{ $message }}</p>
    @enderror

    @if($element->image_path)
        @php($src = str_starts_with($element->image_path, 'http') ? $element->image_path : asset('storage/'.$element->image_path))
        <div class="admin-image-current">
            <p class="muted">Image actuelle</p>
            <img src="{{ $src }}" alt="{{ $element->image_alt ?? $element->title }}" class="admin-thumb admin-thumb-large">
            <label class="admin-check" for="remove_image">
                <input id="remove_image" type="checkbox" name="remove_image" value="1" @checked(old('remove_image'))>
                Supprimer l'image
            </label>
        </div>
    @endif

    <label for="sort_order">
        Ordre d'affichage
        <input id="sort_order" type="number" name="sort_order" min="0" max="999" value="{{ old('sort_order', $element->sort_order ?? 0) }}" required>
    </label>
    @error('sort_order')
        <p class="error">{{ $message }}</p>
    @enderror

    <label class="admin-check" for="is_active">
        <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $element->exists ? $element->is_active : true))>
        Element actif
    </label>
    @error('is_active')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="admin-form-actions">
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('admin.elements.index') }}" class="btn btn-secondary">Annuler</a>
</div>

<script>
    (function () {
        const pageSelect = document.getElementById('page');
        const sectionSelect = document.getElementById('section');
        if (!pageSelect || !sectionSelect) return;

        const allOptions = Array.from(sectionSelect.querySelectorAll('option[data-page]'));

        const filterSections = () => {
            const selectedPage = pageSelect.value;
            let hasSelected = false;

            allOptions.forEach((option) => {
                const visible = option.dataset.page === selectedPage;
                option.hidden = !visible;
                if (!visible && option.selected) {
                    option.selected = false;
                }
                if (visible && option.selected) {
                    hasSelected = true;
                }
            });

            if (!hasSelected) {
                const firstVisible = allOptions.find((option) => option.dataset.page === selectedPage);
                if (firstVisible) {
                    firstVisible.selected = true;
                }
            }
        };

        pageSelect.addEventListener('change', filterSections);
        filterSections();
    })();
</script>
