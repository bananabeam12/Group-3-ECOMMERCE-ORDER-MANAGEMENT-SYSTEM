<section id="addCategoryModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4 transition-all duration-300">
    <main class="add-wrapper bg-white rounded-[2rem] shadow-2xl w-full max-w-md relative max-h-[95vh] overflow-y-auto border border-gray-100">
        
        <button type="button" 
                onclick="document.getElementById('addCategoryModal').style.display = 'none';" 
                class="absolute top-5 right-5 text-gray-400 cursor-pointer hover:text-black transition-all p-2 rounded-full hover:bg-gray-100 z-10">
            <img src="../assets/images/x_icon.svg" class="w-5 h-5" alt="Close">
        </button>
        
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-black text-gray-900">ADD CATEGORY</h2>
            </div>

            <form id="addCategoryForm" class="space-y-5">
                <div class="form-group flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-gray-400 tracking-widest uppercase ml-1">Category Name</label>
                    <input type="text" name="categoryName" placeholder="e.g. OUTERWEAR" required
                           class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition-all placeholder:text-gray-300">
                </div>

                <div class="form-group flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-gray-400 tracking-widest uppercase ml-1">Description</label>
                    <textarea name="categoryDescription" placeholder="Optional category description..." rows="3"
                           class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition-all placeholder:text-gray-300 resize-none"></textarea>
                </div>

                <button type="submit" class="group relative w-full py-4 bg-black text-white rounded-2xl font-black hover:bg-gray-800 transition-all mt-4 overflow-hidden shadow-lg shadow-black/20">
                    <span class="relative z-10 uppercase tracking-[0.2em] text-sm">CREATE CATEGORY</span>
                    <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </button>
            </form>
        </div>
    </main>
</section>