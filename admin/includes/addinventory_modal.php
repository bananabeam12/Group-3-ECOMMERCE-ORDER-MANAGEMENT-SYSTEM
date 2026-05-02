<section id="addProductModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4 transition-all duration-300">
    
    <main class="add-wrapper bg-white rounded-[2rem] shadow-2xl w-full max-w-md relative max-h-[95vh] overflow-y-auto border border-gray-100">
        
        <button type="button" 
                onclick="document.getElementById('addProductModal').style.display = 'none';" 
                class="absolute top-5 right-5 text-gray-400 cursor-pointer hover:text-black transition-all p-2 rounded-full hover:bg-gray-100 z-10">
            <img src="../assets/images/x_icon.svg" class="w-5 h-5" alt="Close">
        </button>
        
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-black text-gray-900">ADD PRODUCT</h2>
            </div>

            <form id="addProductForm" class="space-y-5">
                <div class="form-group flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-gray-400 tracking-widest uppercase ml-1">Product Name</label>
                    <input type="text" name="productName" placeholder="e.g. SKRRT '26 GRAPHIC TEE" required
                           class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition-all placeholder:text-gray-300">
                </div>

                <div class="form-group flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-gray-400 tracking-widest uppercase ml-1">Category</label>
                    <div class="relative">
                        <select name="categoryId" id="categorySelect" required
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-black transition-all appearance-none cursor-pointer">
                            <option value="">Fetching Categories...</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="form-group flex-1 flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-gray-400 tracking-widest uppercase ml-1">Price (PHP)</label>
                        <input type="number" name="productPrice" step="0.01" placeholder="0.00" required
                               class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-black transition-all">
                    </div>
                    <div class="form-group flex-1 flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-gray-400 tracking-widest uppercase ml-1">Initial Stock</label>
                        <input type="number" name="stockQuantity" placeholder="0" required
                               class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-black transition-all">
                    </div>
                </div>

                <div class="form-group flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-gray-400 tracking-widest uppercase ml-1">Product Images</label>
                    
                    <label class="group flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 hover:border-black transition-all">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">Drop files or click to upload</p>
                        </div>
                        <input type="file" id="imageInput" name="productImages[]" accept="image/*" multiple required class="hidden" onchange="previewImages(event)">
                    </label>
                    
                    <div id="imagePreviewContainer" class="grid grid-cols-4 gap-2 mt-3 min-h-[90px] p-3 border border-gray-100 rounded-2xl bg-gray-50/50 max-h-40 overflow-y-auto">
                        <p id="placeholderText" class="col-span-4 text-[10px] text-gray-400 m-auto uppercase tracking-widest font-medium italic">No images selected</p>
                    </div>
                </div>

                <button type="submit" onclick="addProduct()" class="group relative w-full py-4 bg-black text-white rounded-2xl font-black hover:bg-gray-800 transition-all mt-4 overflow-hidden shadow-lg shadow-black/20">
                    <span class="relative z-10 uppercase tracking-[0.2em] text-sm">ADD TO VAULT</span>
                    <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </button>
            </form>
        </div>
    </main>
</section>
  
