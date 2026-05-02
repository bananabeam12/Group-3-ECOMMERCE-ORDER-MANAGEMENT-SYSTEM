<dialog id="restockModal" class="modal">
    <div class="modal-box w-full max-w-md rounded-xl p-8 bg-base-100 border border-black shadow-2xl">
        <h3 class="font-black text-xl mb-6 uppercase tracking-widest border-b border-base-300 pb-3">Restock Product</h3>
        
        <form id="restockForm" class="flex flex-col gap-6">
            <!-- Hidden ID to know which product we are restocking -->
            <input type="hidden" id="restockId" name="productId">

            <div class="form-control w-full">
                <label class="label">
                    <span class="mb-2 label-text font-bold text-[10px] uppercase text-gray-500 tracking-widest">Quantity to Add</span>
                </label>
                <input 
                    type="number" 
                    id="restockAmount" 
                    name="restockAmount" 
                    placeholder="0"
                    min="1"
                    class="input input-bordered w-full bg-gray-50 rounded-lg text-lg font-bold focus:border-black"
                >
                <p class="text-[9px] text-gray-400 mt-2 uppercase italic font-medium tracking-wide">
                    This amount will be added to the current stock level.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 mt-2">
                <button type="button" onclick="submitRestock()" class="btn bg-black text-white hover:bg-[#ccff00] hover:text-black flex-1 font-bold uppercase tracking-widest rounded-lg transition-colors border-none">
                    Confirm Restock
                </button>
                <button type="button" onclick="closeRestockModal()" class="btn btn-ghost text-gray-400 hover:text-black hover:bg-gray-100 w-full sm:w-auto font-bold uppercase tracking-widest rounded-lg border-none">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Backdrop for clicking outside to close -->
    <form method="dialog" class="modal-backdrop bg-black/50">
        <button onclick="closeRestockModal()">close</button>
    </form>
</dialog>