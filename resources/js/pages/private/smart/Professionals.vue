<template>
    <base-smart-view
        page-title="Profissionais"
        store-title="Lista de profissionais da saúde"
        :headers="headers"
        default-sort-by="name"
        filter-key="scope_search_professionals"
        :form-config="formConfig"
        :tag-validation="tagValidation"
        :sort-by-mapping="sortByMapping"
        load-function="indexProfessionals"
        send-function="sendProfessionals"
        :mask-function="maskCpf"
    >
        <template #table-row="{ item }">
            <tr>
                <td>{{ item.name }}</td>
                <td>{{ item.cpf }}</td>
                <td>{{ item.cnes }}</td>
                <td>{{ item.cbo }}</td>
            </tr>
        </template>

        <template #custom-form="{ form, validateTag }">
            <v-row>
                <v-col col="12" md="4" class="pt-4 pb-1">
                    <v-text-field
                        label="Data *"
                        v-model="form.custom_date"
                        density="small"
                        required variant="outlined"
                        color="orange-darken-4"
                        hint="Informe o perído que deseja enviar"
                        :persistent-hint="true"
                        placeholder="Data"
                        autocomplete="false"
                        v-maska:[cpf]="'##/####'"
                        clearable>
                    </v-text-field>
                </v-col>
            </v-row>

            <v-row>
                <v-col col="12" md="6">
                    <tags-input
                      v-model="form.include_tag_list"
                      :validate-tag="validateTag"
                      :hint="'Lista de CPF que serão enviados, CPF\'s da lista principal serão excluídos'"
                      placeholder="Incluir CPF"
                    />
                </v-col>
            </v-row>
        </template>
    </base-smart-view>
</template>

<script setup>
import BaseSmartView from '@/pages/private/smart/BaseSmartView.vue'
import TagsInput from '@/components/TagsInput.vue'
import { maskCpf } from '@/helpers'

const headers = [
    { title: 'Nome', icon: 'fa-sort' },
    { title: 'cpf' },
    { title: 'cnes' },
    { title: 'cbo' },
]

const formConfig = {
    custom_date: null,
    include_tag_list: [],
}

const tagValidation = {
    pattern: '[0-9]{11}',
    message: 'Tag deve ser CPF com 11 digitos numéricos sem pontos (.) e traço (-)'
}

const sortByMapping = {
    'Nome': 'name'
}
</script>