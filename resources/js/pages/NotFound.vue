<template>
    <v-empty-state
      headline="Oops, 404"
      title="Página não encontrada"
      text="A página que você está procurando não existe"
      :image="NotFound"
    >
        <v-btn
            :prepend-icon="icon('fas fa-arrow-left')"
            variant="flat"
            color="primary"
            rounded="sm"
            class="font-weight-bold text-none fs-13x"
            @click="$router.back()">
            Voltar
        </v-btn>
    </v-empty-state>
</template>

<script setup>
    import useIcon from '@/composables/useIcon'
    import NotFound from '@images/404.png?url'

    const { icon } = useIcon()
</script>