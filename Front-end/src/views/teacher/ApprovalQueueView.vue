<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Antrean Persetujuan</h2>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
      <!-- Card Request -->
      <div class="bg-white rounded-xl shadow-sm border border-border p-5 hover:border-primary transition-colors">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h3 class="font-bold text-gray-900">Andi Saputra</h3>
            <p class="text-sm text-gray-500">XII TKJ 2</p>
          </div>
          <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded font-medium">PENDING</span>
        </div>
        
        <div class="mb-4">
          <p class="text-xs text-gray-500 mb-1">Alasan</p>
          <p class="text-sm text-gray-800 bg-gray-50 p-2 rounded border border-gray-100">Mengambil barang tertinggal di parkiran.</p>
        </div>
        
        <div class="flex gap-2">
          <button class="flex-1 bg-primary hover:bg-primary-hover text-white py-2 rounded-lg text-sm font-medium transition-colors">
            Izinkan (QR)
          </button>
          <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded-lg text-sm font-medium transition-colors">
            Tolak
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
