<dialog id="restoreModal" class="modal">
    <div class="modal-box w-full max-w-2xl rounded-xl p-8 bg-base-100 border border-black shadow-xl">
        <h3 class="font-black text-xl mb-6 uppercase tracking-widest border-b border-base-300 pb-3">Restore Products</h3>
        
        <!-- Search Bar -->
        <label class="input input-bordered flex items-center gap-3 w-full mb-4 bg-gray-50 border border-base-300 focus-within:border-black rounded-lg transition-all">
            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            <input type="search" id="restoreSearch" placeholder="Search by name or ID..." class="grow text-black placeholder-gray-400 text-sm font-medium bg-transparent border-none focus:outline-none" />
        </label>

        <!-- Archived Products Table -->
        <div class="overflow-x-auto max-h-64 border border-base-300 rounded-lg bg-white mb-4">
            <table class="table table-zebra w-full text-xs sm:text-sm">
                <thead class="bg-base-200/80 text-base-content/70 sticky top-0 z-10 backdrop-blur-sm">
                    <tr>
                        <th class="w-10"><input type="checkbox" id="selectAllRestore" class="checkbox checkbox-sm" /></th>
                        <th class="font-bold uppercase text-xs">Image</th>
                        <th class="font-bold uppercase text-xs">ID</th>
                        <th class="font-bold uppercase text-xs w-full">Product Name</th>
                    </tr>
                </thead>
                <tbody id="archivedProductsList">
                    <tr><td colspan="4" class="text-center py-6 text-gray-500 font-medium">Loading archived vault...</td></tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <button type="button" onclick="submitRestoreProduct()" class="btn bg-black text-white hover:bg-[#ccff00] hover:text-black flex-1 font-bold uppercase tracking-widest rounded-lg transition-colors border-none">
                Restore Selected
            </button>
            <button type="button" onclick="closeRestoreModal()" class="btn btn-ghost text-gray-500 hover:text-black hover:bg-gray-200 w-full sm:w-auto font-bold uppercase tracking-widest rounded-lg border-none">
                Cancel
            </button>
        </div>
    </div>

    <form method="dialog" class="modal-backdrop bg-black/60">
        <button onclick="closeRestoreModal()">close</button>
    </form>
</dialog>