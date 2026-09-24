<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Monitoring Kelas Saat Ini</h2>
    
    <div class="grid md:grid-cols-3 gap-6">
      <div class="col-span-2 bg-white rounded-xl shadow-sm border border-border overflow-hidden">
        <div class="p-4 border-b border-border bg-gray-50 flex justify-between items-center">
          <h3 class="font-semibold text-gray-700">Daftar Siswa Berizin (Kelas XII RPL 1)</h3>
          <span class="text-sm text-gray-500">Jam: 08:00 - 09:30</span>
        </div>
        <div class="p-0">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Waktu</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">Budi Santoso</div>
                  <div class="text-sm text-gray-500">12345 / Izin Toilet</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">ACTIVE</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">05:23</td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">Siti Aminah</div>
                  <div class="text-sm text-gray-500">12346 / Ke UKS</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">OVERDUE</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold">-02:15</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <div class="bg-surface-soft rounded-xl border border-secondary p-5">
        <h3 class="font-semibold text-primary mb-4">Ringkasan</h3>
        <div class="space-y-4">
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Total Siswa</span>
            <span class="font-bold">32</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Di Kelas</span>
            <span class="font-bold">30</span>
          </div>
          <div class="flex justify-between items-center text-orange-600">
            <span class="font-medium">Sedang Izin</span>
            <span class="font-bold">2</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
