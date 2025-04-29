<?php get_header(); ?>

<main id="primary" class="container mx-auto px-4 py-8">

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Smithsonian Open Access Search</h1>

        <!-- Search Form -->
        <div class="bg-gray-100 p-6 rounded-lg shadow-md mb-8">
            <form id="smithsonian-search-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search-query" class="block text-sm font-medium text-gray-700 mb-1">Search
                            Query</label>
                        <input type="text" id="search-query" name="q"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter search terms...">
                    </div>

                    <div>
                        <label for="search-category"
                            class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="search-category" name="category"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            <option value="art_design">Art & Design</option>
                            <option value="history_culture">History & Culture</option>
                            <option value="science_technology">Science & Technology</option>
                        </select>
                    </div>

                    <div>
                        <label for="search-type" class="block text-sm font-medium text-gray-700 mb-1">Content
                            Type</label>
                        <select id="search-type" name="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="edanmdm">Objects</option>
                            <option value="ead_collection">Archives (Collections)</option>
                            <option value="ead_component">Archives (Components)</option>
                            <option value="all">All Types</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search-rows" class="block text-sm font-medium text-gray-700 mb-1">Results Per
                            Page</label>
                        <select id="search-rows" name="rows"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <div>
                        <label for="search-sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                        <select id="search-sort" name="sort"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="relevancy">Relevance</option>
                            <option value="newest">Newest</option>
                            <option value="updated">Recently Updated</option>
                            <option value="random">Random</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Search Smithsonian
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loading-indicator" class="hidden flex justify-center mb-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        </div>

        <!-- Search Results -->
        <div id="search-results" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Results will be populated here via JavaScript -->
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
        const loading = document.getElementById('loading-indicator');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();                   // don’t reload the page
            resultsDiv.innerHTML = '';           // clear old results
            loading.classList.remove('hidden');  // show spinner

            // collect form values
            const q = form.q.value.trim();
            const category = form.category.value;
            const type = form.type.value;
            const rows = form.rows.value;
            const sort = form.sort.value;

            if (!q) {
                alert('Please enter a search term.');
                loading.classList.add('hidden');
                return;
            }

            // choose endpoint: category search wes. general
            let baseUrl;
            if (category) {
                baseUrl = `https://api.si.edu/openaccess/api/v1.0/category/${encodeURIComponent(category)}/search`;
            } else {
                baseUrl = 'https://api.si.edu/openaccess/api/v1.0/search';
            }

            console.log(baseUrl); 3

            // build query string
            const params = new URLSearchParams({
                q,
                rows,
                sort,
                type,
                api_key: API_KEY
            });

            try {
                console.log(`${baseUrl}?${params}`);
                const resp = await fetch(`${baseUrl}?${params}`);
                if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
                const json = await resp.json();

                // error from API?
                if (json.responseCode === 0) {
                    resultsDiv.innerHTML = `<p class="text-red-600">Error: ${json.response.error}</p>`;
                } else {
                    const items = json.response.rows || [];
                    if (!items.length) {
                        resultsDiv.innerHTML = `<p>No results found.</p>`;
                    } else {
                        // render cards
                        resultsDiv.innerHTML = items.map(item => {
                            const mediaArray = item.content?.descriptiveNonRepeating?.online_media?.media;
                            const img = mediaArray && mediaArray.length
                                ? `<img src="${mediaArray[0].thumbnail}" alt="" class="w-full h-48 object-cover mb-2 rounded">`
                                : '';
                            const title = item.content?.descriptiveNonRepeating?.title?.content || 'Untitled';
                            const link = item.content?.descriptiveNonRepeating?.record_link?.content || 'https://collection.cooperhewitt.org/'
                            const medium = item.content?.freetext?.physicalDescription?.[0]?.content || "";
                            const credit = item.content?.freetext?.creditLine?.[0]?.content || "";
                            return `
        <div class="border rounded-lg p-4 shadow hover:shadow-lg transition">
          ${img}
          <span>
          
          <a href="${link}" target="_blank" class="text-base text-[#FF5701] font-semibold hover:underline">${title}</a>

          <span class="text-sm text-black">${medium}</span>
                            
          <span class="text-sm text-black">${credit}</span>

          </span>
        </div>`;
                        }).join('');
                    }
                }
            } catch (err) {
                resultsDiv.innerHTML = `<p class="text-red-600">Fetch error: ${err.message}</p>`;
            } finally {
                loading.classList.add('hidden');
            }
        });
    })();
</script>


<?php get_footer(); ?>