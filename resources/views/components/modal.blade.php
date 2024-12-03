<!-- Modal -->
<div
    id="featureModal"
    tabindex="-1"
    aria-labelledby="feature-title"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-75 hidden"
>
    <div class="modal-dialog relative bg-white rounded-lg shadow-lg w-full max-w-md">
        <!-- Modal Header -->
        <div class="modal-header flex justify-between items-center p-4 border-b">
            <h5 class="modal-title text-lg font-semibold" id="feature-title"></h5>
            <button
                type="button"
                id="closeModalButton"
                class="text-gray-500 hover:text-red-500"
                aria-label="Close"
            >
                &times;
            </button>
        </div>
        <!-- Modal Body -->
        <div class="modal-body p-4" id="feature-info"></div>
        <!-- Modal Footer -->
        <div class="modal-footer flex justify-end p-4 border-t">
            <button
                type="button"
                id="closeModalFooterButton"
                class="px-4 py-2 text-sm font-medium text-green-800 bg-green-300 rounded hover:bg-green-500"
            >
                Close
            </button>
        </div>
    </div>
</div>