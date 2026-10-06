<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('categoryManager', () => ({
            openCategoryModal: false,
            newCategoryName: '',
            isLoading: false,
            errorMessage: '',
            successMessage: '',
            categoryEnhanced: false,
            categories: [],
            selectedCategoryId: '',
            categorySearch: '',
            categoryDropdownOpen: false,
            activeCategoryIndex: -1,
            showCategoryError: false,

            initCategorySearch() {
                this.categories = Array.from(this.$refs.categorySelect.options)
                    .filter((option) => option.value)
                    .map((option) => ({ id: String(option.value), name: option.text.trim() }));
                this.selectedCategoryId = String(this.$refs.categorySelect.value || '');
                const selectedCategory = this.categories.find((category) => category.id === this.selectedCategoryId);
                this.categorySearch = selectedCategory ? selectedCategory.name : '';
                this.categoryEnhanced = true;
            },

            get filteredCategories() {
                const query = this.categorySearch.trim().toLocaleLowerCase('id-ID');
                const selectedCategory = this.categories.find((category) => category.id === this.selectedCategoryId);

                if (!query || (selectedCategory && query === selectedCategory.name.toLocaleLowerCase('id-ID'))) {
                    return this.categories;
                }

                return this.categories.filter((category) =>
                    category.name.toLocaleLowerCase('id-ID').includes(query)
                );
            },

            openCategoryDropdown() {
                this.categoryDropdownOpen = true;
                const selectedIndex = this.filteredCategories.findIndex(
                    (category) => category.id === this.selectedCategoryId
                );
                this.activeCategoryIndex = selectedIndex >= 0 ? selectedIndex : (this.filteredCategories.length ? 0 : -1);
                this.$nextTick(() => this.$refs.categorySearch.focus());
            },

            closeCategoryDropdown() {
                this.categoryDropdownOpen = false;
                this.activeCategoryIndex = -1;
            },

            updateCategorySearch() {
                const selectedCategory = this.categories.find((category) => category.id === this.selectedCategoryId);

                if (!selectedCategory || this.categorySearch !== selectedCategory.name) {
                    this.selectedCategoryId = '';
                    this.$refs.categorySelect.value = '';
                }

                this.showCategoryError = false;
                this.$refs.categorySearch.setCustomValidity('');
                this.categoryDropdownOpen = true;
                this.activeCategoryIndex = this.filteredCategories.length ? 0 : -1;
            },

            moveCategoryFocus(direction) {
                if (!this.categoryDropdownOpen) {
                    this.openCategoryDropdown();
                    return;
                }

                const total = this.filteredCategories.length;
                if (!total) return;

                this.activeCategoryIndex = (this.activeCategoryIndex + direction + total) % total;
                this.$nextTick(() => {
                    document.getElementById(`category-option-${this.filteredCategories[this.activeCategoryIndex].id}`)
                        ?.scrollIntoView({ block: 'nearest' });
                });
            },

            selectFocusedCategory() {
                if (!this.categoryDropdownOpen) {
                    this.openCategoryDropdown();
                    return;
                }

                const category = this.filteredCategories[this.activeCategoryIndex];
                if (category) this.selectCategory(category);
            },

            selectCategory(category) {
                this.selectedCategoryId = String(category.id);
                this.categorySearch = category.name;
                this.$refs.categorySelect.value = this.selectedCategoryId;
                this.$refs.categorySelect.dispatchEvent(new Event('change', { bubbles: true }));
                this.showCategoryError = false;
                this.$refs.categorySearch.setCustomValidity('');
                this.closeCategoryDropdown();
            },

            clearCategory() {
                this.selectedCategoryId = '';
                this.categorySearch = '';
                this.$refs.categorySelect.value = '';
                this.$refs.categorySelect.dispatchEvent(new Event('change', { bubbles: true }));
                this.showCategoryError = false;
                this.$nextTick(() => this.openCategoryDropdown());
            },

            addCategory(category) {
                const normalizedCategory = {
                    id: String(category.id),
                    name: category.nama_kategori,
                };

                if (!this.categories.some((item) => item.id === normalizedCategory.id)) {
                    this.categories.push(normalizedCategory);
                    this.$refs.categorySelect.add(new Option(normalizedCategory.name, normalizedCategory.id));
                }

                this.selectCategory(normalizedCategory);
            },

            async saveCategory() {
                if (!this.newCategoryName.trim()) {
                    this.errorMessage = 'Nama kategori tidak boleh kosong!';
                    return;
                }

                this.isLoading = true;
                this.errorMessage = '';
                this.successMessage = '';

                try {
                    const response = await fetch("{{ url(auth()->user()->role->nama_role . '/category/ajax') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ nama_kategori: this.newCategoryName })
                    });
                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.successMessage = data.message;
                        this.addCategory(data.category);

                        setTimeout(() => {
                            this.openCategoryModal = false;
                            this.newCategoryName = '';
                            this.successMessage = '';
                            this.$nextTick(() => this.$refs.categorySearch.focus());
                        }, 1000);
                    } else {
                        this.errorMessage = data.message || 'Terjadi kesalahan.';
                    }
                } catch (error) {
                    this.errorMessage = 'Koneksi terputus. Pastikan internet stabil.';
                } finally {
                    this.isLoading = false;
                }
            }
        }));
    });
</script>
