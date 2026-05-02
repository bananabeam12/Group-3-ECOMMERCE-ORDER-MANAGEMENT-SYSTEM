<dialog id="editModal" class="modal">
        <div class="modal-box w-full max-w-lg rounded-xl p-6">
            <h3 class="font-bold text-lg mb-4 uppercase tracking-widest">Edit Product</h3>
            <form id="editProductForm" class="flex flex-col gap-3">
                <input type="hidden" id="editId" name="productId">

                <div class="form-group">
                    <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Name</label>
                    <input type="text" id="editName" name="productName"
                        class="input input-bordered input-sm w-full rounded">
                </div>

                <div class="form-group">
                    <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Description</label>
                    <textarea id="editDescription" name="productDescription"
                        class="textarea textarea-bordered textarea-sm w-full rounded resize-none h-20"></textarea>
                </div>

                <div class="form-group">
                    <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Category</label>
                    <select id="editCategory" name="categoryId"
                        class="select select-bordered select-sm w-full rounded"></select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Price (PHP)</label>
                        <input type="number" id="editPrice" name="productPrice" step="0.01"
                            class="input input-bordered input-sm w-full rounded">
                    </div>
                    <div class="form-group">
                        <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Stock</label>
                        <input type="number" id="editStock" name="stockQuantity"
                            class="input input-bordered input-sm w-full rounded">
                    </div>
                </div>

                <div class="form-group">
                    <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Current Gallery</label>
                    <div id="editGalleryPreview"
                        class="flex gap-2 flex-wrap p-2 bg-gray-50 border border-gray-200 rounded-lg min-h-[70px]">
                    </div>
                </div>

                <div class="form-group">
                    <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Update Gallery</label>
                    <input type="file" name="productImages[]" accept="image/*" multiple
                        class="file-input file-input-sm w-full">
                    <p class="text-[9px] text-gray-400 mt-1 uppercase italic">Adding new images will merge into the vault.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 mt-2">
                    <button type="button" onclick="submitProductUpdate()"
                        class="btn btn-success text-white w-full sm:flex-1 font-bold text-xs uppercase">
                        Update Product
                    </button>
                    <button type="button" onclick="closeEditModal()"
                        class="btn btn-ghost text-red-500 w-full sm:w-auto text-xs uppercase">
                        Cancel
                    </button>
                </div>
            </form>
        </div>