<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Monitor Mobilitas Global (Sekolah)</h2>
    
    <div class="bg-white rounded-xl shadow-sm border border-border overflow-hidden">
      <div class="p-4 border-b border-border bg-gray-50 flex justify-between items-center">
        <h3 class="font-semibold text-gray-700">Seluruh Siswa Sedang Berizin</h3>
        <div class="flex gap-2">
          <input type="text" placeholder="Cari NIS/Nama..." class="border border-gray-300 rounded p-1.5 text-sm outline-none focus:ring-1 focus:ring-primary">
        </div>
      </div>
      <div class="p-0 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penanggung Jawab</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Andi Saputra</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">XII TKJ 2</td>
              <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">PENDING</span></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Bpk. Ahmad</td>
            </tr>
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Siti Aminah</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">XII RPL 1</td>
              <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">OVERDUE</span></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Ibu Budi</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
