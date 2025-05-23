<?php get_header(); ?>

<main id="primary" class="mx-auto px-4 py-8">

    <div class="mx-auto flex flex-col">
        <!-- Search Form -->
        <div class="w-full md:max-w-xl mx-auto h-14">
            <h2 class="uppercase text-sm md:text-2xl text-center text-[#333333] font-black mb-4">Search Smithsonian Art
                & Design Collection
            </h2>
            <form id="smithsonian-search-form" class="relative flex w-full h-full flex-col">
                <div class="flex flex-row justify-between items-center">
                    <div class="w-full">
                        <div class="relative">
                            <label for="search-query" class="sr-only">Search query</label>
                            <input type="text" id="search-query" name="q"
                                class="w-full h-full font-medium text-xs md:text-sm px-4 py-2.5 border border-[#FF5701] focus:outline-none focus:shadow-md focus:shadow-[#FF5701]/50 placeholder:text-xs placeholder:text-[#FF5701] placeholder:font-medium"
                                autofocus placeholder="Enter search terms...">

                            <button id="options-toggle" type="button" aria-expanded="false"
                                aria-controls="options-panel"
                                class="text-xs absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer hover:text-[#FF5701] transition-colors">
                                Options
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit"
                            class="px-4 py-2 bg-[#FF5701] text-white font-black hover:bg-[#d64900] uppercase transition duration-300 hover:shadow-md hover:shadow-[#FF5701]">
                            Search
                        </button>
                    </div>
                </div>

                <div id="options-panel"
                    class="hidden relative z-20 bg-white shadow-lg shadow-[#FF5701]/50 border border-t-0 border-[#FF5701] p-4 mb-4 flex flex-col gap-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="search-rows" class="block text-xs font-medium text-gray-700 mb-1">Results Per
                                Page</label>
                            <select id="search-rows" name="rows"
                                class="text-xs w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FF5701]">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <div>
                            <label for="search-sort" class="block text-xs font-medium text-gray-700 mb-1">Sort
                                By</label>
                            <select id="search-sort" name="sort"
                                class="text-xs w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FF5701]">
                                <option value="relevancy">Relevance</option>
                                <option value="newest">Newest</option>
                                <option value="updated">Recently Updated</option>
                                <option value="random">Random</option>
                            </select>
                        </div>

                    </div>
                    <button id="close-options" type="button" aria-label="Close options panel"
                        class="text-xs cursor-pointer hover:text-[#FF5701] transition-colors text-end">
                        Close
                    </button>
                </div>

                <div id="example-search" class="mt-4 absolute top-10 left-0 right-0 flex flex-wrap items-center gap-2">
                    <h3 class="text-xs md:text-sm font-black text-[#333333] my-1 uppercase">Example Search: </h3>
                    <span
                        class="example-term text-xs md:text-sm text-[#FF5701] font-medium hover:text-black transition duration-300 cursor-pointer">chair</span>
                    <span
                        class="example-term text-xs md:text-sm text-[#FF5701] font-medium hover:text-black transition duration-300 cursor-pointer">pottery</span>
                    <span
                        class="example-term text-xs md:text-sm text-[#FF5701] font-medium hover:text-black transition duration-300 cursor-pointer">textile</span>
                    <span
                        class="example-term text-xs md:text-sm text-[#FF5701] font-medium hover:text-black transition duration-300 cursor-pointer">jewelry</span>
                    <span
                        class="example-term text-xs md:text-sm text-[#FF5701] font-medium hover:text-black transition duration-300 cursor-pointer">poster</span>
                </div>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loading-indicator" class="hidden flex justify-center mt-32" aria-hidden="true" role="status">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#FF5701]"></div>
        </div>

        <!-- Search Results -->
        <div id="live-region" class="sr-only" aria-live="polite"></div>
        <div class="flex flex-col mt-24 md:mt-32">
            <div id="results-header" class="z-0 hidden w-fit px-2 pt-2 bg-[#FF5701] mx-auto md:mx-0 md:ml-24">
                <h2 class="text-2xl text-[#333333] font-black uppercase text-center">Results</h2>
            </div>
            <div id="search-results" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 md:gap-0">
                <!-- Results will be populated here via JavaScript -->
            </div>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="mt-8 flex justify-center hidden">
            <button id="load-more"
                class="px-6 py-2 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                Load More Results
            </button>
        </div>
    </div>
</main>

<script>
    (function () {
        const API_KEY = 'pB1sbfcoLsBKaUF9wcUl6Sbb0OUAcBdv9fBDr2Pf';
        const form = document.getElementById('smithsonian-search-form');
        const resultsDiv = document.getElementById('search-results');
        const resultsHeader = document.getElementById('results-header');
        const loading = document.getElementById('loading-indicator');
        const optionsToggle = document.getElementById('options-toggle');
        const optionsPanel = document.getElementById('options-panel');
        const searchInput = document.getElementById('search-query');
        const exampleTerms = document.querySelectorAll('.example-term');
        const closeOptions = document.getElementById('close-options');
        // Add click event to example search terms
        exampleTerms.forEach(term => {
            term.setAttribute('tabindex', '0');
            term.setAttribute('role', 'button');
            term.addEventListener('click', handleTermClick);
            term.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    handleTermClick();
                }
            });

            function handleTermClick() {
                searchInput.value = term.textContent;
                form.dispatchEvent(new Event('submit'));
            }
        });

        // Toggle options panel
        optionsToggle.addEventListener('click', () => {
            optionsPanel.classList.toggle('hidden');

            if (!optionsPanel.classList.contains('hidden')) {
                document.getElementById('search-rows').focus();
            }
        });

        // Close options panel
        closeOptions.addEventListener('click', () => {
            optionsPanel.classList.add('hidden');
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();                   // don't reload the page
            resultsDiv.innerHTML = '';           // clear old results
            resultsHeader.classList.add('hidden'); // hide results header initially
            optionsPanel.classList.add('hidden');  // hide options panel
            loading.classList.remove('hidden');  // show spinner


            // collect form values
            const q = form.q.value.trim();
            const category = "art_design";
            const type = "edanmdm";
            const rows = form.rows.value || 10;
            const sort = form.sort.value || "relevancy";

            if (!q) {
                alert('Please enter a search term.');
                loading.classList.add('hidden');
                return;
            }

            let baseUrl = `https://api.si.edu/openaccess/api/v1.0/category/${encodeURIComponent(category)}/search`;

            // build query string
            const params = new URLSearchParams({
                q,
                rows,
                sort,
                type,
                api_key: API_KEY
            });

            try {
                const liveRegion = document.getElementById('live-region');
                liveRegion.textContent = 'Loading search results, please wait...';

                const resp = await fetch(`${baseUrl}?${params}`);
                if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
                const json = await resp.json();

                // error from API?
                if (json.responseCode === 0) {
                    resultsDiv.innerHTML = `<div class="col-span-full flex justify-center items-center h-full mt-20"><p class="text-center text-red-600">Error: ${json.response.error}</p></div>`;
                    resultsHeader.classList.remove('hidden'); // show header even for errors
                } else {
                    const items = json.response.rows || [];
                    if (!items.length) {
                        resultsDiv.innerHTML = `<div class="col-span-full flex justify-center items-center h-full mt-20"><p class="text-center text-gray-500">No results found.</p></div>`;
                        resultsHeader.classList.remove('hidden'); // show header for "no results"
                    } else {
                        // Show results header before rendering results
                        resultsHeader.classList.remove('hidden');

                        // Filter items to only include those with images
                        const itemsWithImages = items.filter(item => {
                            const mediaArray = item.content?.descriptiveNonRepeating?.online_media?.media;
                            return mediaArray && mediaArray.length;
                        });

                        if (!itemsWithImages.length) {
                            resultsDiv.innerHTML = `<div class="col-span-full flex justify-center items-center h-full mt-20"><p class="text-center text-gray-500">No items with images found.</p></div>`;
                        } else {
                            // render cards for items with images
                            resultsDiv.innerHTML = itemsWithImages.map(item => {
                                const mediaArray = item.content?.descriptiveNonRepeating?.online_media?.media;
                                const title = item.content?.descriptiveNonRepeating?.title?.content || 'Untitled';
                                const date = item.content?.freetext?.date?.[0]?.content || '';
                                const link = item.content?.descriptiveNonRepeating?.record_link || 'https://collection.cooperhewitt.org/'
                                const medium = item.content?.freetext?.physicalDescription?.[0]?.content || "";
                                const credit = item.content?.freetext?.creditLine?.[0]?.content || "";
                                const altText = item.content?.descriptiveNonRepeating?.online_media?.media[0]?.altTextAccessibility || "Image of" + title + " " + medium + " " + date + " " + credit;
                                const img = `<img src="${mediaArray[0].thumbnail}" alt="${altText}" class="w-full h-full object-contain max-h-[400px]">`;
                                return `
        <div class="grid grid-cols-1 [grid-template-rows:repeat(2,1fr)_max-content]">
            <div class="px-4 sm:px-8 md:px-12 lg:px-16 pt-6 sm:pt-8 md:pt-12 lg:pt-16 pb-3 sm:pb-4 md:pb-6 lg:pb-8 row-span-2 flex justify-center items-center w-full h-full max-h-[400px] overflow-hidden">
                <a href="${link}" target="_blank" class="w-full h-full flex items-center justify-center" aria-label="View image of ${title}">
                ${img}
                </a>
            </div>
        
            <div class="px-4 sm:px-8 md:px-12 lg:px-16 row-span-1">
                <div class="text-[12px] font-medium overflow-hidden text-ellipsis">
                    <a href="${link}" target="_blank" class="text-[#FF5701] hover:text-black transition duration-300 inline" aria-label="View details for ${title}">${title}.</a>
                    <span class="text-black inline">${medium}. ${credit}. ${date}</span>
                </div>
            </div>
        </div>
        `}).join('');
                        }
                    }
                }
            } catch (err) {
                resultsDiv.innerHTML = `<div class="col-span-full flex justify-center items-center h-full mt-20"><p class="text-center text-red-600">Fetch error: ${err.message}</p></div>`;
            } finally {
                loading.classList.add('hidden');
            }
        });
    })();
</script>

<?php get_footer(); ?>