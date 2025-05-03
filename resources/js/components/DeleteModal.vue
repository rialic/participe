<template>
    <v-dialog v-model="showDestroyModal" max-width="1000">
      <v-card>
        <template #prepend>
          <v-icon icon="fas fa-circle-xmark" size="small" class="text-grey-darken-2"></v-icon>
        </template>

        <template #title>
          <span class="fs-20x text-grey-darken-3 font-weight-bold">{{ title }}</span>
        </template>

        <v-card-text class="d-flex flex-column align-center ga-2">
            <h4>{{ message }}</h4>

            <h4>{{ targetName }}</h4>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            :prepend-icon="icon('fas fa-circle-check')"
            color="teal-lighten-1"
            class="font-weight-bold"
            variant="tonal"
            size="small"
            @click="onConfirmModal">
            Sim
          </v-btn>

          <v-btn
            :prepend-icon="icon('fas fa-circle-xmark')"
            class="font-weight-bold"
            color="black"
            variant="tonal"
            size="small"
            @click="onCloseModal">
            Não
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>

<script setup>
import { ref, watch } from 'vue'
import useIcon from '@/composables/useIcon'

const props = defineProps({
    title: {
        required: true,
        type: String,
        default: 'Exclusão'
    },
    message: {
        required: false,
        type: String,
        default: 'Tem certeza que deseja excluir?'
    },
    targetName: {
        required: false,
        type: String,
    },
    modelValue: {
        require: true,
    }
})

const emit = defineEmits(['onConfirm', 'onClose', 'update:modelValue'])

const { icon } = useIcon()
const showDestroyModal = ref(props.modelValue)

/* watch */
watch(() => props.modelValue, (newVal) => {
    if (newVal !== showDestroyModal.value) {
        showDestroyModal.value = newVal
    }
})

watch(showDestroyModal, (value) => {
    emit('update:modelValue', value)
})

/* functions */
function onConfirmModal() {
    showDestroyModal.value = false

    emit('onConfirm')
}

function onCloseModal() {
    showDestroyModal.value = false

    emit('onClose')
}
</script>