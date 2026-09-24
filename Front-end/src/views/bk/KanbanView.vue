<template>
  <div class="h-full flex flex-col">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800">Kanban Resolusi Konflik</h2>
      <button class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-hover">Ekspor Laporan</button>
    </div>

    <div class="flex-1 flex gap-6 overflow-x-auto pb-4">
      <!-- Column: OPEN -->
      <div class="w-80 flex-shrink-0 flex flex-col bg-gray-50 rounded-xl border border-gray-200">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
          <h3 class="font-bold text-gray-700">Open</h3>
          <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full font-bold">1</span>
        </div>
        <div class="p-3 flex-1 space-y-3 overflow-y-auto">
          <!-- Card -->
          <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer hover:border-blue-400">
            <div class="flex justify-between items-start mb-2">
              <span class="bg-red-100 text-red-800 text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider">BULLYING</span>
              <span class="text-xs text-gray-400">10mnt lalu</span>
            </div>
            <h4 class="font-medium text-gray-800 text-sm mb-2">Pemerasan di Kantin</h4>
            <p class="text-xs text-gray-500 line-clamp-2">Ada siswa kelas 12 yang meminta uang secara paksa di kantin belakang.</p>
            <div class="mt-3 flex items-center justify-between text-xs">
              <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded">Anonim</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Column: IN PROGRESS -->
      <div class="w-80 flex-shrink-0 flex flex-col bg-blue-50/50 rounded-xl border border-blue-100">
        <div class="p-4 border-b border-blue-100 flex justify-between items-center bg-white rounded-t-xl">
          <h3 class="font-bold text-gray-700">In Progress</h3>
          <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-bold">0</span>
        </div>
        <div class="p-3 flex-1 overflow-y-auto">
          <!-- Empty State -->
          <div class="h-32 flex items-center justify-center border-2 border-dashed border-blue-200 rounded-lg">
            <span class="text-sm text-blue-400">Tarik kartu ke sini</span>
          </div>
        </div>
      </div>

      <!-- Column: RESOLVED -->
      <div class="w-80 flex-shrink-0 flex flex-col bg-green-50/50 rounded-xl border border-green-100">
        <div class="p-4 border-b border-green-100 flex justify-between items-center bg-white rounded-t-xl">
          <h3 class="font-bold text-gray-700">Resolved</h3>
          <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-bold">0</span>
        </div>
        <div class="p-3 flex-1 overflow-y-auto"></div>
      </div>
    </div>
  </div>
</template>
