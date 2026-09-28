<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-150 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-100 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-heading/45 p-4 sm:p-6"
        @click.self="handleBackdropClick"
        @keydown.escape="handleEscape"
        tabindex="-1"
        ref="modalRef"
      >
        <Transition
          enter-active-class="transition duration-150 ease-out"
          enter-from-class="opacity-0 translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition duration-100 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 translate-y-2"
        >
          <div
            v-if="show"
            class="flex w-full max-h-[90vh] flex-col overflow-hidden rounded-xl border border-border bg-card shadow-overlay"
            :class="maxWidthClass"
            role="dialog"
            aria-modal="true"
            :aria-labelledby="titleId"
          >
            <!-- Header -->
            <div class="flex shrink-0 items-center justify-between gap-3 border-b border-border px-5 py-4">
              <h3 :id="titleId" class="flex items-center gap-2 text-h3 font-bold text-heading">
                <slot name="icon" />
                {{ title }}
              </h3>
              <button
                @click="close"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md text-muted transition-colors hover:bg-subtle hover:text-body focus:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                aria-label="Tutup dialog"
              >
                <X class="h-5 w-5" />
              </button>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-5 py-5">
              <slot />
            </div>

            <!-- Footer -->
            <div v-if="$slots.footer" class="flex shrink-0 items-center justify-end gap-2.5 border-t border-border bg-canvas px-5 py-4">
              <slot name="footer" />
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed, watch, ref, nextTick } from 'vue'
import { X } from 'lucide-vue-next'

const props = defineProps({
  show: { type: Boolean, default: false },
  title: { type: String, default: 'Dialog' },
  maxWidth: { type: String, default: 'md' }, // sm, md, lg, xl, 2xl
  closeOnBackdrop: { type: Boolean, default: true }
})

const emit = defineEmits(['close'])

const modalRef = ref(null)
const titleId = computed(() => `modal-title-${Math.random().toString(36).substring(2, 9)}`)

const maxWidthClass = computed(() => {
  switch (props.maxWidth) {
    case 'sm': return 'max-w-sm'
    case 'lg': return 'max-w-lg'
    case 'xl': return 'max-w-xl'
    case '2xl': return 'max-w-2xl'
    default: return 'max-w-md'
  }
})

const close = () => emit('close')
const handleBackdropClick = () => {
  if (props.closeOnBackdrop) close()
}
const handleEscape = () => close()

watch(() => props.show, (val) => {
  if (val) {
    nextTick(() => {
      modalRef.value?.focus()
    })
  }
})
</script>
