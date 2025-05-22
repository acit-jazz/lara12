<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'

const props = defineProps<{
  defaultValue?: string | number | Array<any>
  modelValue?: any
  class?: HTMLAttributes['class']
  value?: any
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: any): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})

const isCheckbox = computed(() => props.type === 'checkbox')

const inputValue = computed({
  get() {
    if (isCheckbox.value) {
      // For checkbox, modelValue is an array; check if value is in it
      if (Array.isArray(modelValue.value)) {
        return modelValue.value.includes(props.value)
      }
      return !!modelValue.value
    } else {
      return modelValue.value
    }
  },
  set(val) {
    if (isCheckbox.value) {
      if (Array.isArray(modelValue.value)) {
        const arr = [...modelValue.value]
        if (val) {
          if (!arr.includes(props.value)) arr.push(props.value)
        } else {
          const index = arr.indexOf(props.value)
          if (index > -1) arr.splice(index, 1)
        }
        emits('update:modelValue', arr)
      } else {
        emits('update:modelValue', val)
      }
    } else {
      emits('update:modelValue', val)
    }
  },
})
</script>

<template>
  <input
    type="checkbox"
    :value="props.value"
    v-model="inputValue"
    :class="cn(
      'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
      'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
      'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
      props.class,
    )"
  />
</template>
