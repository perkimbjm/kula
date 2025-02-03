<div x-data="lazyLoadComponent()" x-intersect="loadComponent">
    <div x-show="isLoaded">
        <div x-data="ticketSearch()" class="max-w-3xl mx-auto p-6">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-2">Lihat Tanggapan</h2>
                <p class="text-white">Masukkan nomor tiket untuk melihat tanggapan dari Usulan atau Pengaduan Anda</p>
            </div>
    
            <form @submit.prevent="searchTicket" class="mb-8">
                <div class="flex items-center">
                    <div class="relative flex-grow">
                        <input
                            x-model="ticketNumber"
                            type="text"
                            placeholder="Masukkan Nomor Tiket Anda"
                            class="w-full px-6 py-4 text-lg border-1 border-gray-100 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out text-black placeholder:text-blue-500"
                            required
                        >
                    </div>
                    <button
                        type="submit"
                        class="px-8 py-4 bg-cyan-500 text-white font-semibold text-lg rounded-r-lg hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                    >
                        Cari
                    </button>
                </div>
            </form>
    
            <!-- Modal -->
            <template x-if="isLoaded">
                <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto sm:block" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-show="showModal"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="inline-block w-full max-w-3xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:p-6">
                            <div class="absolute top-0 right-0 pt-4 pr-4">
                                <button @click="showModal = false" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                                            Informasi Tiket
                                        </h3>
                                        <div class="mt-2">
                                            <template x-if="ticketData">
                                                <div class="space-y-6">
                                                    <div class="flex justify-between items-center">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-500">Nomor Tiket</p>
                                                            <p class="text-lg font-semibold text-gray-900" x-text="ticketData.ticket.ticket_number"></p>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="text-sm font-medium text-gray-500">Tanggal Tiket</p>
                                                            <p class="text-sm text-gray-900" x-text="new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }).format(new Date(ticketData.ticket.updated_at))"></p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500 mb-1">Status</p>
                                                        <span
                                                            x-text="ticketData.ticket.status === 'open' ? 'Open' : ticketData.ticket.status === 'in_progress' ? 'Dalam Proses' : 'Tiket Ditutup'"
                                                            :class="{
                                                                'bg-green-100 text-green-800': ticketData.ticket.status === 'open',
                                                                'bg-yellow-100 text-yellow-800': ticketData.ticket.status === 'in_progress',
                                                                'bg-red-100 text-red-800': ticketData.ticket.status === 'closed'
                                                            }"
                                                            class="px-2 py-1 text-xs font-semibold rounded-full"
                                                        ></span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500 mb-1">Permasalahan</p>
                                                        <div class="bg-gray-50 p-3 rounded-lg">
                                                            <div x-html="ticketData.ticket.issue" class="prose prose-sm max-w-none"></div>
                                                        </div>
                                                    </div>
                                                    <template x-if="ticketData.ticket.photo_url">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-500 mb-1">Foto</p>
                                                            <img :src="'storage/' + ticketData.ticket.photo_url" alt="Foto" class="w-full h-auto rounded-lg shadow-md" loading="lazy">
                                                        </div>
                                                    </template>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500 mb-1">Tanggapan</p>
                                                        <p class="text-base font-semibold text-gray-900 bg-blue-50 p-3 rounded-lg" x-text="ticketData.latest_response.response"></p>
                                                    </div>
                                                    <template x-if="ticketData.latest_response.updated_at">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-500 mb-1">Tanggal Tanggapan</p>
                                                            <p class="text-sm text-gray-900" x-text="ticketData.latest_response.updated_at"></p>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="!ticketData && !ticketNotFound">
                                                <div class="text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                                    </svg>
                                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Tanggapan</h3>
                                                    <p class="mt-1 text-sm text-gray-500">Maaf, permasalahan Anda belum ditanggapi oleh Admin.</p>
                                                </div>
                                            </template>
                                            <template x-if="ticketNotFound">
                                                <div class="text-center">
                                                    <svg class="mx-auto h-12 w-12 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tiket Tidak Ditemukan</h3>
                                                    <p class="mt-1 text-sm text-red-500">Nomor tiket tidak ditemukan, silahkan periksa lagi input Anda dan pastikan sesuai dengan nomor tiket yang sudah anda catat</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button @click="showModal = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <template x-if="!isLoaded">
        <div class="max-w-3xl mx-auto p-6">
            <div class="text-center">
                <p class="text-gray-600">Memuat komponen...</p>
            </div>
        </div>
    </template>
</div>

<script>
    function lazyLoadComponent() {
        return {
            isLoaded: false,
            loadComponent() {
                this.isLoaded = true;
            }
        };
    }

    function ticketSearch() {
        return {
            ticketNumber: '',
            showModal: false,
            ticketData: null,
            ticketNotFound: false,
            async searchTicket() {
                try {
                    const response = await fetch(`/api/tickets/${this.ticketNumber}`);
                    if (response.ok) {
                        const data = await response.json();
                        this.ticketData = data;
                        this.ticketNotFound = false;
                    } else {
                        this.ticketData = null;
                        this.ticketNotFound = true;
                    }
                    this.showModal = true;
                } catch (error) {
                    console.error('Error fetching ticket data:', error);
                    this.ticketData = null;
                    this.ticketNotFound = true;
                    this.showModal = true;
                }
            }
        }
    }
</script>