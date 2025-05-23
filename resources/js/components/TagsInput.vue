<template>
  <div class="tags-input-wrapper">
    <v-card
      :class="containerClasses"
      variant="outlined"
      border="md"
      :ripple="false"
      @click="focusInput"
    >
      <div class="tags-content">
        <v-chip
          v-for="(tag, index) in tags"
          :key="`tag-${index}-${tag}`"
          label
          :color="tagColor"
          variant="flat"
          closable
          size="small"
          class="mx-1"
          @click:close="(e) => handleTagClose(e, index)"
        >
          {{ tag }}
        </v-chip>

        <input
          ref="inputRef"
          v-model="inputValue"
          :placeholder="inputPlaceholder"
          :disabled="disabled"
          class="tag-input"
          @keydown="handleKeydown"
          @focus="handleFocus"
          @blur="handleBlur"
          @paste="handlePaste"
        />
      </div>
    </v-card>

    <transition name="error-fade">
      <div v-if="hasError" class="error-message">
        {{ errorMessage }}
      </div>
    </transition>

    <transition name="error-fade">
      <div v-if="!hasError" class="hint">
        {{ props.hint }}
      </div>
    </transition>

    <v-menu
      v-model="showSuggestions"
      :target="inputRef"
      :close-on-content-click="false"
      location="bottom start"
      width="100%"
      max-height="200"
    >
      <v-list v-if="hasSuggestions" density="compact">
        <v-list-item
          v-for="(suggestion, index) in filteredSuggestions" :key="`suggestion-${index}`"
          :class="getSuggestionItemClass(index)"
          @click="selectSuggestion(suggestion)"
        >
          <v-list-item-title>{{ suggestion }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script setup>
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => []
  },
  placeholder: {
    type: String,
    default: 'Adicionar tag...'
  },
  disabled: {
    type: Boolean,
    default: false
  },
  tagColor: {
    type: String,
    default: ''
  },
  separators: {
    type: Array,
    default: () => [',', ';', '\n']
  },
  allowDuplicates: {
    type: Boolean,
    default: false
  },
  trimValue: {
    type: Boolean,
    default: true
  },
  maxTags: {
    type: Number,
    default: null
  },
  suggestions: {
    type: Array,
    default: () => []
  },
  validateTag: {
    type: Function,
    default: null
  },
  maxSuggestions: {
    type: Number,
    default: 10
  },
  errorTimeout: {
    type: Number,
    default: 3000
  },
  hint: {
    type: String,
    default: null
  }
})

const emit = defineEmits(['update:modelValue', 'tag-added', 'tag-removed', 'input'])

const inputRef = ref(null)
const inputValue = ref('')
const isFocused = ref(false)
const showSuggestions = ref(false)
const selectedSuggestionIndex = ref(-1)
const hasError = ref(false)
const errorMessage = ref('')
const errorTimeoutId = ref(null)

/* computed */
const tags = computed({
  get: () => props.modelValue || [],
  set: (value) => emit('update:modelValue', value)
})

const inputPlaceholder = computed(() =>
  tags.value.length === 0 ? props.placeholder : ''
)

const containerClasses = computed(() => ({
  'tags-input-container': true,
  'focused': isFocused.value,
  'error': hasError.value,
  'disabled': props.disabled
}))

const filteredSuggestions = computed(() => {
  if (!inputValue.value || !props.suggestions.length) return []

  const input = inputValue.value.toLowerCase().trim()
  if (!input) return []

  return props.suggestions
    .filter(suggestion => {
      const suggestionLower = suggestion.toLowerCase()
      return suggestionLower.includes(input) && !tags.value.includes(suggestion)
    })
    .slice(0, props.maxSuggestions)
})

const hasSuggestions = computed(() => filteredSuggestions.value.length > 0)

/* watcg */
watch(inputValue, (newValue) => {
  emit('input', newValue)

  const shouldShowSuggestions = newValue && props.suggestions.length > 0
  showSuggestions.value = shouldShowSuggestions

  if (shouldShowSuggestions) {
    selectedSuggestionIndex.value = -1
  }

  clearError()
})

watch(filteredSuggestions, (newSuggestions) => {
  if (newSuggestions.length === 0) {
    showSuggestions.value = false
    selectedSuggestionIndex.value = -1
  }
})

/* functions */
const focusInput = async () => {
  if (props.disabled) return

  await nextTick()
  inputRef.value?.focus()
}

const handleFocus = () => {
  isFocused.value = true

  if (inputValue.value && hasSuggestions.value) {
    showSuggestions.value = true
  }
}

const handleBlur = () => {
  isFocused.value = false

  setTimeout(() => {
    showSuggestions.value = false
    selectedSuggestionIndex.value = -1
  }, 200)
}

const handleKeydown = (event) => {
  const { key } = event

  if (showSuggestions.value && hasSuggestions.value) {
    switch (key) {
      case 'ArrowDown':
        event.preventDefault()
        navigateSuggestions(1)
        break
      case 'ArrowUp':
        event.preventDefault()
        navigateSuggestions(-1)
        break
      case 'Enter':
        if (selectedSuggestionIndex.value >= 0) {
          event.preventDefault()
          selectSuggestion(filteredSuggestions.value[selectedSuggestionIndex.value])
          return
        }
        break
      case 'Escape':
        closeSuggestions()
        return
    }
  }

  if (key === 'Enter' || props.separators.includes(key)) {
    event.preventDefault()
    addCurrentTag()
    return
  }

  if (key === 'Backspace' && !inputValue.value && tags.value.length > 0) {
    removeTag(tags.value.length - 1)
  }
}

const handlePaste = (event) => {
  event.preventDefault()

  const pastedText = event.clipboardData.getData('text')
  const newTags = parseTagsFromText(pastedText)

  if (newTags.length > 0) {
    addMultipleTags(newTags)
  }
}

const handleTagClose = (event, index) => {
  event.stopPropagation()
  removeTag(index)
}

const navigateSuggestions = (direction) => {
  const maxIndex = filteredSuggestions.value.length - 1
  const currentIndex = selectedSuggestionIndex.value

  if (direction > 0) {
    selectedSuggestionIndex.value = Math.min(currentIndex + 1, maxIndex)
  } else {
    selectedSuggestionIndex.value = Math.max(currentIndex - 1, -1)
  }
}

const closeSuggestions = () => {
  showSuggestions.value = false
  selectedSuggestionIndex.value = -1
}

const getSuggestionItemClass = (index) => ({
  'v-list-item--active': index === selectedSuggestionIndex.value
})

const addCurrentTag = () => {
  const value = props.trimValue ? inputValue.value.trim() : inputValue.value
  if (value) {
    addTag(value)
  }
}

const addTag = (value) => {
  if (!isValidTag(value)) return

  const newTags = [...tags.value, value]
  tags.value = newTags
  inputValue.value = ''
  closeSuggestions()

  emit('tag-added', value)
}

const addMultipleTags = (tagValues) => {
  const currentTags = [...tags.value]
  const addedTags = []

  for (const tagValue of tagValues) {
    if (isValidTag(tagValue, currentTags.length + addedTags.length)) {
      currentTags.push(tagValue)
      addedTags.push(tagValue)
    }
  }

  if (addedTags.length > 0) {
    tags.value = currentTags
    inputValue.value = ''
    closeSuggestions()

    addedTags.forEach(tag => emit('tag-added', tag))
  }
}

const removeTag = (index) => {
  if (index < 0 || index >= tags.value.length) return

  const removedTag = tags.value[index]
  const newTags = tags.value.filter((_, i) => i !== index)

  tags.value = newTags
  emit('tag-removed', removedTag, index)

  nextTick(() => {
    setTimeout(() => {
      inputRef.value?.focus()
    }, 100)
  })
}

const selectSuggestion = (suggestion) => {
  addTag(suggestion)
}

const isValidTag = (value, currentTagCount = null) => {
  clearError()

  const tagCount = currentTagCount ?? tags.value.length

  if (props.maxTags && tagCount >= props.maxTags) {
    showError(`Máximo de ${props.maxTags} tags permitidas`)
    return false
  }

  if (!props.allowDuplicates && tags.value.includes(value)) {
    showError('Tag já existe')
    return false
  }

  if (props.validateTag) {
    const validationResult = props.validateTag(value)
    if (validationResult !== true) {
      showError(validationResult || 'Tag inválida')
      return false
    }
  }

  return true
}

const parseTagsFromText = (text) => {
  let separatorFound = false
  let newTags = []

  const prioritySeparators = [';', ',']

  for (const separator of prioritySeparators) {
    if (text.includes(separator)) {
      newTags = text.split(separator)
      separatorFound = true
      break
    }
  }

  if (!separatorFound) {
    for (const separator of props.separators) {
      if (text.includes(separator)) {
        newTags = text.split(separator)
        separatorFound = true
        break
      }
    }
  }

  if (!separatorFound) {
    newTags = [text]
  }

  return newTags
    .map(tag => props.trimValue ? tag.trim() : tag)
    .filter(tag => tag && tag !== '')
}

const showError = (message) => {
  hasError.value = true
  errorMessage.value = message

  if (errorTimeoutId.value) {
    clearTimeout(errorTimeoutId.value)
  }

  errorTimeoutId.value = setTimeout(() => {
    clearError()
  }, props.errorTimeout)
}

const clearError = () => {
  hasError.value = false
  errorMessage.value = ''

  if (errorTimeoutId.value) {
    clearTimeout(errorTimeoutId.value)
    errorTimeoutId.value = null
  }
}

const clearTags = () => {
  tags.value = []
}

onMounted(() => {
})

onUnmounted(() => {
  clearError()
})

defineExpose({
  focus: focusInput,
  addTag,
  removeTag,
  clearTags
})
</script>

<style scoped>
.tags-input-wrapper {
  width: 100%;
}

.tags-input-container {
  min-height: 36px;
  border-color: #909090 !important;
  cursor: text;
  transition: all 0.2s ease;
}

.tags-input-container.focused {
  border-color: rgb(var(--v-theme-primary)) !important;
}

.tags-input-container.error {
  border-color: rgb(var(--v-theme-error)) !important;
}

.tags-input-container.disabled {
  cursor: not-allowed;
  opacity: 0.6;
}

.tags-content {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  padding: 6px 0 6px 8px;
  height: 100%;
  min-height: 32px;
  gap: 2px;
}

.tag-input {
  border: none;
  outline: none;
  background: transparent;
  flex: 1;
  min-width: 120px;
  padding: 0 0 0 6px;
  font-size: 16px;
  color: rgb(var(--v-theme-on-surface));
  line-height: 1.5;
}

.tag-input::placeholder {
  color: #909090;
  opacity: 1;
  font-size: 16px;
}

.tag-input:disabled {
  cursor: not-allowed;
  color: rgba(var(--v-theme-on-surface), 0.6);
}

.error-message {
  color: rgb(var(--v-theme-error));
  font-size: 12px;
  margin-top: 4px;
  padding-left: 12px;
  font-weight: 500;
}

.hint {
  color: #707070;
  font-size: 12px;
  margin-top: 4px;
  padding-left: 12px;
  font-weight: 500;
}

.v-list-item--active {
  background-color: rgba(var(--v-theme-primary), 0.1) !important;
}

/* Transitions */
.error-fade-enter-active,
.error-fade-leave-active {
  transition: all 0.3s ease;
}

.error-fade-enter-from {
  opacity: 0;
  transform: translateY(-4px);
}

.error-fade-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
  .tags-input-container,
  .error-fade-enter-active,
  .error-fade-leave-active {
    transition: none;
  }
}

/* Mobile optimizations */
@media (max-width: 600px) {
  .tag-input {
    min-width: 80px;
    font-size: 16px; /* Prevents zoom on iOS */
  }

  .tags-content {
    padding: 0 6px;
  }
}
</style>