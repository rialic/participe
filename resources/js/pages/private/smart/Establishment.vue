<template>
    <base-smart-view
        page-title="Estabelecimentos"
        store-title="Lista Estabelecimentos"
        :headers="headers"
        default-sort-by="cnes"
        filter-key="scope_search_establishment"
        :form-config="formConfig"
        :tag-validation="tagValidation"
        :sort-by-mapping="sortByMapping"
        load-function="indexEstablishments"
        send-function="sendEstablishments"
    >
        <template #table-row="{ item }">
            <tr>
                <td>{{ item.name }}</td>
                <td>{{ item.cnes }}</td>
                <td>{{ (item.tdiagn) ? 'Sim' : 'Não' }}</td>
                <td>{{ (item.teduca) ? 'Sim' : 'Não' }}</td>
                <td>{{ (item.tconsul) ? 'Sim' : 'Não' }}</td>
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
                      :hint="'Lista do CNES que serão enviados, CNES da lista principal serão excluídos'"
                      placeholder="Incluir CNES"
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col col="12" md="6">
                    <tags-input
                      v-model="form.exclude_tag_list"
                      :validate-tag="validateTag"
                      placeholder="Excluir CNES"
                      :hint="'Lista do CNES que serão excluídos da lista principal'"
                    />
                </v-col>
            </v-row>
        </template>
    </base-smart-view>
</template>

<script setup>
import BaseSmartView from '@/pages/private/smart/BaseSmartView.vue'
import TagsInput from '@/components/TagsInput.vue'

const headers = [
    { title: 'Nome', icon: 'fa-sort' },
    { title: 'CNES' },
    { title: 'Telediagnóstico' },
    { title: 'Tele-educação' },
    { title: 'Teleconsultoria' },
]

const formConfig = {
    custom_date: null,
    include_tag_list: [],
    exclude_tag_list: [],
}

const tagValidation = {
    pattern: '[0-9]{7}',
    message: 'Tag deve ter 7 caracteres numéricos'
}

const sortByMapping = {
    'Nome': 'name'
}
</script>