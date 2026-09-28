<template>
  <div class="overflow-hidden rounded-xl border border-border bg-card shadow-subtle">
    <!-- Search Header -->
    <div v-if="searchable" class="flex flex-col items-stretch gap-3 border-b border-border px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="relative w-full sm:max-w-xs">
        <Search class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-muted" />
        <input
          v-model="searchQuery"
          type="text"
          :placeholder="searchPlaceholder"
          aria-label="Cari data tabel"
          class="h-10 w-full rounded-lg border border-border bg-card pl-9 pr-3 text-body text-heading transition-colors placeholder:text-muted/70 hover:border-border-strong focus:border-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/25"
        />
      </div>

      <div class="self-end text-caption text-muted sm:self-center">
        Menampilkan <span class="font-semibold text-heading">{{ filteredData.length }}</span> data
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full min-w-[640px] text-left">
        <thead class="border-b border-border bg-canvas">
          <tr>
            <th
              v-for="col in columns"
              :key="col.key"
              scope="col"
              class="px-4 py-3 text-caption font-semibold text-muted"
              :class="col.class"
            >
              {{ col.label }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border">
          <tr v-if="filteredData.length === 0">
            <td :colspan="columns.length" class="px-4 py-10 text-center text-body text-muted">
              Tidak ada data yang cocok dengan kriteria pencarian.
            </td>
          </tr>
          <tr
            v-for="(row, idx) in paginatedData"
            :key="row.id || idx"
            class="transition-colors hover:bg-canvas"
          >
            <td
              v-for="col in columns"
              :key="col.key"
              class="px-4 py-3 align-middle text-body text-body"
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
    <div v-if="totalPages > 1" class="flex items-center justify-between gap-3 border-t border-border px-4 py-3">
      <span class="text-caption text-muted">
        Halaman {{ currentPage }} dari {{ totalPages }}
      </span>
      <div class="flex items-center gap-1.5">
        <button
          @click="currentPage--"
          :disabled="currentPage === 1"
          aria-label="Halaman sebelumnya"
          class="flex h-9 w-9 items-center justify-center rounded-md border border-border bg-card text-body transition-colors hover:bg-subtle focus:outline-none focus-visible:ring-2 focus-visible:ring-primary disabled:cursor-not-allowed disabled:opacity-40"
        >
          <ChevronLeft class="h-4 w-4" />
        </button>
        <button
          @click="currentPage++"
          :disabled="currentPage === totalPages"
          aria-label="Halaman berikutnya"
          class="flex h-9 w-9 items-center justify-center rounded-md border border-border bg-card text-body transition-colors hover:bg-subtle focus:outline-none focus-visible:ring-2 focus-visible:ring-primary disabled:cursor-not-allowed disabled:opacity-40"
        >
          <ChevronRight class="h-4 w-4" />
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

/* NOTE: filtering behaviour intentionally unchanged in this stage. */
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
