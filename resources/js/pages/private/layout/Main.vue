<template>
    <v-layout>
    <v-navigation-drawer color="grey-darken-3" :width="280" v-model="drawer">
      <template #prepend>
        <v-sheet class="d-flex justify-center py-4 bg-grey-lighten-2">
          <v-img :src="TelessaudeLogo" width="100" height="100"></v-img>
        </v-sheet>
      </template>

      <v-list
        v-if="user"
        v-model:selected="selectedMenuItem"
        density="comfortable"
        nav>
          <template v-for="(module, moduleIndex) in moduleStore.list" :key="moduleIndex">
            <v-list-item
              v-if="!module.submodules?.length"
              v-can:[user.abilities]="modulePermission(module.name)"
              @click.prevent="router.push({ name: moduleRoute(module.name) })"
              :value="module"
              color="orange-darken-4">
              <template #prepend>
                <v-icon :icon="`fas ${moduleIcon(module.name)}`" size="x-small"></v-icon>
              </template>

              <v-list-item-title class="fs-14x text-white font-weight-bold" v-text="module.name"></v-list-item-title>
            </v-list-item>

            <v-list-group v-else fluid
              v-can:[user.abilities]="modulePermission(module.name)"
              :value="module.name"
              :collapse-icon="icon('fas fa-angle-up')"
              :expand-icon="icon('fas fa-angle-down')"
              color="orange-darken-4">
              <template #activator="{ props }">
                <v-list-item
                  v-bind="props">
                  <template #prepend>
                    <v-icon :icon="`fas ${moduleIcon(module.name)}`" size="x-small"></v-icon>
                  </template>

                  <v-list-item-title class="fs-14x text-white font-weight-bold" v-text="module.name"></v-list-item-title>
                </v-list-item>
              </template>

              <v-list-item
                v-for="(submodule, submoduleIndex) in module.submodules" :key="submoduleIndex"
                v-can:[user.abilities]="submodulePermission(submodule.name)"
                @click.prevent="router.push({ name: submoduleRoute(submodule.name) })"
                :value="submodule"
                color="orange-darken-4">

                <v-list-item-title
                  class="fs-14x text-white font-weight-bold"
                  v-text="submodule.name">
                </v-list-item-title>
              </v-list-item>
            </v-list-group>
          </template>
      </v-list>

      <template #append>
        <v-divider></v-divider>

        <v-list
          density="comfortable"
          nav>
            <v-list-item v-for="(footerItem, index) in footerItems" :key="index"
              :value="footerItem"
              color="orange-darken-4"
              @click="logout()">
                <v-list-item-title class="fs-14x text-white font-weight-bold" v-text="footerItem.name"></v-list-item-title>

                <template #append>
                  <v-icon icon="fas fa-arrow-right-from-bracket" size="x-small"></v-icon>
                </template>
          </v-list-item>
        </v-list>
      </template>
    </v-navigation-drawer>

    <v-app-bar color="grey-darken-3" border="b" class="ps-4" flat>
      <svg :class="['ham ham6', { 'active': drawer }]" viewBox="0 0 100 100" width="45" @click="drawer = !drawer">
        <path
              class="line top"
              d="m 30,33 h 40 c 13.100415,0 14.380204,31.80258 6.899646,33.421777 -24.612039,5.327373 9.016154,-52.337577 -12.75751,-30.563913 l -28.284272,28.284272" />
        <path
              class="line middle"
              d="m 70,50 c 0,0 -32.213436,0 -40,0 -7.786564,0 -6.428571,-4.640244 -6.428571,-8.571429 0,-5.895471 6.073743,-11.783399 12.286435,-5.570707 6.212692,6.212692 28.284272,28.284272 28.284272,28.284272" />
        <path
              class="line bottom"
              d="m 69.575405,67.073826 h -40 c -13.100415,0 -14.380204,-31.80258 -6.899646,-33.421777 24.612039,-5.327373 -9.016154,52.337577 12.75751,30.563913 l 28.284272,-28.284272" />
      </svg>

      <v-app-bar-title class="ms-1">{{ appName }}</v-app-bar-title>

      <template #append>
        <v-btn class="text-none me-2" height="48" icon slim>
          <v-icon :icon="'fas fa-user'" size="x-small"></v-icon>

          <v-menu activator="parent">
            <v-list density="compact" nav>
              <v-list-item @click.prevent.stop="router.push({ name: 'user.account' })" link title="Minha conta" />

              <v-list-item @click.prevent.stop="logout()" link title="Sair" />
            </v-list>
          </v-menu>
        </v-btn>
      </template>
    </v-app-bar>

    <v-main>
      <v-toolbar height="110" color="orange-darken-3">
        <v-toolbar-title class="pb-10 px-4">
          <h3 class="text-white">{{ appStore.pageTitle }}</h3>
        </v-toolbar-title>
      </v-toolbar>

        <v-container fluid class="position-relative mt-n16 px-0 pb-0 pb-md-4 px-md-4">
            <!-- <div class="d-flex flex-column mb-4">
              <h2 class="text-grey-darken-3">{{ appStore.pageTitle }}</h2>

              <span>Breadcrumb</span>
            </div> -->

            <v-sheet class="pa-6" color="white" height="100%" width="100%" elevation="1" border="thin" rounded>
              <router-view></router-view>
            </v-sheet>
        </v-container>
    </v-main>
  </v-layout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import TelessaudeLogo from '@images/logo-telessaude.png?url'
import useIcon from '@/composables/useIcon'
import useAuth from '@/composables/useAuth'

import { useAppStore } from '@/stores/appStore'
import { useModuleStore } from '@/stores/moduleStore'

const appStore = useAppStore()
const moduleStore = useModuleStore()

const { user, logout } = useAuth()
const router = useRouter()
const { icon } = useIcon()
const drawer = ref(false)
const appName = import.meta.env.VITE_APP_NAME
const selectedMenuItem = ref()
const footerItems = ref([{ name: 'Sair' } ])

/* onMounted */
onMounted(async () => {
  const response = await moduleStore.index()

  if(response.ok) {
    const data = response.data
    moduleStore.list = data

  }
})

/* computed */
const moduleIcon = computed(() => {
  return (moduleName) => {
    return {
      'dashboard': 'fa-chart-line',
      'tele-educação': 'fa-house-laptop',
      'smart': 'fa-lightbulb',
      'configurações': 'fa-gear',
    }[moduleName.toLowerCase()]
  }
})

const moduleRoute = computed(() => {
  return (moduleName) => {
    return {
      'dashboard': 'dashboard',
      'smart': 'smart',
    }[moduleName.toLowerCase()]
  }
})

const submoduleRoute = computed(() => {
  return (moduleName) => {
    return {
      'webaulas': 'webs.view',
    }[moduleName.toLowerCase()]
  }
})

const modulePermission = computed(() => {
  return (moduleName) => {
    return {
      'dashboard': 'MENU.DASHBOARD',
      'tele-educação': 'MENU.EVENT',
      'smart': 'MENU.SMART',
      'configurações': 'MENU.SETTINGS',
    }[moduleName.toLowerCase()]
  }
})

const submodulePermission = computed(() => {
  return (moduleName) => {
    return {
      'webaulas': 'MENU.EVENT-WEBCLASS',
      'cursos': 'MENU.EVENT-COURSE',
      'usuários': 'MENU.SETTINGS-USER',
      'papéis': 'MENU.SETTINGS-ROLES',
    }[moduleName.toLowerCase()]
  }
})
</script>

<style>

</style>