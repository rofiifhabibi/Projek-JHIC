<template>
  <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    <!-- Search Header -->
    <div v-if="searchable" class="p-4 border-b border-slate-100 flex flex-col sm:flex-row gap-3 items-center justify-between">
      <div class="relative w-full sm:w-72">
        <Search class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
        <input
          v-model="searchQuery"
          type="text"
          :placeholder="searchPlaceholder"
          class="w-full pl-9 pr-4 py-2 text-xs font-medium rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#355245] transition"
        />
      </div>

      <div class="text-xs text-slate-500 font-medium self-end sm:self-center">
        Menampilkan <span class="font-bold text-slate-800">{{ filteredData.length }}</span> data
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs sm:text-sm">
        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
          <tr>
            <th
              v-for="col in columns"
              :key="col.key"
              class="px-4 py-3.5"
              :class="col.class"
            >
              {{ col.label }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-if="filteredData.length === 0">
            <td :colspan="columns.length" class="px-4 py-8 text-center text-slate-400">
              Tidak ada data yang cocok dengan kriteria pencarian.
            </td>
          </tr>
          <tr
            v-for="(row, idx) in paginatedData"
            :key="row.id || idx"
            class="hover:bg-slate-50/80 transition"
          >
            <td
              v-for="col in columns"
              :key="col.key"
              class="px-4 py-3.5 text-slate-700 font-medium"
              :class="col.class"
            >
              <slot :name="`cell-${col.key}`" :row="row" :value="row[col.key]">
                {{ row[col.key] }}
              </slot>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="p-4 border-t border-slate-100 flex items-center justify-between">
      <span class="text-xs text-slate-500">
        Halaman {{ currentPage }} dari {{ totalPages }}
      </span>
      <div class="flex items-center gap-1.5">
        <button
          @click="currentPage--"
          :disabled="currentPage === 1"
          class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed"
        >
          <ChevronLeft class="w-4 h-4" />
        </button>
        <button
          @click="currentPage++"
          :disabled="currentPage === totalPages"
          class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed"
        >
          <ChevronRight class="w-4 h-4" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Search, ChevronLeft, ChevronRight } from 'lucide-vue-next'

const props = defineProps({
  columns: { type: Array, required: true },
  data: { type: Array, default: () => [] },
  searchable: { type: Boolean, default: true },
  searchPlaceholder: { type: String, default: 'Cari data...' },
  pageSize: { type: Number, default: 10 }
})

const searchQuery = ref('')
const currentPage = ref(1)

const filteredData = computed(() => {
  if (!searchQuery.value) return props.data
  const q = searchQuery.value.toLowerCase()
  return props.data.filter((row) =>
    Object.values(row).some(
      (val) => val && String(val).toLowerCase().includes(q)
    )
  )
})

const totalPages = computed(() => Math.ceil(filteredData.value.length / props.pageSize) || 1)

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * props.pageSize
  return filteredData.value.slice(start, start + props.pageSize)
})

watch(searchQuery, () => {
  currentPage.value = 1
})
</script>
