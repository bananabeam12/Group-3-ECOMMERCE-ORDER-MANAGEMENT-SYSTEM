<dialog id="viewImageModal" class="modal">
    <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden relative bg-base-300 rounded-xl">
        
        <!-- Control Buttons -->
        <div class="absolute top-4 right-4 z-10 flex gap-2">
            <!-- Zoom Controls -->
            <div class="join shadow-md">
                <button type="button" onclick="zoomOutImage()" class="btn btn-sm join-item bg-white text-black border-none hover:bg-gray-200" title="Zoom Out">-</button>
                <button type="button" onclick="resetImageTransform()" class="btn btn-sm join-item bg-white text-black border-none hover:bg-gray-200" title="Reset">↺</button>
                <button type="button" onclick="zoomInImage()" class="btn btn-sm join-item bg-white text-black border-none hover:bg-gray-200" title="Zoom In">+</button>
            </div>
            
            <form method="dialog">
                <button class="btn btn-sm btn-circle bg-error text-white border-none shadow-md hover:bg-red-600">✕</button>
            </form>
        </div>
        
        <!-- Interactive Image Container -->
        <div id="imageContainer" class="w-full h-[70vh] flex items-center justify-center overflow-hidden cursor-move relative bg-black/5">
            <img 
                id="zoomableImage" 
                src="" 
                alt="Product Preview" 
                class="max-w-full max-h-full object-contain origin-center select-none" 
                draggable="false"
                style="transform: translate(0px, 0px) scale(1); transition: transform 0.1s ease-out;"
            >
        </div>
    </div>
    <form method="dialog" class="modal-backdrop bg-black/80">
        <button>close</button>
    </form>
</dialog>